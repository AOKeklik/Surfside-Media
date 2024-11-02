<?php

namespace App\Http\Controllers;

use App\Models\Cupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminCouponController extends Controller
{
    public function coupons () {
        $coupons = Cupon::orderBy ("expiry_date", "DESC")->paginate(12);
        return view("admin.coupons", compact("coupons"));
    }
    public function add_coupon () {
        $enumValues = DB::select("SHOW COLUMNS FROM cupons LIKE 'type'");
        $typeEnum = str_replace(['enum(', ')', '\''], '', $enumValues[0]->Type);
        $typeOptions = explode(',', $typeEnum);
        return view("admin.coupon-add", compact("typeOptions"));
    }
    public function store_coupon (Request $request) {
        $request->validate([
            "code" => "required|string",
            "type" => ["required", Rule::in(["fixed", "percent"])],
            "value" => "required|numeric",
            "cart_value" => "required|numeric",
            "expiry_date" => "required|date"
        ]);


        $coupon = new Cupon();

        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;

        $coupon->save();

        return redirect()->route("admin.coupons")->with("status", "Coupon has been created successfully!");
    }
    public function edit_coupon ($id) {
        $enumValues = DB::select("show columns from cupons like 'type'");
        $typeEnum = str_replace(["enum(", ")", "'"], "", $enumValues[0]->Type);
        $typeOptions = explode(",", $typeEnum);

        $coupon = Cupon::find ($id);

        return view("admin.coupon-edit", compact("coupon", "typeOptions"));
    }
    public function update_coupon (Request $request) {
        $request->validate([
            "code" => "required|string",
            "type" => ["required", Rule::in(["fixed", "percent"])],
            "value" => "required|numeric",
            "cart_value" => "required|numeric",
            "expiry_date" => "required|date",
        ]);

        $coupon = Cupon::find($request->id);

        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;

        $coupon->save();


        return redirect()->route("admin.coupons")->with("status", "Coupon has been updated successfully!");
    }
    public function delete_coupon (Request $request) {
        $coupon = Cupon::find($request->id);

        if (!$coupon) {
            return redirect()->route("admin.coupons")->with("error", "Coupon not found!");
        }

        $coupon->delete();

        return redirect()->route("admin.coupons")->with("status", "Coupon has been deleted successfully!");
    }
}
