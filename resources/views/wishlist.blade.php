@extends("layouts.app")
@section("content")
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
        <h2 class="page-title">Wishlist</h2>
        @if (Cart::instance("wishlist")->content()->count() > 0)
            <div class="shopping-cart">
                <div class="cart-table__wrapper">
                    <table class="cart-table">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th></th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th colspan="2">Action</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <div class="shopping-cart__product-item">
                                            <img loading="lazy" src="{{asset("uploads/products/thumbnails")}}/{{$item->model->image}}" width="120" height="120" alt="" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shopping-cart__product-item__detail">
                                            <h4>{{$item->name}}</h4>
                                            {{-- <ul class="shopping-cart__product-item__options">
                                                <li>Color: Yellow</li>
                                                <li>Size: L</li>
                                            </ul> --}}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="shopping-cart__product-price">{{$item->price}}</span>
                                    </td>
                                    <td>
                                        <span class="shopping-cart__subtotal">{{$item->subTotal()}}</span>
                                    </td>
                                    <td>
                                        <a href="{{route('wishlist.move.to.cart', ["rowId" => $item->rowId])}}">Move to cart</a>
                                    </td>
                                    <td>
                                        <form action="{{route('wishlist.delete.item', ["rowId" =>$item->rowId])}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a onclick="event.target.closest('form').submit()"  class="remove-cart">
                                                <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                                                <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                                                </svg>
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="cart-table-footer">
                        <a href="{{route('shop.index')}}"  class="btn btn-light">GO TO CART</a>
                        <form action="{{route('wishlist.delete')}}" method="POST" class="btn btn-light">
                            @csrf
                            @method('DELETE')
                            <a onclick="event.target.closest('form').submit()">REMOVE  WISHLIST</a>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <p class="col-12 text-center pt-10 bp-5">No item found in your wishlist!</p>
                <a href="{{route('shop.index')}}" class="col-lg-3 col-md-4 btn btn-info">Like now!</a>
            </div>
        @endif
    </section>
</main>
@endsection