@extends('layout.admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">SubCategory</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit SubCategory</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="/admin/subcategory/all" class="btn btn-primary">All SubCategory</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="myForm" method="post" action="/admin/subcategory/update/{{$category->id}}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{$category->id}}">

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Category Name</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <select name="parent_id" class="form-select mb-3" aria-label="Default select example">
                                            <option selected="">Open this select menu</option>
                                            @foreach ($categories as $category)
                                                <option value="{{$category->id}}" {{$category->id == old('parent_id', $category->parent_id) ? 'selected' : ''}}>{{$category->category_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">SubCategory Name </h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <input type="text" name="subcategory_name" class="form-control" value="{{ old('subcategory_name', $category->category_name) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Photo</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" class="form-control" name="subcategory_image" id="image" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <img id="showImage" src="{{ old('subcategory_image', asset($category->category_image)) }}" alt="Admin" width="110" style="border-radius: 10px">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="Update SubCategory" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#myForm').validate({
            rules: {
                subcategory_name: {
                    required: true,
                },
            },
            messages: {
                subcategory_name: {
                    required: 'Please enter the SubCategory Name',
                },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
        });
    });
</script>

@endsection
