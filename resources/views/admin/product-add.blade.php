@extends("layouts.admin")
@section("content")
<div class="main-content-inner">
    <!-- main-content-wrap -->
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add Product</h3>
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
                    <a href="{{route('admin.products')}}">
                        <div class="text-tiny">Products</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Add product</div>
                </li>
            </ul>
        </div>
        <!-- form-add-product -->
        <form class="tf-section-2 form-add-product" enctype="multipart/form-data"  method="POST" action="{{ route('admin.product.store') }}">
            @csrf
            @method("POST")
            {{-- <input type="hidden" name="_token" value="8LNRTO4LPXHvbK2vgRcXqMeLgqtqNGjzWSNru7Xx" autocomplete="off"> --}}
            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Product name <span class="tf-color-1">*</span> </div>
                    <input onchange="handlerChangeName (event)" class="mb-10" type="text" placeholder="Enter product name" name="name" tabindex="0" value="{{old('name')}}" aria-required="true" required="">
                    <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                </fieldset>
                @error("name") <p class="alert alert-danger text-center">{{$message}}</p> @enderror

                <fieldset class="name">
                    <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" tabindex="0" value="{{old('slug')}}" aria-required="true" required="">
                    <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                </fieldset>
                @error("slug") <p class="alert alert-danger text-center">{{$message}}</p> @enderror

                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Category <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select class="" name="category_id">
                                <option>Choose category</option>
                                @foreach ($categories as $cat) 
                                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    @error("category_id") <p class="alert alert-danger text-center">{{$message}}</p> @enderror

                    <fieldset class="brand">
                        <div class="body-title mb-10">Brand <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select class="" name="brand_id">
                                <option>Choose Brand</option>
                                @foreach ($brands as $bra) 
                                    <option value="{{$bra->id}}">{{$bra->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    @error("brand_id") <p class="alert alert-danger text-center">{{$message}}</p> @enderror
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" tabindex="0" aria-required="true" required="">{{old('short_description')}}</textarea>
                    <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                </fieldset>
                @error("short_description") <p class="alert alert-danger text-center">{{$message}}</p> @enderror

                <fieldset class="description">
                    <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10" name="description" placeholder="Description" tabindex="0" aria-required="true" required="">{{old('description')}}</textarea>
                    <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                </fieldset>
                @error("description") <p class="alert alert-danger text-center">{{$message}}</p> @enderror
            </div>
            <div class="wg-box">
                <fieldset>
                    <div class="body-title">Upload images <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="display:none">
                            <img src="../../../localhost_8000/images/upload/upload-1.png" class="effect8" alt="">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                <input onchange="handlerChangeFile(event)" type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error("image") <p class="alert alert-danger text-center">{{$message}}</p> @enderror

                <fieldset>
                    <div class="body-title mb-10">Upload Gallery Images</div>
                    <div class="upload-image mb-16">
                        <div class="item" id="imgspreview" style="display:none">
                            
                        </div>
                        <div id="galUpload" class="item up-load">
                            <label class="uploadfile" for="gFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="text-tiny">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                <input onchange="handlerChangeFile(event)" type="file" id="gFile" name="images[]" accept="image/*"  multiple="">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error("images") <p class="alert alert-danger text-center">{{$message}}</p> @enderror

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter regular price"  name="regular_price" tabindex="0" value="{{old('regular_price')}}" aria-required="true" required="">
                    </fieldset>
                    @error("regular_price") <p class="alert alert-danger text-center">{{$message}}</p> @enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter sale price" name="sale_price" tabindex="0" value="{{old('sale_price')}}" aria-required="true" required="">
                    </fieldset>
                    @error("sale_price") <p class="alert alert-danger text-center">{{$message}}</p> @enderror
                </div>


                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU"  tabindex="0" value="{{old('SKU')}}" aria-required="true" required="">
                    </fieldset>
                    @error("SKU") <p class="alert alert-danger text-center">{{$message}}</p> @enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span> </div>
                        <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity" tabindex="0" value="{{old('quantity')}}" aria-required="true" required="">
                    </fieldset>
                    @error("quantity") <p class="alert alert-danger text-center">{{$message}}</p> @enderror
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Stock</div>
                        <div class="select mb-10">
                            <select class="" name="stock_status">
                                <option value="instock">InStock</option>
                                <option value="outofstock">Out of Stock</option>
                            </select>
                        </div>
                    </fieldset>
                    @error("stock_status") <p class="alert alert-danger text-center">{{$message}}</p> @enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">Featured</div>
                        <div class="select mb-10">
                            <select class="" name="featured">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </fieldset>
                    @error("featured") <p class="alert alert-danger text-center">{{$message}}</p> @enderror
                </div>
                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Add product</button>
                </div>
            </div>
        </form>
        <!-- /form-add-product -->
    </div>
    <!-- /main-content-wrap -->
</div>
@endsection
@push ("scripts")
<script>
    function handlerChangeFile (e) {
        const parent = e.target.closest("fieldset")
        const node = e.target
        const image = parent.querySelector ("img")
        const imgspreview = parent.querySelector("#imgspreview")
        const files = e.target.files


        if (files.length === 0) return

        if (node.id.includes("myFile")) {
            image.setAttribute("src", URL.createObjectURL (files[0]))
            image.parentElement.removeAttribute("style")
            return
        }
        
        imgspreview.removeAttribute("style")
        imgspreview.innerHTML = ""
        Array.from(files).forEach(file => {
            const img = document.createElement("img")
            img.setAttribute("src", URL.createObjectURL (file))
            imgspreview.appendChild (img)  
        })
    }
    function handlerChangeName (e) {
        const parent = e.target.closest("form") 
        const name = e.target.value
        const slug = parent.querySelector("input[name='slug']")
        const formatedName = name
            .trim()
            .toLowerCase()
            .replace(/[^\w ]/g, "")
            .replace(/\s+/g, "-")
        
        slug.value= formatedName
    }
</script>
@endpush



