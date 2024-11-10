<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class AdminSliderController extends Controller
{
    public function slides () {
        $slides = Slide::orderBy("created_at", "DESC")->paginate(12);
        return view("admin.slides", compact("slides"));
    }

    public function add_slide ()  {        
        return view("admin.slide-add");
    }

    public function store_slide (Request $request) {
        $request->validate([
            "tagline" => "required|string|min:3|max:15",
            "title" => "required|string|min:3|max:15",
            "subtitle" => "required|string|min:3|max:15",
            "link" => "required|url",
            "image" => "required|image|max:2048|mimes:jpg,jpeg,png",
            "status" => "required|boolean",
        ]);

        $slide = new Slide();

        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;

        if ($request->hasFile("image")) {
            $destination_directory = public_path("uploads/slides");
            $destination_thumbnail_directory = public_path("uploads/slides/thumbnails");

            $image_allowed = ["jpg", "jpeg", "png"];
            $image_extension = $request->file("image")->extension();
            $image_name = Carbon::now()->timestamp.".".$image_extension;
            $image_path = $request->file("image")->path();

            if (!File::isDirectory($destination_directory))
                File::makeDirectory($destination_directory, 0755, true);

            if (!File::isDirectory($destination_thumbnail_directory))
                File::makeDirectory($destination_thumbnail_directory, 0755, true);

            
            if (in_array($image_extension, $image_allowed) && !File::isFile($destination_directory."/".$image_name)) {

                $image = Image::read($image_path);

                $image->cover(400,690,"top");
                $image->resize(400,690,function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destination_directory."/".$image_name);
                
                $image->cover(104,104,"top");
                $image->resize(104,104,function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destination_thumbnail_directory."/".$image_name);
            }

            $slide->image = $image_name;
        }

        $slide->save();

        return redirect()->route("admin.slides")->with("status", "Slide has been added successfully!");
    }

    public function edit_slide ($slide_id) {
        $slide = Slide::find($slide_id);

        if (!$slide)
            return redirect()->back()->with("error", "Slide not found!");
    
        return view ("admin.slide-edit", compact("slide"));
    }

    public function update_slide (Request $request) {
        $request->validate([
            "tagline" => "required|string|min:3|max:15",
            "title" => "required|string|min:3|max:15",
            "subtitle" => "required|string|min:3|max:15",
            "link" => "required|string|url",
            "image" => "image|mimes:jpg,jpeg,png|max:2048",
            "status" => "required|boolean|in:0,1",
        ]);

        $slide = Slide::find($request->slide_id);

        if (!$slide)
            return redirect()->back()->with("error", "Slide not found!");

        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;
        
        if ($request->hasFile("image")) {

            $destination_directory = public_path("uploads/slides");
            $destination_thumbnail_directory = public_path("uploads/slides/thumbnails");

            $image_allowed = ["jpg","jpeg","png"];
            $image_extension = $request->file("image")->getClientOriginalExtension();
            $image_old_name = $slide->image;
            $image_path = $request->file("image")->path();
            $image_new_name = Carbon::now()->timestamp.".".$image_extension;

            if (File::isFile($destination_directory."/".$image_old_name))
                File::delete($destination_directory."/".$image_old_name);

            if (File::isFile($destination_thumbnail_directory."/".$image_old_name))
                File::delete($destination_thumbnail_directory."/".$image_old_name);

            if (in_array($image_extension, $image_allowed)) {
                $image = Image::read($image_path);

                $image->cover(400,690,"top");
                $image->resize(400,690,function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destination_directory."/".$image_new_name);

                $image->cover(104,104,"top");
                $image->resize(104,104,function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destination_thumbnail_directory."/".$image_new_name);

                $slide->image = $image_new_name;
            }
        }

        $slide->save();

        return redirect()->route("admin.slides")->with("status", "Slide has been uploaded successfully!");
    }

    public function delete_slide (Request $request) {

        $slide = Slide::find($request->slide_id);
        $image_name = $slide->image;
        $destination_directory = public_path("uploads/slides");
        $destination_thumbnail_directory = public_path("uploads/slides/thumbnails");

        if (!$slide)
            return redirect()->back()->with("error", "Slide not found.");

        if (File::isDirectory($destination_directory))
            if (File::isFile($destination_directory."/".$image_name))
                File::delete($destination_directory."/".$image_name);

        if (File::isDirectory($destination_thumbnail_directory))
            if (File::isFile($destination_thumbnail_directory."/".$image_name))
                File::delete($destination_thumbnail_directory."/".$image_name);

        $slide->delete();
        
        return redirect()->back()->with("status", "Slide has been deleted successfully!");
    }
}
