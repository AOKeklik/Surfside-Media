@extends("layouts.admin")
@section("content")
<div class="main-content-inner">
    <!-- main-content-wrap -->
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Category infomation</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route ('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{route('admin.categories')}}">
                        <div class="text-tiny">Categories</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">New Category</div>
                </li>
            </ul>
        </div>
        <!-- new-category -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{route('admin.category.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method("put")
                <input type="hidden" name="id" value="{{$category->id}}">
                <fieldset class="name">
                    <div class="body-title">Category Name <span class="tf-color-1">*</span> </div>
                    <input class="flex-grow" onchange="handlerOnChange(event)" type="text" placeholder="Category name" name="name" tabindex="0" value="{{$category->name}}" aria-required="true" required="">
                </fieldset>
                @error('name') <div class="alert alert-danger text-center">{{$message}}</div>  @enderror
                <fieldset class="name">
                    <div class="body-title">Category Slug <span class="tf-color-1">*</span>
                    </div>
                    <input class="flex-grow" type="text" placeholder="Category Slug" name="slug"  tabindex="0" value="{{$category->slug}}" aria-required="true" required="">
                </fieldset>
                @error('slug') <div class="alert alert-danger text-center">{{$message}}</div>  @enderror
                <fieldset>
                    <div class="body-title">Upload images <span class="tf-color-1">*</span>
                    </div>
                    <div class="upload-image flex-grow">
                        @if ($category->image) 
                            <div class="item" id="imgpreview">
                                <img src="{{asset('uploads/categories')}}/{{$category->image}}" class="effect8" alt="">
                            </div>
                        @else
                            <div class="item" id="imgpreview" style="display:none">
                                <img src="upload-1.html" class="effect8" alt="">
                            </div>
                        @endif
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                <input onchange="handlerOnChangeUpdateImage(event)" type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image') <div class="alert alert-danger text-center">{{$message}}</div>  @enderror
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push ("scripts")
    <script>
        function handlerOnChange(e) {
            const val = e.target.value
            const slug = document.querySelector("input[name='slug']")
            const formatedVal = val.toLowerCase().replace(/[^\w ]/g, "").replace(/\s+/g, "-")
            slug.value = formatedVal
        }
        function handlerOnChangeUpdateImage (e) {
            const parent = e.target.closest(".upload-image")
            const img = parent.querySelector("img")
            const fileElement = e.target
            const file = e.target.files[0]
            const fileName = file.name

            if (fileName) {
                img.setAttribute("src", URL.createObjectURL(file))
                img.parentElement.style = ""
            }

        }
    </script>
@endpush