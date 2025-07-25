@extends('layout.vendor')
@section('vendor')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Product</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Product  <span class="badge rounded-pill bg-danger"> {{ count($products) }} </span> </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="/vendor/product/add" class="btn btn-primary">Add Product</a>
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
                                <th>Product Name </th>
                                <th>Price </th>
                                <th>QTY </th>
                                <th>Discount </th>
                                <th>Status </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $count => $product)
                                <tr>
                                    <td> {{ $count + 1 }} </td>
                                    <td> <img src="{{ asset($product->product_thumbnail) }}" style="width: 80px; height:80px; border-radius : 10px; border : 1px solid gainsboro">
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($product->product_name, 90) }} </td>
                                    <td>{{ $product->selling_price }}</td>
                                    <td>{{ $product->product_quantity }}</td>


                                    <td>
                                        @if ($product->discount_price == null)
                                            <span class="badge rounded-pill bg-info">No Discount</span>
                                        @else
                                            @php
                                                $amount = $product->selling_price - $product->discount_price;
                                                $discount = ($product->discount_price / $product->selling_price) * 100;
                                            @endphp
                                            <span class="badge rounded-pill bg-danger"> {{ round($discount) }}%</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($product->status == 1)
                                            <span class="badge rounded-pill bg-success">Active</span>
                                        @else
                                            <span class="badge rounded-pill bg-danger">InActive</span>
                                        @endif
                                    </td>

                                    <td>

                                       
                                        <a href="/vendor/product/edit/{{ $product->id }}" class="btn btn-info" title="Edit Data"> <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="/vendor/product/delete/{{ $product->id }}" class="btn btn-danger" id="delete" title="Delete Data"><i
                                                class="fa fa-trash"></i></a>
    
                                        <a href="/product/details/{{$product->id}}" class="btn btn-warning" title="Details Page"> <i
                                                class="fa fa-eye"></i> </a>
    
                                        @if ($product->status == 1)
                                            <a href="/vendor/product/inactive/{{ $product->id }}" class="btn btn-primary" title="Inactive"> <i class="fa-solid fa-thumbs-down"></i> </a>
                                        @else
                                            <a href="/vendor/product/active/{{ $product->id }}" class="btn btn-primary" title="Active"> <i
                                                    class="fa-solid fa-thumbs-up"></i> </a>
                                        @endif

                                    </td>
                                   
                                </tr>
                            @endforeach


                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Image </th>
                                <th>Product Name </th>
                                <th>Price </th>
                                <th>QTY </th>
                                <th>Discount </th>
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
