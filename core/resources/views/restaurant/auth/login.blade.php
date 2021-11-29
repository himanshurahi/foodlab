@extends('restaurant.layouts.master')
@section('content')
    <div class="page-wrapper default-version">
        <div class="form-area bg_img" data-background="{{asset('assets/admin/images/1.jpg')}}">
            <div class="container p-0">
                <div class="row justify-content-center no-gutters">
                    <div class="col-lg-6">
                        <div class="form-wrapper w-100">
                            <h4 class="logo-text mb-15">@lang('Welcome to') <strong>{{__($general->sitename)}}</strong></h4>
                            <p>{{__($pageTitle)}}</p>
                            <form action="{{ route('restaurant.login') }}" method="POST" class="cmn-form mt-30 form-row" onsubmit="return submitUserForm();">
                                @csrf
                                <div class="form-group col-md-12">
                                    <label for="email">@lang('Username')</label>
                                    <input type="text" name="username" class="form-control b-radius--capsule" id="username" value="{{ old('username') }}" placeholder="@lang('Enter your username')">
                                    <i class="las la-user input-icon"></i>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="pass">@lang('Password')</label>
                                    <input type="password" name="password" class="form-control b-radius--capsule" id="pass" placeholder="@lang('Enter your password')">
                                    <i class="las la-lock input-icon"></i>
                                </div>
                                <div class="form-group col-md-12 google-captcha">
                                    @php echo loadReCaptcha() @endphp
                                </div>
                                @include('restaurant.partials.custom_captcha')

                                <div class="form-group col-md-12 d-flex justify-content-between align-items-center">
                                    <a href="{{ route('restaurant.password.reset') }}" class="text-muted text--small"><i class="las la-lock"></i>@lang('Forgot password?')</a>
                                    <a href="{{route('restaurant.register')}}" class="text-muted text--small"><i class="las la-sign-in-alt"></i> @lang('Register Now?')</a>
                                </div>
                                <div class="form-group col-md-12">
                                    <button type="submit" class="submit-btn mt-25 b-radius--capsule">@lang('Login') <i class="las la-sign-in-alt"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- login-area end -->
    </div>
@endsection


@push('script')
    <script>
        "use strict";
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
    </script>
@endpush

