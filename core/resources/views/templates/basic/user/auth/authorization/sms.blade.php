@extends($activeTemplate .'layouts.frontend')
@section('content')

    <section class="account-section ptb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-6 col-md-8">
                    <div class="account-form-area">
                        <div class="account-header">
                            <h3 class="title">{{__($pageTitle)}}</h3>
                        </div>
                        <div class="account-btn-area">
                            <div class="account-btn"><i class="las la-mobile"></i></div>
                        </div>
                        <form class="account-form" action="{{route('user.verify.sms')}}" method="POST">
                            @csrf
                            <div class="row ml-b-20">
                                <h4 class="text-center">@lang('Please Verify Your Mobile to Get Access')</h4>

                                <p class="text-center">@lang('Your Mobile Number'):  <strong class="text--base">{{auth()->user()->mobile}}</strong></p>

                                <div class="col-lg-12 form-group">
                                    <label>@lang('Verification Code') <span class="text-center">*</span></label>
                                    <input type="text" name="sms_verified_code" id="code" class="form-control form--control" required>
                                </div>

                                <div class="col-lg-12 form-group">
                                    <div class="forgot-item">
                                        <label>@lang('If you don\'t receive any code you can') <a href="{{route('user.send.verify.code')}}?type=phone" class="text--base"> @lang('Try again')</a></label>

                                        @if ($errors->has('resend'))
                                            <br/>
                                            <small class="text-danger">{{ $errors->first('resend') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-12 form-group text-center">
                                    <button type="submit" class="submit-btn">@lang('Verify')</button>
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
