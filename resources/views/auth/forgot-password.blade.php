
@extends('layout.main')

@section('main')

<main class="main pages" style="margin-top:-30px">
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="index.html" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                <span></span> Pages <span></span> My Account
            </div>
        </div>
    </div>
    <div class="page-content pt-150 pb-150">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-8 col-md-12 m-auto">
                    <div class="row">
                        <div class="heading_s1">
                            <img class="border-radius-15" src="{{asset('Frontend/assets/imgs/page/reset_password.svg')}}" alt="" />
                            <h2 class="mb-15 mt-15">Forgot Password ?? Don't Worry.We have send link of your mail</h2>
                            <p class="mb-30">Please put the right email and notification link send through the email</p>
                        </div>
                        <div class="col-lg-8 col-md-8">
                            <div class="login_wrap widget-taber-content background-white">
                                <div class="padding_eight_all bg-white">
                                     <!-- Session Status -->
                                    <x-auth-session-status class="mb-4" :status="session('status')" style="color:green; font-weight:bold, font-size=13px" /> 
                                    <form method="post" action="{{ route('password.email') }}">
                                        @csrf
                                        <div class="form-group">
                                            <input id="email"  type="email" name="email" :value="old('email')" required autofocus placeholder="Enter your valid email *" />
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>
                                       
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-heading btn-block hover-up" name="login">Reset password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 pl-50">
                            <h6 class="mb-15">Password must:</h6>
                            <p>Be between 9 and 64 characters</p>
                            <p>Include at least tow of the following:</p>
                            <ol class="list-insider">
                                <li>An uppercase character</li>
                                <li>A lowercase character</li>
                                <li>A number</li>
                                <li>A special character</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection



