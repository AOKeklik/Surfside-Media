<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminOrderController extends Controller
{
    public function orders () {
        $orders = Order::orderBy("created_at", "DESC")->paginate(12);
        return view("admin.orders", compact("orders"));
    }
    public function order ($order_id) {
        $order = Order::find($order_id);
        $orderItems = OrderItem::where("order_id", $order_id)->orderBy("id")->paginate(12);
        $transaction = Transaction::where("order_id", $order_id)->first();
        return view("admin.order", compact("order", "orderItems", "transaction"));
    }
    
    public function update_status_order (Request $request) {
        $order = Order::find($request->order_id);

        if (!$order)
            return redirect()->route("login");

        $order->status = $request->status;
        
        if ($request->status === "delivered")
            $order->delivered_date = Carbon::now();

        if ($request->status === "canceled") 
            $order->canceled_date = Carbon::now();

        $order->save();

        if ($request->status === "delivered") {
            $transaction = Transaction::where("order_id", $request->order_id)->first();
            $transaction->status = "approved";
            $transaction->save();
        }

        return redirect()->back()->with("status", "Order has been updated successfully");
    }
}
