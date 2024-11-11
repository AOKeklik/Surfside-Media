@extends("layouts.admin")
@section("content")
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
                    <div class="text-tiny">Contacts</div>
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
                <a class="tf-button style-1 w208" href="add-category.html"><i
                        class="icon-plus"></i>Add new</a>
            </div>
            <div class="wg-table {{-- table-all-user --}}">
                <div class="table-responsive">
                    @if(Session::has('status'))
                        <p class="alert alert-success text-success">{{Session::get("status")}}</p>
                    @endif
                    @if(Session::has('error'))
                        <p class="alert alert-success text-success">{{Session::get("error")}}</p>
                    @endif
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                                <tr>
                                    <td>{{$contact->id}}</td>
                                    <td>{{$contact->name}}</td>
                                    <td>{{$contact->email}}</td>
                                    <td><a href="#" target="_blank">{{$contact->phone}}</a></td>
                                    <td><a href="#" target="_blank">{{$contact->comment}}</a></td>
                                    <td><a href="#" target="_blank">{{$contact->created_at}}</a></td>
                                    <td>
                                        <div class="list-icon-function">
                                            <form action="{{route('admin.contact.delete')}}" method="POST">
                                                @csrf
                                                @method("DELETE")
                                                <input type="hidden" name="contact_id" value="{{$contact->id}}">
                                                <div class="item text-danger delete" onclick="handlerClickDelete(event)">
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
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{$contacts->links("pagination::bootstrap-5")}}
            </div>
        </div>
    </div>
</div>
@endsection
@push("scripts")
<script>
    function handlerClickDelete (e) {
        const form = e.target.closest('form')
        
        swal ({
            title: "Are you sure?",
            text: "You want to remove this message!",
            type: "warning",
            buttons: ["no", "yes"],
            confirmButtonColor: "#dc3545"
        }).then(function (result) {
            if (result) {
                form.submit()
            }
        })
    }
</script>
@endpush