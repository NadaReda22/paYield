@extends('layout.admin')

@section('admin')


<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Brand</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Categories</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="/admin/category/add"  class="btn btn-primary">Add Category</a>
               
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">All Category Data</h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Category Name</th>
                            <th>Category Image</th>
                            <th>Product Front View</th>
                            <th>Action</th>
                            
                        </tr>
                    </thead>
                   
                    <tbody>
                         @foreach($categories as $count=>$category)
                        <tr>
                            <td>{{$count +1}}</td>
                            <td>{{$category->category_name}}</td>
                            <td>
                                <img src="{{asset($category->category_image)}}" style="width: 150px; height: 70px; border-radius:10px; border : 1px solid gainsboro" />
                            </td>

                            <td>

                          {{--    @if ($item->front_view == 1)
                                <a href="" class="btn btn-warning" title="Yes">Yes </a>
                            @else
                                <a href="" class="btn btn-dark" title="No"> No </a>
                            @endif

                            </td>
--}}  
                           


                            <td>
                                <a href="/admin/category/edit/{{$category->id}}" class="btn btn-info">Edit</a>
                                <a href="/admin/category/delete/{{$category->id}}" id="delete" class="btn btn-danger" style="margin-left: 10px">Delete</a>
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