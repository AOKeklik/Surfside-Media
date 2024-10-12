@extends("layouts.admin")
@section ("content")
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Categories</h3>
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
                    <div class="text-tiny">Categories</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="" name="name"
                                tabindex="2" value="" aria-required="true" required="">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{route('admin.category.add')}}">
                    <i class="icon-plus"></i>
                    Add new
                </a>
            </div>
            <div class="wg-table table-all-user">
                @if (Session::has ("status"))
                    <p class="alert alert-success">{{Session::get("status")}}</p>
                @endif
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Products</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{$category->id}}</td>
                                <td class="pname">
                                    @if ($category->image)
                                        <div class="image">
                                            <img src="{{asset('uploads/categories')}}/{{$category->image}}" alt="" class="image">
                                        </div>
                                    @else
                                        <span style="color: tomato">No image!</span>
                                    @endif
                                    <div class="name">
                                        <a href="#" class="body-title-2">{{$category->name}}</a>
                                    </div>
                                </td>
                                <td>{{$category->slug}}</td>
                                <td><a href="#" target="_blank">2</a></td>
                                <td>
                                    <div class="list-icon-function">
                                        <a href="{{route('admin.category.edit', ["id"=>$category->id])}}">
                                            <div class="item edit">
                                                <i class="icon-edit-3"></i>
                                            </div>
                                        </a>
                                        <form action="{{route('admin.category.delete', ["id" => $category->id])}}" method="POST">
                                            @csrf
                                            @method("delete")
                                            <input type="hidden" name="id", value="{{$category->id}}">
                                            <div class="item text-danger delete" onclick="handlerClickDelete(event, '{{$category->id}}')">
                                                <i class="icon-trash-2"></i>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

            </div>
        </div>
    </div>
</div>
@endsection
@push  ("scripts")
<script>
    function handlerClickDelete (e, name) {
        const form = event.target.closest('form')

        if (confirm ("Are you sure to delete category: ", name)) {
            form.submit()
        }
    }
</script>
@endpush