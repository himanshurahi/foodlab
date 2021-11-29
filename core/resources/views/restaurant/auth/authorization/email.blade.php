@extends('restaurant.layouts.master')

@section('content')
    <div class="page-wrapper default-version">
        <div class="form-area bg_img" data-background="{{asset('assets/admin/images/1.jpg')}}">
            <div class="form-wrapper">
                <h4 class="logo-text mb-15"><strong>@lang('Please Verify Your Email to Get Access')</strong></h4>
                <p>@lang('Email : ') <b>{{auth()->guard('restaurant')->user()->email}}</b></p>

                <form action="{{route('restaurant.verify.email')}}" method="POST" class="cmn-form mt-30">
                    @csrf
                    <div class="form-group">
                        <label>@lang('Verification Code')</label>
                        <input type="text" name="email_verified_code" id="code" class="form-control">
                    </div>
                    <div class="form-group d-flex justify-content-between align-items-center">
                        <p>@lang('Please check including your Junk/Spam Folder. if not found, you can') <a href="{{route('restaurant.send.verify.code')}}?type=email" class="forget-pass"> @lang('Resend code')</a></p>
                        @if ($errors->has('resend'))
                            <br/>
                            <small class="text-danger">{{ $errors->first('resend') }}</small>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn mt-25 b-radius--capsule">@lang('Verify Code') <i class="las la-sign-in-alt"></i></button>
                    </div>
                </form>
            </div>
        </div><!-- login-area end -->
    </div>
@endsection

@push('script')
<script>
    (function($){
        "use strict";
        $('#code').on('input change', function () {
          var xx = document.getElementById('code').value;
            $(this).val(function (index, value) {
                value = value.substr(0,7);
                return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
            });
      });
    })(jQuery)
</script>
@endpush
