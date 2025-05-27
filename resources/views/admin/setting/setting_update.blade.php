@php
    // Get the SiteSetting with ID 1 or create a new one if it doesn't exist to can make updates on existing record
    $setting = \App\Models\SiteSetting::firstOrNew(['id' => 1]);
@endphp


@extends('layout.admin')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Site Setting</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Site Setting</li>
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

                                <form method="post" action="{{ route('site.setting.update') }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="id" value="1">

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Support Phone</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" class="form-control" name="support_phone"
                                                value="{{ old('support_phone',$setting->support_phone) }}" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Phone One</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="phone_one" class="form-control"
                                                value="{{ old('phone_one',$setting->phone_one) }}" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Email</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email',$setting->email) }}" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Company Address </h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="company_address" class="form-control"
                                                value="{{ old('company_address',$setting->company_address) }}" />
                                        </div>
                                    </div>


                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Facebook</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="facebook" class="form-control"
                                                value="{{ old('facebook',$setting->facebook) }}" />
                                        </div>
                                    </div>


                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Twitter</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="twitter" class="form-control"
                                                value="{{ old('twitter',$setting->twitter) }}" />
                                        </div>
                                    </div>



                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Youtube</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="youtube" class="form-control"
                                                value="{{ old('youtube',$setting->youtube) }}" />
                                        </div>
                                    </div>


                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">CopyRight</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="copyright" class="form-control"
                                                value="{{ old('copyright',$setting->copyright) }}" />
                                        </div>
                                    </div>



                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Logo</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="file" name="logo" class="form-control" id="image" />
                                        </div>
                                    </div>



                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0"> </h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <img id="showImage" src="{{ old('logo',asset($setting->logo)) }}" alt="Admin"
                                                style="width:100px; height: 100px;">
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



    <script type="text/javascript">
        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
@endsection
