@extends("layouts.admin")
@section("content")
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Order Details</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Order Details</div>
                </li>
            </ul>
        </div>

        <div class="wg-box mt-5">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Ordered Items</h5>
                </div>
                <a class="tf-button style-1 w208" href="{{route('admin.orders')}}">Back</a>
            </div>
            <div class="table-responsive">
                @if(Session::has("status"))
                    <div class="alert alert-success">{{Session::get("status")}}</div>
                @endif
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <td>{{$order->name}}</td>
                            <th>Mobile</th>
                            <td>{{$order->phone}}</td>
                            <th>Zip Code</th>
                            <td>{{$order->zip}}</td>
                        </tr>
                        <tr>
                            <th>Order Date</th>
                            <td>{{$order->created_at}}</td>
                            <th>Delivered Date</th>
                            <td>{{$order->delivered_date}}</td>
                            <th>Cancaled Date</th>
                            <td>{{$order->canceled_date}}</td>
                        </tr>
                        <tr>
                            <th>Order Status</th>
                            <td colspan="5">
                                @if ($order->status === "delivered")
                                    <span class="badge bg-success text-center">Delivered</span>
                                @elseif($order->status === "canceled")
                                    <span class="badge bg-danger text-center">Canceled</span>    
                                @else
                                    <span class="badge bg-warning text-center">Ordered</span>
                                @endif
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="wg-box mt-5">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Ordered Items</h5>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Brand</th>
                            <th class="text-center">Options</th>
                            <th class="text-center">Return Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderItems as $orderItem)
                            <tr>

                                <td class="pname">
                                    <div class="image">
                                        <img src="{{asset("uploads/products/thumbnails")}}/{{$orderItem->product->image}}" alt="" class="image">
                                    </div>
                                    <div class="name">
                                        <span class="body-title-2">{{$orderItem->product->name}}</span>
                                    </div>
                                </td>
                                <td class="text-center">${{$orderItem->product->sale_price > 0 ? $orderItem->product->sale_price : $orderItem->product->regular_price}}</td>
                                <td class="text-center">{{$orderItem->quantity}}</td>
                                <td class="text-center">{{$orderItem->product->SKU}}</td>
                                <td class="text-center">{{$orderItem->product->category->name}}</td>
                                <td class="text-center">{{$orderItem->product->brand->name}}</td>
                                <td class="text-center"></td>
                                <td class="text-center">{{$orderItem->rstatus == 0 ? "no" : "yes"}}</td>
                                <td class="text-center">
                                    <a  href="{{route("shop.product", ["slug" => $orderItem->product->slug])}}" target="_blank" class="list-icon-function view-icon">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{$orderItems->links("pagination::bootstrap-5")}}
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Shipping Address</h5>
            <div class="my-account__address-item col-md-6">
                <div class="my-account__address-item__detail">
                    <p>{{$order->name}}</p>
                    <p>Flat No - {{$order->address}}, {{$order->locality}} - {{$order->city}}</p>
                    <p>{{$order->landmark}}</p>
                    <p>{{$order->state}}, {{$order->country}}</p>
                    <p>{{$order->zip}}</p>
                    <br>
                    <p>Mobile : {{$order->phone}}</p>
                </div>
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Transactions</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-transaction">
                    <tbody>
                        <tr>
                            <th>Subtotal</th>
                            <td>${{$order->subtotal}}</td>
                            <th>Tax</th>
                            <td>${{$order->tax}}</td>
                            <th>Discount</th>
                            <td>${{$order->discount}}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>${{$order->total}}</td>
                            <th>Payment Mode</th>
                            <td>{{$transaction->mode}}</td>
                            <th>Status</th>
                            <td>
                                @if ($transaction->status === "approved")
                                    <span class="badge bg-success">Appreved</span>
                                @elseif ($transaction->status === "declined")
                                    <span class="badge bg-dangered">Declined</span>
                                @else 
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Update Order Status</h5>
            <form action="{{route('admin.order.uptdate.status')}}" method="POST">
                @csrf
                @method("PUT")
                <input type="hidden" name="order_id" value="{{$order->id}}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="select">
                            <select name="status" id="status">
                                <option value="ordered" @if($order->status === "ordered") selected @endif>Ordered</option>
                                <option value="delivered" @if($order->status === "delivered") selected @endif>Delivered</option>
                                <option value="canceled" @if($order->status === "canceled") selected @endif>Canceled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary tf-button w208">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection