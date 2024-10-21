<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function products (Request $request) {

        $size = $request->query("size") ? $request->query("size") : 9;
        $order = $request->query("order") ? $request->query("order") : -1;

        $o_column = "";
        $o_order = "";

        $f_brands = $request->query("brands");
        $f_categories = $request->query("categories");
        $min_price = $request->query("min") ? $request->query("min") : 1;
        $max_price = $request->query("max") ? $request->query("max") : 500;

        switch ($order) {
            case 1: $o_column="created_at";$o_order="DESC";break;
            case 2: $o_column="created_at";$o_order="ASC";break;
            case 3: $o_column="sale_price";$o_order="ASC";break;
            case 4: $o_column="sale_price";$o_order="DESC";break;
            default: $o_column="id";$o_order="DESC";break;
        }

        $brands = Brand::orderBy("id", "DESC")->get();
        $categories = Category::orderBy("id", "DESC")->get();

        $products = Product::where (function ($query) use ($f_brands) {
            $query->whereIn ("brand_id", explode(",", $f_brands))->orWhereRaw("'".$f_brands."'=''");
        })
        ->where (function ($query) use ($f_categories) {
            $query->whereIn ("category_id", explode(",", $f_categories))->orWhereRaw("'".$f_categories."'=''");
        })
        ->where (function ($query) use ($min_price, $max_price) {
            $query->whereBetween("regular_price", [$min_price, $max_price])
            ->orWhereBetween ("sale_price", [$min_price, $max_price]);
        })
        ->orderBy($o_column, $o_order)->paginate($size);

        return view("shop.products", compact("categories", "brands", "products", "size", "order", "f_brands", "f_categories", "min_price", "max_price"));
    }
    public function details ($slug) {
        $product = Product::where("slug", $slug)->first();
        $relatedProducts = Product::where("category_id", $product->category_id)
            ->where ("id", "<>", $product->id)
            ->orderBy ("created_at", "DESC")
            ->get()
            ->take(8);


        return view ("shop.product", compact("product", "relatedProducts"));
    }
}