@extends ("layouts.admin")
@section ("content")
<div class="main-content-inner">
    <!-- main-content-wrap -->
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Slide</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{route('admin.slides')}}">
                        <div class="text-tiny">Slider</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">New Slide</div>
                </li>
            </ul>
        </div>
        <!-- new-category -->
        <div class="wg-box">
            <form action="{{route('admin.slide.update')}}" method="POST" class="form-new-product form-style-1" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <input type="hidden" name="slide_id" value="{{$slide->id}}">
                <fieldset class="name">
                    <div class="body-title">Tagline <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Tagline" name="tagline" value="{{$slide->tagline}}" tabindex="0" aria-required="true" required="">
                </fieldset>
                @error("tagline") <p class="alert alert-danger text-danger">{{$message}}</p> @enderror
                <fieldset class="name">
                    <div class="body-title">Title <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Title" name="title" tabindex="0" value="{{$slide->title}}" aria-required="true" required="">
                </fieldset>
                @error("title") <p class="alert alert-danger text-danger">{{$message}}</p> @enderror
                <fieldset class="name">
                    <div class="body-title">Subtitle <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="subtitle" name="subtitle" tabindex="0" value="{{$slide->subtitle}}" aria-required="true" required="">
                </fieldset>
                @error("subtitle") <p class="alert alert-danger text-danger">{{$message}}</p> @enderror
                <fieldset class="name">
                    <div class="body-title">Link <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Link" name="link" tabindex="0" value="{{$slide->link}}" aria-required="true" required="">
                </fieldset>
                @error("link") <p class="alert alert-danger text-danger">{{$message}}</p> @enderror
                <fieldset>
                    <div class="body-title">Upload images <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        @if ($slide->image)
                            <div class="item" id="imgpreview">
                                <img src="{{asset('uploads/slides')}}/{{$slide->image}}" class="effect8" alt="">
                            </div>
                        @else
                            <div class="item" id="imgpreview" style="display:none">
                                <img src="../../../localhost_8000/images/upload/upload-1.png" class="effect8" alt="">
                            </div>
                        @endif
                        <div class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                <input onchange="handlerChangeImage(event)" type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error("image") <p class="alert alert-danger text-danger">{{$message}}</p> @enderror
                <fieldset class="category">
                    <div class="body-title">Select category icon</div>
                    <div class="select flex-grow">
                        <select class="" name="status">
                            <option>Select icon</option>
                            <option value="1" @if($slide->status == "1") selected @endif>Visible</option>
                            <option value="0" @if($slide->status == "0") selected @endif>Hidden</option>
                        </select>
                    </div>
                </fieldset>
                @error("status") <p class="alert alert-danger text-danger">{{$message}}</p> @enderror
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save</button>
                </div>
            </form>
        </div>
        <!-- /new-category -->
    </div>
    <!-- /main-content-wrap -->
</div>
@endsection
@push("scripts")
<script>
    function handlerChangeImage (e) {
        const parent = e.target.closest(".upload-image.flex-grow")
        const img = parent.querySelector("img")
        const files = e.target.files

        if (files.length === 0) return

        const path = URL.createObjectURL(files[0])
        
        img.setAttribute("src", path)
        img.parentElement.removeAttribute("style")
    }
</script>
@endpush