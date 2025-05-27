@extends('layout.admin')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Edit  Vendor </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit  Vendor</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">

            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container">
            <div class="main-body">
                <div class="row">

                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">

                                <form method="post" action="{{ url('admin/user-manage/vendor/update/'.$vendor->id) }}"  enctype="multipart/form-data">
                                   
                                @csrf

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Vendor Name</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="username" class="form-control"
                                                value="{{ $vendor->username }}" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Shop Name</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="shop_name" class="form-control"
                                            value="{{ old('shop_name', $vendor->shop_name ?? '') }}"
                                            />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Full Name</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $vendor->name }}" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Email</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="email" name="email" class="form-control"
                                                value="{{ $vendor->email }}" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Phone </h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ $vendor->phone }}" />
                                        </div>
                                    </div>


                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Address</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="address" class="form-control"
                                                value="{{ $vendor->address }}" />
                                        </div>
                                    </div>

                                    
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Vendor Info</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                        <textarea id="editor1" name="vendor_info" style="height: 600px;">
                                        {{old('vendor_info',$vendor->vendor_info)}}
                                        </textarea>
                                        </div>
                                    </div>

                
                                    <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Photo</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" class="form-control" name="photo" id="image" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                    <img id="showImage" src="{{ $vendor->photo ? asset($vendor->photo) : url('AdminBackend/no_image.jpg') }}" alt="User" width="110" style="border-radius: 10px">
                                    </div>
                                </div>

                                   <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Vendor Join Date</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" name="vendor_join_date" class="form-control"
                                    value="{{ old('vendor_join_date', $vendor->vendor_join_date ? \Carbon\Carbon::parse($vendor->vendor_join_date)->format('Y') : '') }}"/>
                                    </div>
                            </div>




                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Asign Roles </h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <select name="roles" class="form-select mb-3"
                                                aria-label="Default select example">

                                                <option selected="">Open this select menu</option>
                                             {{-- @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ $vendor->hasRole($role->name) ? 'selected' : '' }}>
                                                        {{ $role->name }}</option>
                                                @endforeach--}}  
                                            </select>
                                        </div>
                                    </div>




                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="submit" class="btn btn-primary px-4" value="Save Changes" />
                                        </div>
                                    </div>
                            </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
