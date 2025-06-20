@extends('layout.admin')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All User Data </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All User Data</li>
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
                            @foreach ($users as $count => $user)
                                <tr>
                                    <td>{{$count +1 }}</td>
                                    <td> <img
                                            src="{{!empty($user->photo) ? asset($user->photo) : url('AdminBackend/no_image.jpg')}}"
                                            alt="Admin" class="rounded-circle p-1 bg-primary" width="60"
                                            height="60"></td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->phone}}</td>

                                    <td>
                                        @if ($user->isOnline())
                                            <span class="badge badge-pill bg-success">Active Now </span>
                                        @else
                                            <span class="badge badge-pill bg-danger">
                                              {{ Carbon\Carbon::parse($user->lastseen)->diffForHumans(); }}
                                               </span>
                                        @endif

                                    </td>

                                    <td>
                                        <a href="/admin/user-manage/user/edit/{{$user->id}}" class="btn btn-info">Edit</a>
                                        <a href="/admin/user-manage/user/delete/{{$user->id}}" class="btn btn-danger" id="delete">Delete</a>

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
