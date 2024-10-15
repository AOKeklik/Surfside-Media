<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;

use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

use Intervention\Image\Laravel\Facades\Image;

class AdminProductsController {
    public function products () {
        $products = Product::orderBy("created_at", "DESC")->paginate(10);
        return view ("admin.products", compact("products"));
    }
    public function add_product () {
        $categories = Category::select("id", "name")->orderBy("name")->get();
        $brands = Brand::select("id", "name")->orderBy("name")->get();

        return view ("admin.product-add", compact("categories", "brands"));
    }
    public function store_product (Request $request) {
        $request->validate([
            "name" => "required",
            "slug" => "required|unique:products,slug",
            "category_id" => "required|integer|exists:categories,id",
            "brand_id" => "required|integer|exists:brands,id",
            "short_description" => "required|max:100",
            "description" => "required|max:300",
            "image" => "required|mimes:jpg,jpeg,png|max:2048",
            "regular_price" => "required|numeric|min:0|max:9223372036854775807",
            "sale_price" => "required|numeric|min:0|max:9223372036854775807",
            "SKU" => "required",
            "quantity" => "required|integer|min:0|max:4294967295",
            "stock_status" => "required",
            "featured" => "required"
        ]);

        $product = new Product ();

        $product->name = $request->name;
        $product->slug = Str::slug($product->name);
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->quantity = $request->quantity;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        
        if ($request->hasFile("image")) {
            $destinationPath = public_path("uploads/products");
            $destinationPathThumbnail = public_path("uploads/products/thumbnails");

            $path = $request->file("image")->path();
            $extention = $request->file("image")->extension();
            $newFileName = Carbon::now()->timestamp.".".$extention;
            
            if (!File::isDirectory($destinationPath))
                File::makeDirectory($destinationPath, 0755, true);

            if (!File::isDirectory($destinationPathThumbnail))
                File::makeDirectory($destinationPathThumbnail, 0755, true);

            if (!File::exists($destinationPath.'/'.$newFileName)) {
                $image = Image::read($path);
                
                $image->cover (540, 689, "top");
                
                $image->resize (540, 689, function ($constraint) {
                    $constraint->aspectRatio();
                })->save ($destinationPath."/".$newFileName);
                $image->resize (104,104, function ($constraint) {
                    $constraint->aspectRatio();
                })->save ($destinationPathThumbnail."/".$newFileName);
            }

            $product->image = $newFileName;
        }

        if ($request->hasFile("images")) {
            $images = [];
            $allowedExtentions = ["jpg", "jpeg", "png"];
            $counter = 1;
            $destinationPath = public_path("uploads/products");
            $destinationPathThumbnail = public_path("uploads/products/thumbnails");

            if (!File::isDirectory($destinationPath))
                File::makeDirectory($destinationPath);

            if (!File::isDirectory($destinationPathThumbnail))
                File::makeDirectory($destinationPathThumbnail, 0755, true);

            foreach ($request->file("images") as $file) {
                $currentExtention = $file->getClientOriginalExtension();
                $isAllowed = in_array($currentExtention, $allowedExtentions);
                $newFileName = Carbon::now()->timestamp."-".$counter.".".$currentExtention;

                if ($isAllowed && !File::exists($destinationPath."/".$newFileName)) {
                    $image = Image::read($file->path());

                    $image->cover(540,689,"top");
                    $image->resize (540,689, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath."/".$newFileName);

                    $image->cover(104,104,"top");
                    $image->resize(104,104,function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPathThumbnail."/".$newFileName);

                    array_push($images, $newFileName);
                }

                $counter++;
            }

            $product->images = implode(", ", $images);
        }

        $product->save();

        return redirect()->route("admin.products")->with("status", "Product has been added successfully!");
    }
    public function edit_product ($id) {
        $product = Product::find ($id);
        $categories = Category::select("id","name")->orderBy("name", "ASC")->get();
        $brands = Brand::select("id","name")->orderBy("name","ASC")->get();
        return view ("admin.product-edit", compact("product","categories","brands"));
    }
    public function update_product (Request $request) {
        $request->validate([
            "category_id" => "required|integer|exists:categories,id",
            "brand_id" => "required|integer|exists:brands,id",
            "name" => "required",
            "slug" => "required|unique:products,slug,".$request->id,
            "short_description" => "required|min:0|max:100",
            "description" => "required|min:0|max:300",
            "regular_price" => "required|numeric|min:0|max:9223372036854775807",
            "sale_price" => "required|numeric|min:0|max:9223372036854775807",
            "SKU" => "required",
            "stock_status" => "required",
            "featured" => "required",
            "quantity" => "required|numeric|min:0|max:4294967295"
        ]);

        $product = Product::find ($request->id);

        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;

        if ($request->hasFile("image")) {
            $destinationPath = public_path("uploads/products");
            $destinationThumbnailPath = public_path("uploads/products/thumbnails");
            $newFileName = Carbon::now()->timestamp.".".$request->file("image")->extension();

            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            if (!File::isDirectory($destinationThumbnailPath)) {
                File::makeDirectory($destinationThumbnailPath, 0755, true);
            }

            if (File::exists($destinationPath."/".$product->image)) {
                File::delete($destinationPath."/".$product->image);
            }

            if (File::exists($destinationThumbnailPath."/".$product->image)) {
                File::delete($destinationThumbnailPath."/".$product->image);
            }

            if (!File::exists($destinationPath.$newFileName)) {
                $image = Image::read($request->file("image")->path()); 
                $image->cover(540,689,"top");
                $image->resize(540,689,function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath."/".$newFileName);

                $image->cover(104,104,"top");
                $image->resize(104,104,function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationThumbnailPath."/".$newFileName);
            }

            $product->image = $newFileName;  
        }

        if ($request->hasFile("images")) {
            $destinationPath = public_path("uploads/products");
            $destinationThumbnailPath = public_path("uploads/products/thumbnails");
            $images = [];
            $counter = 1;

            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            if (!File::isDirectory($destinationThumbnailPath)) {
                File::makeDirectory($destinationThumbnailPath, 0755, true);
            }

            foreach (explode(", ", $product->images) as $deleteFile) {
                if (File::exists($destinationPath."/".$deleteFile)) {
                    File::delete($destinationPath."/".$deleteFile);
                }

                if (File::exists($destinationThumbnailPath."/".$deleteFile)) {
                    File::delete($destinationThumbnailPath."/".$deleteFile);
                }
            }

            foreach ($request->file("images") as $file) {
                $newFileName = Carbon::now()->timestamp."-".$counter.".".$file->getClientOriginalExtension();
                $isAllowed = in_array($file->estension(),["jpg","jpeg","png"]);

                if ($isAllowed && !File::exists($destinationPath."/".$newFileName)) {
                    $image = Image::read($file->path());
                    
                    $image->cover (540,689,"top");
                    $image->resize (540,689,function ($constraint) {
                        $constraint->aspectRatio();
                    })->save ($destinationPath."/".$newFileName);

                    $image->cover(104,104,"top");
                    $image->resize(104,104,function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationThumbnailPath."/".$newFileName);

                    array_push($images, $newFileName);

                    $counter++;
                }
            }

            $product->images = implode(", ", $images);
        }


        $product->save();

        return redirect()->route("admin.products")->with("status", "Product has been updated successfully!");
    }
    public function delete_product (Request $request) {
        $product = Product::find($request->id);

        if (!$product) {
            return redirect()->route("admin.products")->with("status", "Product not found.");
        }

        $destinationPath = public_path("uploads/products");
        $destinationThumbnailPath = public_path("uploads/products/thumbnails");

        
        if ($product->image) {
            $destinationFullPath = $destinationPath."/".$product->image;
            $destinationFullThumbnailPath = $destinationThumbnailPath."/".$product->image;

            if (File::exists($destinationFullPath)) {
                File::delete($destinationFullPath);
            }

            if (File::exists($destinationFullThumbnailPath)) {
                File::delete($destinationFullThumbnailPath);
            }
        }

        if ($product->images) {
            foreach (explode(", ", $product->images) as $file) {
                $destinationFullPath = $destinationPath."/".$file;
                $destinationFullThumbnailPath = $destinationThumbnailPath."/".$file;

                if (File::exists($destinationFullPath)) {
                    File::delete($destinationFullPath);
                }
                
                if (File::exists($destinationFullThumbnailPath)) {
                    File::delete($destinationFullThumbnailPath);
                }
            }
        }

        $product->delete();

        return redirect()->route("admin.products")->with("status", "Product has been deleted successfully!");
    }
}

