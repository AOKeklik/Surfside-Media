<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController {
    public function index () {
        $orders = Order::where("user_id", Auth::user()->id)->orderBy("id", "DESC")->get()->take(5);

        $dashboardData = DB::select("select sum(total) TotalAmount, 
        sum(if(status='ordered',total,0)) as TotalOrderedAmount,
        sum(if(status='delivered',total,0)) as TotalDeliveredAmount,
        sum(if(status='canceled',total,0)) as TotalCanceledAmount,
        count(*) as Total,
        sum(if(status='ordered',1,0)) as TotalOrdered,
        sum(if(status='delivered',1,0)) as TotalDelivered,
        sum(if(status='canceled',1,0)) as TotalCanceled
        from orders");

        $motnhlyData = DB::select("select 
        M.id as MonthNo, 
        M.name as MonthName,
        ifnull(D.TotalAmount,0) as TotalAmount,
        ifnull(D.TotalOrderedAmount,0) as TotalOrderedAmount,
        ifnull(D.TotalDeliveredAmount,0) as TotalDeliveredAmount,
        ifnull(D.TotalCanceledAmount,0) as TotalCanceledAmount
        from month_names as M

        left join (select 
        date_format(created_at, '%b') MonthName,
        sum(total) as TotalAmount,
        sum(if(status='ordered',total,0)) as TotalOrderedAmount,
        sum(if(status='delivered',total,0)) as TotalDeliveredAmount,
        sum(if(status='canceled',total,0)) as TotalCanceledAmount,
        month(created_at) as MonthNo
        from orders
        where year(created_at)=year(now()) 
        group by year(created_at), month(created_at), date_format(created_at, '%b')
        order by month(created_at)
        
        ) as D on D.MonthNo=M.id");

        $amountM = implode(",", collect($motnhlyData)->pluck("TotalAmount")->toArray());
        $orderAmountM = implode(",", collect($motnhlyData)->pluck("TotalOrderedAmount")->toArray());
        $deliveredAmountM = implode(",", collect($motnhlyData)->pluck("TotalDeliveredAmount")->toArray());
        $canceledAmountM = implode(",", collect($motnhlyData)->pluck("TotalCanceledAmount")->toArray());

        $totalAmount = collect($motnhlyData)->sum("TotalAmount");
        $totalOrderdAmount = collect($motnhlyData)->sum("TotalOrderedAmount");
        $totalDeliveredAmount = collect($motnhlyData)->sum("TotalDeliveredAmount");
        $totalCanceledAmount = collect($motnhlyData)->sum("TotalCanceledAmount");


        return view ("admin.index", compact(
            "orders", 
            "dashboardData", 
            "motnhlyData",
            "amountM",
            "orderAmountM",
            "deliveredAmountM",
            "canceledAmountM",
            "totalAmount",
            "totalOrderdAmount",
            "totalDeliveredAmount",
            "totalCanceledAmount"
        ));
    }

    public function search (Request $request) {
        $query = $request->input("query");
        $results = Product::where("name", "like", "%{$query}%")->get()->take(8);

        return response()->json($results);
    }
}