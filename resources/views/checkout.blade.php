@extends("layouts.app")
@section("content")
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
        <h2 class="page-title">Shipping and Checkout</h2>
        <div class="checkout-steps">
            <a href="{{route("cart.index")}}" class="checkout-steps__item active">
            <span class="checkout-steps__item-number">01</span>
            <span class="checkout-steps__item-title">
                <span>Shopping Bag</span>
                <em>Manage Your Items List</em>
            </span>
            </a>
            <a href="javascript:void(0)" class="checkout-steps__item active">
            <span class="checkout-steps__item-number">02</span>
            <span class="checkout-steps__item-title">
                <span>Shipping and Checkout</span>
                <em>Checkout Your Items List</em>
            </span>
            </a>
            <a href="javascript:void(0)" class="checkout-steps__item">
            <span class="checkout-steps__item-number">03</span>
            <span class="checkout-steps__item-title">
                <span>Confirmation</span>
                <em>Review And Submit Your Order</em>
            </span>
            </a>
        </div>
        <form name="checkout-form" method="POST" action="{{route('cart.place.an.order')}}">
            @csrf
            @method("POST")
            <div class="checkout-form">
                <div class="billing-info__wrapper">
                    <div class="row">
                        <div class="col-6">
                            <h4>SHIPPING DETAILS</h4>
                        </div>
                        <div class="col-6"></div>
                    </div>
                    @if ($address)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="my-account__address-list">
                                    <div class="my-account__address-item">
                                        <div class="my-account__address-item__detail">
                                            <p>{{$address->name}}</p>
                                            <p>{{$address->address}}</p>
                                            <p>{{$address->landmark}}, {{$address->state}}, {{$address->country}}</p>
                                            <p>{{$address->zip}}</p>
                                            <br />
                                            <p>{{$address->phone}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="name" value="{{old('name')}}" required="">
                                    <label for="name">Full Name *</label>
                                    @error("name") <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" value="{{old("phone")}}" name="phone" required="">
                                    <label for="phone">Phone Number *</label>
                                    @error("phone") <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" value="{{old("zip")}}" name="zip" required="">
                                    <label for="zip">Pincode *</label>
                                    @error("zip") <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mt-3 mb-3">
                                    <input type="text" class="form-control" value="{{old("state")}}" name="state" required="">
                                    <label for="state">State *</label>
                                    @error("state") <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" value="{{old("city")}}" name="city" required="">
                                    <label for="city">Town / City *</label>
                                    @error("city") <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" value="{{old("address")}}" name="address" required="">
                                    <label for="address">House no, Building Name *</label>
                                    @error("address") <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" value="{{old("locality")}}" name="locality" required="">
                                    <label for="locality">Road Name, Area, Colony *</label>
                                    @error("locality") <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" value="{{old("landmark")}}" name="landmark" required="">
                                    <label for="landmark">Landmark *</label>
                                    @error("landmark") <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="checkout__totals-wrapper">
                    <div class="sticky-content">
                    <div class="checkout__totals">
                        <h3>Your Order</h3>
                        <table class="checkout-cart-items">
                            <thead>
                                <tr>
                                    <th>PRODUCT</th>
                                    <th class="text-right">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Cart::instance("cart")->content() as $cartItem)
                                    <tr>
                                        <td>{{$cartItem->name}} x {{$cartItem->qty}}</td>
                                        <td class="text-right">{{$cartItem->subtotal()}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table class="checkout-totals">
                        <tbody>
                            @if (Session::has("discounts"))
                                <tr>
                                    <th>Coupon {{Session::get("coupon")["code"]}}</th>
                                    <td class="text-right">${{Session::get("discounts")["discount"]}}</td>
                                </tr>
                                <tr>
                                    <th>SUBTOTAL</th>
                                    <td class="text-right">${{Session::get("discounts")["subtotal"]}}</td>
                                </tr>
                                <tr>
                                    <th>SHIPPING</th>
                                    <td class="text-right">Free shipping</td>
                                </tr>
                                <tr>
                                    <th>VAT</th>
                                    <td class="text-right">${{Session::get("discounts")["tax"]}}</td>
                                </tr>
                                <tr>
                                    <th>TOTAL</th>
                                    <td class="text-right">${{Session::get("discounts")["total"]}}</td>
                                </tr>
                            @else
                                <tr>
                                    <th>SUBTOTAL</th>
                                    <td class="text-right">${{Cart::instance("cart")->subtotal()}}</td>
                                </tr>
                                <tr>
                                    <th>SHIPPING</th>
                                    <td class="text-right">Free shipping</td>
                                </tr>
                                <tr>
                                    <th>VAT</th>
                                    <td class="text-right">${{Cart::instance("cart")->tax()}}</td>
                                </tr>
                                <tr>
                                    <th>TOTAL</th>
                                    <td class="text-right">${{Cart::instance("cart")->total()}}</td>
                                </tr>
                            @endif
                        </tbody>
                        </table>
                    </div>
                    <div class="checkout__payment-methods">
                        <div class="form-check">
                            <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode_1" value="card" checked>
                            <label class="form-check-label" for="mode_1">
                                Debit or Credit Cart
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode_2" value="paypal">
                            <label class="form-check-label" for="mode_2"> 
                                Paypal 
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode_3" value="cod">
                            <label class="form-check-label" for="mode_3">
                                Cash on delivery
                            </label>
                        </div>
                        <div class="policy-text">
                        Your personal data will be used to process your order, support your experience throughout this
                        website, and for other purposes described in our <a href="terms.html" target="_blank">privacy policy</a>.
                        </div>
                    </div>
                    <button class="btn btn-primary btn-checkout">PLACE ORDER</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</main>
@endsection