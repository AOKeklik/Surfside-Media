<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminBrandController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminContactController;
use App\Http\Controllers\AdminCouponController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\CartConteller;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

/* WEBSITE */
Route::get ("/", [HomeController::class, "index"])->name("home.index");

/* search */
Route::get("/search", [HomeController::class, "search"])->name("home.search");

/* contact */
Route::get("/contact", [ContactController::class, "index"])->name("contact.index");
Route::post("/contact/add/comment", [ContactController::class, "store_contact"])->name("contact.add.comment");

/* shop */
Route::get("/shop", [ShopController::class, "products"])->name("shop.index");
Route::get("/shop/{slug}", [ShopController::class, "details"])->name("shop.product");

/* cart */
Route::get("/cart", [CartConteller::class, "index"])->name("cart.index");
Route::post("/cart/add", [CartConteller::class, "add_to_cart"])->name("cart.add");
Route::put("/cart/increase-quantity/{rowId}", [CartConteller::class, "increase_cart_quantity"])->name("cart.increase.quantity");
Route::put("/cart/decrease-quantity/{rowId}", [CartConteller::class, "decrease_cart_quantity"])->name("cart.decrease.quantity");
Route::get("/cart/delete-item/{rowId}", [CartConteller::class, "delete_cart_item"])->name("cart.delete.item");
Route::delete("/cart/delete", [CartConteller::class, "delete_cart"])->name("cart.delete");
Route::post("/cart/apply-coupon", [CouponController::class, "apply_cupon_code"])->name("cart.coupon.apply");
Route::delete("/cart/remove-coupon", [CouponController::class, "remove_coupon_code"])->name("cart.coupon.remove");

/* checkout */
Route::get("/checkout", [CheckoutController::class, "checkout"])->name("cart.checkout");
Route::post("/place-an-order", [CheckoutController::class, "place_an_order"])->name("cart.place.an.order");
Route::get("/order-confirmation", [CheckoutController::class, "order_confirmation"])->name("cart.order.confirmation");

/* wishlist */
Route::get("/wishlist", [WishlistController::class, "index"])->name("wishlist.index");
Route::post("/wishlist/add", [WishlistController::class, "add_to_wishlist"])->name("wishlist.add");
Route::delete("/wishlist/delete/{rowId}", [WishlistController::class, "remove_wishlist_item"])->name("wishlist.delete.item");
Route::delete("/wishlist/delete", [WishlistController::class, "remove_wishlist"])->name("wishlist.delete");
Route::get("/wishlist/move-to-cart/{rowId}", [WishlistController::class, "move_to_cart"])->name("wishlist.move.to.cart");


/* USER */
Route::middleware(["auth"])->group(function ()  {
    Route::get("/account-dashboard", [UserController::class, 'index'])->name("user.index");
    Route::get("/account-orders", [UserController::class, "orders"])->name("user.orders");
    Route::get("/account-order/{order_id}", [UserController::class, "order"])->name("user.order.detail");
    Route::put("/acoount-canceled", [UserController::class, "canceled_oder"])->name("user.order.canceled");
});


/* ADMIN */
Route::middleware(["auth", AuthAdmin::class])->group (function () {

    Route::get("/admin", [AdminController::class, "index"])->name("admin.index");
    
/* BRAND */
    Route::get("/admin/brands", [AdminBrandController::class, "brands"])->name("admin.brands");
    Route::get("/admin/brand/add", [AdminBrandController::class, "add_brand"])->name("admin.brand.add");
    Route::post("/admin/brand/store", [AdminBrandController::class, "store_brand"])->name("admin.brand.store");
    Route::get("/admin/brand/edit/{id}", [AdminBrandController::class, "edit_brand"])->name ("admin.brand.edit");
    Route::put("/admin/brand/update", [AdminBrandController::class, "update_brand"])->name ("admin.brand.update");
    // Route::delete("/admin/brand/delete", [AdminController::class, "delete_brand"])->name("admin.brand.delete");
    Route::delete("/admin/brand/{id}/delete", [AdminBrandController::class, "delete_brand"])->name("admin.brand.delete");

/* CATEGORY */
    Route::get("/admin/categories", [AdminCategoryController::class, "categories"])->name("admin.categories");
    Route::get("/admin/category/add", [AdminCategoryController::class, "add_category"])->name("admin.category.add");
    Route::post("/admin/category/store", [AdminCategoryController::class, "store_category"])->name("admin.category.store");
    Route::get("/admin/category/edit/{id}", [AdminCategoryController::class, "edit_category"])->name("admin.category.edit");
    Route::put("/admin/category/update", [AdminCategoryController::class, "update_category"])->name("admin.category.update");
    Route::delete("/admin/category/delete/{id}", [AdminCategoryController::class, "delete_category"])->name("admin.category.delete");

/* PRODUCTS */
    Route::get("/admin/products", [AdminProductController::class, "products"])->name("admin.products");
    Route::get("/admin/product/add", [AdminProductController::class, "add_product"])->name("admin.product.add");
    Route::post ("/admin/product/store", [AdminProductController::class, "store_product"])->name("admin.product.store");
    Route::get ("/admin/product/edit/{id}", [AdminProductController::class, "edit_product"])->name("admin.product.edit");
    Route::put ("/admin/product/update", [AdminProductController::class, "update_product"])->name("admin.product.update");
    Route::delete("/admin/product/delete", [AdminProductController::class, "delete_product"])->name("admin.product.delete");

/* CUPON */
    Route::get("/admin/coupons", [AdminCouponController::class, "coupons"])->name("admin.coupons");
    Route::get("/admin/coupon/add", [AdminCouponController::class, "add_coupon"])->name("admin.coupon.add");
    Route::post("/admin/coupon/add", [AdminCouponController::class, "store_coupon"])->name("admin.coupon.store");
    Route::get("/admin/coupon/edit/{id}", [AdminCouponController::class, "edit_coupon"])->name("admin.coupon.edit");
    Route::put("/admin/coupon/update", [AdminCouponController::class, "update_coupon"])->name("admin.coupon.update");
    Route::delete("/admin/coupon/delete", [AdminCouponController::class, "delete_coupon"])->name("admin.coupon.delete");

/* ORDERS */
    Route::get("/admin/orders", [AdminOrderController::class, "orders"])->name("admin.orders");
    Route::get("/admin/order/detail/{order_id}", [AdminOrderController::class, "order"])->name("admin.order.detail");
    Route::put("/admin/order/update-status", [AdminOrderController::class, "update_status_order"])->name("admin.order.uptdate.status");

/* SLIDER */
    Route::get("/admin/slides", [AdminSliderController::class, "slides"])->name("admin.slides");
    Route::get("/admin/slide/add", [AdminSliderController::class, "add_slide"])->name("admin.slide.add");
    Route::post("/admin/slide/store", [AdminSliderController::class, "store_slide"])->name("admin.slide.store");
    Route::get("/admin/slide/edit/{slide_id}", [AdminSliderController::class, "edit_slide"])->name("admin.slide.edit");
    Route::put("/admin/slide/update", [AdminSliderController::class, "update_slide"])->name("admin.slide.update");
    Route::delete("/admin/slide/delete", [AdminSliderController::class, "delete_slide"])->name("admin.slide.delete");

/* CONTACT */
    Route::get("/admin/contacts", [AdminContactController::class, "contacts"])->name("admin.contacts");
    Route::delete("/admin/contact/delete", [AdminContactController::class, "delete_contact"])->name("admin.contact.delete");

/* SEARCH */
    Route::get("/admin/search", [AdminController::class, "search"])->name("admin.search");
});