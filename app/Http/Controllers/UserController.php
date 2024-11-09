<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index () {
        return view ("user.index");
    }

    public function orders () {
        $orders = Order::where("user_id", Auth::user()->id)->orderBy("created_at", "DESC")->paginate(10);
        return view("user.orders", compact("orders"));
    }

    public function order ($order_id) {
        $order = Order::where("id", $order_id)->where("user_id", Auth::user()->id)->first();

        if (!$order) 
            return redirect()->route("login");

        $orderItems = OrderItem::where("order_id", $order_id)->orderBy("id", "DESC")->paginate(12);
        $address = Address::where("user_id", Auth::user()->id)->first();
        $transaction = Transaction::where("user_id", Auth::user()->id)->where("order_id", $order_id)->first();
        return view("user.order", compact("order", "orderItems", "address", "transaction"));
    }

    public function canceled_oder (Request $request) {
        $order = Order::find($request->order_id);
        $order->status = "canceled";
        $order->canceled_date = Carbon::now();
        $order->save();

        return redirect()->back()->with("status", "Order has been canceled successfull!");
    }
}
