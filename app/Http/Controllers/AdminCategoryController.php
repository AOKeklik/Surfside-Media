<?php

namespace App\Http\Controllers;

use App\Models\Category;

use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

use Intervention\Image\Laravel\Facades\Image;

class AdminCategoryController {
    public function categories () {
        $categories = Category::orderBy("id", "DESC")->paginate (10);
        return view ("admin.categories", compact ("categories"));
    }
    public function add_category () {
        return view ("admin.category-add");
    }
    public function store_category (Request $request) {
        $request->validate([
            "name" => "required",
            "slug" => "required|unique:categories,slug",
            "image" => "mimes:png,jpg,jpeg|max:2048"
        ]);

        $category = new Category ();
        
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile("image")) {
            $image = $request->file("image");
            $file_extension = $request->file("image")->extension();
            $file_name = Carbon::now()->timestamp.".".$file_extension;

            $this->GenerateCategoryThumbnailImage ($image, $file_name);

            $category->image = $file_name;
        }

        $category->save();      
        
        return redirect()->route("admin.categories")->with("status", "Category has been added successfully!");
    }
    public function edit_category ($id) {
        $category =  Category::find($id);
        return view("admin.category-edit", compact("category"));
    }
    public function update_category (Request $request) {
        $request->validate([
            "name" => "required",
            "slug" => "required|unique:categories,slug,".$request->id,
            "image" => "mimes:jpg,png,jpeg|max:2048"
        ]);

        $category = Category::find ($request->id);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile("image")) {
            if (File::exists(public_path("uploads/categories")."/".$category->image))
                File::delete(public_path("uploads/categories")."/".$category->image);

            $destinationPath = public_path ("uploads/categories");
            $image = $request->file("image");
            $extention = $request->file("image")->extension();
            $newImageName = Carbon::now()->timestamp.".".$extention;
            
            if (!File::exists($destinationPath))
                mkdir ($destinationPath, 0755, true);

            $img = Image::read($image->path());
            $img->cover (124, 124, "top");
            $img->resize(124, 124, function ($constratint) {
                $constratint->aspectRotio ();
            })->save($destinationPath."/".$newImageName);

            $category->image = $newImageName;
        }
        
        $category->save();
        return redirect()->route("admin.categories")->with ("status", "Category has been updated successfully!");
    }
    public function delete_category ($id) {
        $category = Category::find ($id);
        
        if ($category->image) {
            $image = public_path("uploads/categories")."/".$category->image;

            if (File::exists($image)) {
                File::delete($image);
            }
        }

        $category->delete($id);

        return redirect()->route("admin.categories")->with ("status", "Category has been deleted successfully!");
    }
    public function GenerateCategoryThumbnailImage ($image, $file_name) {
        $destinationPath = public_path("uploads/categories");

        if (!is_dir($destinationPath)) {
            mkdir ($destinationPath, 0755, true);
        }

        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize (124, 124, function ($constratint) {
            $constratint->aspectRotio();
        })->save($destinationPath."/".$file_name);
    }
}