<?php

namespace App\Http\Controllers;

use App\Models\Cupon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CouponController extends Controller
{
    public function apply_cupon_code (Request $request) {
        $coupon_code = $request->coupon_code;
        
        if (!isset($coupon_code))
            return redirect ()->back()->with("error", "Invalid coupon code!");

        $coupon = Cupon::where("code", $coupon_code)
            ->where("expiry_date", ">=", Carbon::now())
            ->where("cart_value", "<=", Cart::instance("cart")->subTotal())
            ->first();

        if (!$coupon)
            return redirect ()->back() -> with("error", "Invalid coupon code!");

        Session::put("coupon", [
            "code" => $coupon->code,
            "type" => $coupon->type,
            "value" => $coupon->value,
            "cart_value" => $coupon->cart_value
        ]);

        if (Session::has("coupon")) {
            $discount = 0;

            if (Session::get("coupon")["type"] === "fixed")
                $discount = Session::get("coupon")["value"];
            else
                $discount = Cart::instance("cart")->subtotal() * Session::get("coupon")["value"] / 100;

            $subtotalAfterDiscount = Cart::instance("cart")->subtotal() - $discount;
            $taxAfterDiscount = $subtotalAfterDiscount * config("cart.tax") / 100;
            $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

            Session::put("discounts", [
                "discount" => $discount,
                "subtotal" => $subtotalAfterDiscount,
                "tax" => $taxAfterDiscount,
                "total" => $totalAfterDiscount,
            ]);

            // Session::put("discounts", [
            //     "discount" => number_format(floatval($discount),2,".",""),
            //     "subtotal" => number_format(floatval($subtotalAfterDiscount), 2, ".", ""),
            //     "tax" => number_format(floatval($taxAfterDiscount), 2, ".", ""),
            //     "total" => number_format(floatval($totalAfterDiscount), 2, ".", "")
            // ]);
        }
        
        return redirect()->back()->with("success", "Coupon has been applied!");
    }
    public function remove_coupon_code () {
        if (Session::has ("coupon"))
            Session::forget("coupon");

        if (Session::has ("discounts"))
            Session::forget("discounts");

        return redirect() ->back() ->with("success", "Coupn has been removed!");
    }
}
