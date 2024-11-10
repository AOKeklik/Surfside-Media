<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::where("status", 1)->orderBy("id", "DESC")->get()->take(3);
        $categories = Category::orderBy("name", "ASC")->get();
        $products = Product::whereNotNull("sale_price")
            ->where("sale_price", "<>", "0")
            ->inRandomOrder()
            ->get()->take(8);
        $fProducts = Product::where("featured", 1)->orderBy("created_at", "DESC")->get()->take(8);

        return view('index', compact("slides", "categories", "products", "fProducts"));
    }
}
