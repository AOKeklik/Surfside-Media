<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminBrandController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminProductsController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
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

Route::get ("/", [HomeController::class, "index"])->name("home.index");

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
    Route::get("/admin/products", [AdminProductsController::class, "products"])->name("admin.products");
    Route::get("/admin/product/add", [AdminProductsController::class, "add_product"])->name("admin.product.add");
    Route::post ("/admin/product/store", [AdminProductsController::class, "store_product"])->name("admin.product.store");
    Route::get ("/admin/product/edit/{id}", [AdminProductsController::class, "edit_product"])->name("admin.product.edit");
    Route::put ("/admin/product/update", [AdminProductsController::class, "update_product"])->name("admin.product.update");
    Route::delete("/admin/product/delete", [AdminProductsController::class, "delete_product"])->name("admin.product.delete");
});