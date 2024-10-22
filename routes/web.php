<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminBrandController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\CartConteller;
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
/* wishlist */
Route::get("/wishlist", [WishlistController::class, "index"])->name("wishlist.index");
Route::post("/wishlist/add", [WishlistController::class, "add_to_wishlist"])->name("wishlist.add");
Route::delete("/wishlist/delete/{rowId}", [WishlistController::class, "remove_wishlist_item"])->name("wishlist.delete.item");
Route::delete("/wishlist/delete", [WishlistController::class, "remove_wishlist"])->name("wishlist.delete");
Route::get("/wishlist/move-to-cart/{rowId}", [WishlistController::class, "move_to_cart"])->name("wishlist.move.to.cart");




/* USER */
Route::middleware(["auth"])->group(function ()  {
    Route::get("account-dashboard", [UserController::class, 'index'])->name("user.index");
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
});