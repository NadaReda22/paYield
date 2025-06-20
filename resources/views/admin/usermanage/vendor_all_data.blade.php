@extends('layout.admin')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Vendor Data </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Vendor Data</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Image </th>
                                <th>Name </th>
                                <th>Email </th>
                                <th>Phone </th>
                                <th>Status </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vendors as $count => $vendor)
                                <tr>
                                    <td>{{$count +1}}</td>
                                    <td> <img
                                            src="{{!empty($vendor->photo) ? asset($vendor->photo) : url('AdminBackend/no_image.jpg')}}"
                                            alt="Admin" class="rounded-circle p-1 bg-primary" width="60"
                                            height="60"></td>
                                    <td>{{$vendor->name}}</td>
                                    <td>{{$vendor->email}}</td>

                                    <td><span class="badge badge-pill bg-success"></span>{{$vendor->phone}}</td>
                                    <td>{{$vendor->status}}</td>

                                    <td>
                                        <a href="/admin/user-manage/vendor/edit/{{$vendor->id}}" class="btn btn-info">Edit</a>
                                        <a href="/admin/user-manage/vendor/delete/{{$vendor->id}}" class="btn btn-danger"
                                            id="delete">Delete</a>

                                    </td>
                                </tr>
                            @endforeach


                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Image </th>
                                <th>Name </th>
                                <th>Email </th>
                                <th>Phone </th>
                                <th>Status </th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>



    </div>
@endsection
