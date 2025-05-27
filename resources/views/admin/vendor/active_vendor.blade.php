@extends('layout.admin')
@section('admin')

<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">All Active Vendor</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Active Vendor</li>
							</ol>
						</nav>
					</div>
					<div class="ms-auto">
						<div class="btn-group">

						</div>
					</div>
				</div>
				<!--end breadcrumb-->

				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered" style="width:100%">
								<thead>
			<tr>
				<th>Sl</th>
				<th>Shop Name </th>
				<th>Vendor Username </th>
				<th>Join Date  </th>
				<th>Vendor Email </th>
				<th>Status </th>
				<th>Action</th> 
			</tr>
		</thead>
		<tbody>
	@foreach($ActiveVendors as $count => $ActiveVendor)		
			<tr>
				<td>{{$count +1}}</td>
				<td>{{$ActiveVendor->shop_name}}</td>
				<td>{{$ActiveVendor->user_name}}</td>
				<td>{{$ActiveVendor->vendor_join_date}}</td>
				<td>{{$ActiveVendor->email}}</td>
				<td> <span class="btn btn-success"></span>{{ $ActiveVendor->status}}</td>

				<td>
<a href="/admin/vendor/active/details/{{$ActiveVendor->id}}" class="btn btn-info">Vendor Details</a>


				</td> 
			</tr>
			@endforeach


		</tbody>
		<tfoot>
			<tr>
				<th>Sl</th>
				<th>Shop Name </th>
				<th>Vendor Username </th>
				<th>Join Date  </th>
				<th>Vendor Email </th>
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