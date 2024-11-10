<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class AdminController {
    public function index () {
        $orders = Order::where("user_id", Auth::user()->id)->orderBy("id", "DESC")->get()->take(5);
        return view ("admin.index", compact('orders'));
    }
}