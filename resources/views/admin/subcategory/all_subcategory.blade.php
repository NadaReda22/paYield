@extends('layout.admin')

@section('admin')


<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">SubCategory</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Subcategory</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="/admin/subcategory/add"  class="btn btn-primary">Add SubCategory</a>
               
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">All SubCategory Data</h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Category Name</th>
                            <th>SubCategory Name</th>
                            <th>Image</th>
                            <th>Action</th>
                            
                        </tr>
                    </thead>
                   
                    <tbody>
                        @foreach($categories as $count=> $category) 
                         @if($category->subCategories->isNotEmpty()) 
                         @foreach($category->subCategories as $subCategory )
                        <tr>     
                            <td>{{$count +1}}</td>
                           <td> {{$category->category_name ?? 'N/A'}}</td>
                            <td>{{ $subCategory->category_name ?? 'N/A' }}</td> 
                            <td>
                                <img src="{{asset($subCategory->category_image)}}" style="width: 150px; height: 70px; border-radius:10px; border : 1px solid gainsboro" />
                            </td>
                        
                            <td>
                               <a href="/admin/subcategory/edit/{{$subCategory->id}}" class="btn btn-info">Edit</a>
                                <a href="/admin/subcategory/delete/{{$subCategory->id}}" id="delete" class="btn btn-danger" style="margin-left: 10px">Delete</a>
                            </td>
                        </tr> 
                         
                         @endforeach
                         @endif
                        @endforeach
                    </tbody>
                   
                </table>
            </div>
        </div>
    </div>
    
</div>
<!--end page wrapper -->


@endsection