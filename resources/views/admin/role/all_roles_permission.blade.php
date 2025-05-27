@extends('layout.admin')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Roles & Permission</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Roles Permission</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="/admin/role/permissions/add" class="btn btn-primary">Add Roles</a>
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
                                <th>Roles Name </th>
                                <th>Permission </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $count => $role)
                                <tr>
                                    <td> {{ $count + 1 }} </td>
                                    <td>{{ $role->name }}</td>

                                    <?php
                                     
                                    $role=Spatie\Permission\Models\Role::findOrFail($role->id);
                                    $r_permission=$role->permissions()->pluck('name');
                                    ?>

                                    
                                    <td>
                                       @foreach($r_permission as $permission)
                                          <span class="badge bg-danger">{{$permission}}</span>
                                       @endforeach

                                    </td>

                                    <td>
                                        <a href="/admin/role/permissions/edit/{{$role->id}}" class="btn btn-info">Edit</a>
                                        <a href="/admin/role/permissions/delete/{{$role->id}}" class="btn btn-danger"
                                            id="delete">Delete</a>

                                    </td>
                                </tr>
                            @endforeach


                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Role </th>
                                <th>Permission </th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
