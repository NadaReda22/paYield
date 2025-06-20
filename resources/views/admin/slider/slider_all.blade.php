@extends('layout.admin')

@section('admin')
    <!--start page wrapper -->
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Slider</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Slider</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="/admin/slider/add" class="btn btn-primary">Add Slider</a>

                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <h6 class="mb-0 text-uppercase">All Slider Data</h6>
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Slider Url </th>
                                <th>Short Title </th>
                                <th>Slider Image </th>
                                <th>Action</th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($sliders as $count => $slider)
                                <tr>
                                    <td>{{$count +1}}</td>
                                    <td>{{$slider->slider_url}}</td>
                                    <td>{{$slider->slider_title}}</td>
                                    <td> <img src="{{!empty($slider->image) ? asset($slider->image) : url('AdminBackend/no_image.jpg')}}" style="width: 70px; height: 70px; border-radius:10px; border : 1px solid gainsboro">
                                    </td>

                                    <td>
                                        <a href="/admin/slider/edit/{{$slider->id}}" class="btn btn-info">Edit</a>
                                        <a href="/admin/slider/delete/{{$slider->id}}" class="btn btn-danger" id="delete">Delete</a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
    <!--end page wrapper -->
@endsection
