<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{
    public function checkout () {
        if (!Auth::check())
            return redirect()->route("login");

        $address = Address::where("user_id", Auth::user()->id)->where("isdefault", 1)->first();

        return view("checkout", compact("address"));
    }
    public function place_an_order (Request $request) {
        $address = Address::where("user_id", Auth::user()->id)->where("isdefault", true)->first();

        if (!$address) {
            $request->validate([
                "name" => "required|string|max:15",
                "phone" => "required|regex:/^[0-9]{10}$/|digits:10",
                "locality" => "required|string",
                "address" => "required|string",
                "city" => "required|string",
                "state" => "required|string",
                "landmark" => "required|string",
                "zip" => "required|numeric|digits:5",
            ]);

            $address = new Address ();

            $address->user_id = Auth::user()->id;
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->locality = $request->locality;
            $address->address = $request->address;
            $address->city = $request->city;
            $address->state = $request->state;
            $address->country = "POLAND";
            $address->landmark = $request->landmark;
            $address->zip = $request->zip;
            $address->isdefault = true;

            $address->save();
        }

        $this->setAmountForCheckout ();

        // print_r(Session::get("checkout"));
        // echo number_format((float) Session::get("checkout")["subtotal"], 2, ".", "");
        // return;

        $order = new Order ();

        $order->user_id = Auth::user()->id;
        $order->subtotal = number_format(floatval(Session::get("checkout")["subtotal"]), 2, ".", "");
        $order->discount = number_format(floatval(Session::get("checkout")["discount"]), 2, ".", "");
        $order->tax = number_format(floatval(Session::get("checkout")["tax"]), 2, ".", "");
        $order->total = number_format(floatval(Session::get("checkout")["total"]), 2, ".", "");
        
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->locality = $address->locality;
        $order->address = $address->address;
        $order->city = $address->city;
        $order->state = $address->state;
        $order->country = $address->country;
        $order->landmark = $address->landmark;
        $order->zip = $address->zip;

        $order->save ();

        foreach (Cart::instance("cart")->content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            
            $orderItem->save();
        }

        if ($request->mode === "card") {}
        elseif ($request->mode === "paypal") {}
        elseif ($request->mode === "cod") {
            $transaction = new Transaction();

            $transaction->user_id = Auth::user()->id;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = "pending";
    
            $transaction->save();
        }

        Cart::instance("cart")->destroy();
        Session::forget("checkout");
        Session::forget("coupon");
        Session::forget("discounts");
        Session::put("order_id", $order->id);

        return redirect()->route("cart.order.confirmation");
    }
    public function setAmountForCheckout () {
        if (!Cart::instance("cart")->content()->count() > 0) {
            Session::forget("checkout");
            return;
        }

        if (Session::has("coupon")) {
            Session::put("checkout", [
                "discount" => Session::get("discounts")["discount"],
                "subtotal" => Session::get("discounts")["subtotal"],
                "tax" => Session::get("discounts")["tax"],
                "total" => Session::get("discounts")["total"]
            ]);
        } else {
            Session::put("checkout", [
                "discount" => 0,
                "subtotal" => Cart::instance("cart")->subtotal(),
                "tax" => Cart::instance("cart")->tax(),
                "total" => Cart::instance("cart")->total(),
            ]);
        }   
    }
    public function order_confirmation () {

        if (Session::has("order_id")) {
            $order = Order::find(Session::get("order_id"));
            return view("order-confirmation", compact("order"));
        }
        return redirect()->route("cart.index");
    }
}
