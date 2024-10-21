<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartConteller extends Controller
{
    public function index () {
        $items = Cart::instance ("cart")->content();
        return view("cart", compact("items"));
    }
    public function add_to_cart (Request $request) {
        $request->validate([
            "id" => "required|integer",
            "name" => "required|string",
            "quantity" => "required|integer|min:1",
            "price" => "required|numeric|min:0"
        ]);

        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate("App\Models\Product");

        return redirect()->back();
    }
    public function increase_cart_quantity ($rowId) {
        $cart = Cart::instance("cart")->get($rowId);
        $qty = $cart->qty + 1;
        Cart::instance("cart")->update($rowId, $qty);
        return redirect()->back();
    }
    public function decrease_cart_quantity ($rowId) {
        $cart = Cart::instance("cart")->get($rowId);
        $qty = $cart->qty - 1;
        Cart::instance("cart")->update($rowId, $qty);
        return redirect()->back();
    }
    public function delete_cart_item ($rowId) {
        Cart::instance("cart")->remove($rowId);
        return redirect()->back();
    }
    public function delete_cart () {
        Cart::instance("cart")->destroy();
        return redirect()->back();
    }
}
