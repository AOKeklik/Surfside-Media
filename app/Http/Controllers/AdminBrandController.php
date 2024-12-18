<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class AdminBrandController extends Controller
{
    public function brands () {
        $brands = Brand::orderBy ("id", "DESC")->paginate (10);
        return view ('admin.brands', compact('brands'));
    }
    public function add_brand () {
        return view ("admin.brand-add");
    }
    public function store_brand (Request $request) {
        $request->validate([
            "name" => "required",
            "slug" => "required|unique:brands,slug",
            "image" => "mimes:png,jpg,jpeg|max:2048"
        ]);

        $brand = new Brand ();

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile("image")) {
            $destinationPath = public_path("uploads/brands");

            if (!File::exists($destinationPath))
                mkdir($destinationPath, 0755, true);


            $filePath = $request->file("image")->path();
            $fileExtention = $request->file("image")->extension();
            $fileName = Carbon::now()->timestamp.".".$fileExtention;

            $img = Image::read($filePath);
            $img->cover(124,124,"center");
            $img->resize(124,124,function ($constraint) {
                $constraint->aspectRotio();
            })->save($destinationPath."/".$fileName);


            $brand->image = $fileName;
        }

        $brand->save();

        return redirect()->route("admin.brands")->with ("status", "Brand has been added successfully!");
    }
    public function edit_brand ($id) {
        $brand = Brand::find($id);
        return view ("admin.brand-edit", compact ('brand'));
    }
    public function update_brand (Request $request) {
        $request->validate(([
            "name" => "required",
            "slug" => "required|unique:brands,slug,".$request->id,
            "image" => "mimes:png,jpg,jpeg|max:2048"
        ]));

        $brand = Brand::find($request->id);

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile("image")) {
            if (File::exists(public_path("uploads/brands")."/".$brand->image)) {
                File::delete(public_path("uploads/brands")."/".$brand->image);
            }

            $image = $request->file("image");
            $file_extention = $request->file("image")->extension();
            $file_name = Carbon::now()->timestamp.".".$file_extention;

            $this->GenerateBrandThumbnailsImage ($image, $file_name);

            $brand->image = $file_name;
        }

        $brand->save();

        return redirect()->route("admin.brands")->with ("status", "Brand has been updated successfully!");
    }
    public function delete_brand ($id) {
        // $brand = Brand::find($request->id);
        $brand = Brand::find($id);
        $existFile = public_path("uploads/brands")."/".$brand->image;

        if (File::exists($existFile)) {
            File::delete($existFile);
        }

        // $brand->delete($request->id);
        $brand->delete($id);

        return redirect()->route("admin.brands")->with ("status", "Brand has been deleted successfully!");
    }
    public function GenerateBrandThumbnailsImage ($image, $imageName) {
        $destinationPath = public_path("uploads/brands");
        $img = Image::read($image->path ());
        $img->cover (124, 124, "top");
        $img->resize (124, 124, function ($constraint) {
            $constraint->aspectRotio ();
        })->save ($destinationPath."/".$imageName);
    }
}
