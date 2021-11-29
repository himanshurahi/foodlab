@extends($activeTemplate.'layouts.frontend')

@section('content')

    <section class="account-section ptb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-6 col-md-8">
                    <div class="account-form-area">
                        <div class="account-header">
                            <h3 class="title">@lang('Login')</h3>
                        </div>
                        <div class="account-btn-area">
                            <div class="account-btn"><i class="las la-user"></i></div>
                        </div>
                        <form class="account-form" method="POST" action="{{ route('user.login')}}" onsubmit="return submitUserForm();">
                            @csrf
                            <div class="row ml-b-20">
                                <div class="col-lg-12 form-group">
                                    <label>@lang('Username') <span class="text-danger">*</span></label>
                                    <input type="text" name="username" value="{{ old('username') }}" class="form-control form--control" required>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label>@lang('Password') <span class="text-danger">*</span></label>
                                    <input id="password" type="password" class="form-control form--control" name="password" required>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <div class="forgot-item">
                                        <label><a href="{{route('user.password.request')}}" class="text--base">@lang('Forgot Password')?</a></label>
                                    </div>
                                </div>
                                <div class="col-lg-12 form-group google-captcha">
                                    @php echo loadReCaptcha() @endphp
                                </div>
                                @include($activeTemplate.'partials.custom_captcha')

                                <div class="col-lg-12 form-group text-center">
                                    <button type="submit" class="submit-btn">@lang('Login Now')</button>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <div class="account-item mt-10">
                                        <label>@lang('Don\'t Have An Account')? <a href="{{ route('user.register') }}" class="text--base">@lang('Register Now')</a></label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
