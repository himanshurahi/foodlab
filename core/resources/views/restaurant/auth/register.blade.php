@extends('restaurant.layouts.master')

@section('content')
@php
    $policyElements = getContent('policy_pages.element');
@endphp
<div class="page-wrapper default-version">
    <div class="form-area bg_img bg_fixed" data-background="{{asset('assets/admin/images/1.jpg')}}">
        <div class="container p-0">
            <div class="row justify-content-center no-gutters">
                <div class="col-lg-10">
                    <div class="form-wrapper w-100">
                        <h4 class="logo-text mb-15">@lang('Welcome to') <strong>{{$general->sitename}}</strong></h4>
                        <p>{{$pageTitle}}</p>
                        <form action="" method="POST" class="cmn-form mt-30 form-row" onsubmit="return submitUserForm();">
                            @csrf
                            <div class="form-group col-md-6">
                                <label for="firstname">@lang('First Name')</label>
                                <input type="text" name="firstname" class="form-control b-radius--capsule" value="{{ old('firstname') }}" required>
                                <i class="las la-user input-icon"></i>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lastname">@lang('Last Name')</label>
                                <input type="text" name="lastname" class="form-control b-radius--capsule" value="{{ old('lastname') }}" required>
                                <i class="las la-user input-icon"></i>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lastname">@lang('Restaurant Name')</label>
                                <input type="text" name="r_name" class="form-control b-radius--capsule" value="{{ old('r_name') }}" required>
                                <i class="las la-utensils input-icon"></i>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lastname">@lang('Business Location')</label>
                                <select name="location_id" class="form-control b-radius--capsule" required>
                                    @foreach ($locations as $item)
                                        <option value="{{$item->id}}">{{__($item->name)}}</option>
                                    @endforeach
                                </select>
                                <i class="las la-map-marker-alt input-icon"></i>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="username">@lang('Username')</label>
                                <input type="text" name="username" class="form-control b-radius--capsule checkUser" id="username" value="{{ old('username') }}" required>
                                <i class="las la-user input-icon"></i>
                                <small class="text-danger usernameExist"></small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">@lang('Email')</label>
                                <input type="email" name="email" class="form-control b-radius--capsule checkUser" id="email" required>
                                <i class="lar la-envelope input-icon"></i>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="country">@lang('Country')</label>
                                <select name="country" id="country" class="form-control b-radius--capsule">
                                    @foreach($countries as $key => $country)
                                        <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                                    @endforeach
                                </select>
                                <i class="lab la-font-awesome-flag input-icon"></i>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="lastname">@lang('Your Phone Number')</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text  mobile-code left--capsule">
                                        </span>
                                        <input type="hidden" name="mobile_code">
                                        <input type="hidden" name="country_code">
                                    </div>
                                    <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}" class="form-control b-radius--capsule checkUser" placeholder="@lang('Your Phone Number')">
                                </div>
                                <i class="lab la-font-awesome-flag input-icon"></i>
                                <small class="text-danger mobileExist"></small>
                            </div>

                            <div class="form-group col-md-6 hover-input-popup">
                                <label for="password">@lang('Password')</label>
                                <input type="password" name="password" class="form-control b-radius--capsule" id="password" placeholder="@lang('Enter your password')" required>
                                <i class="las la-lock input-icon"></i>

                                @if($general->secure_password)
                                    <div class="input-popup">
                                        <p class="error lower">@lang('1 small letter minimum')</p>
                                        <p class="error capital">@lang('1 capital letter minimum')</p>
                                        <p class="error number">@lang('1 number minimum')</p>
                                        <p class="error special">@lang('1 special character minimum')</p>
                                        <p class="error minimum">@lang('6 character password')</p>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="password-confirm">@lang('Confirm Password')</label>
                                <input type="password" name="password_confirmation" class="form-control b-radius--capsule" id="password-confirm" placeholder="@lang('Confirm your password')" required>
                                <i class="las la-lock input-icon"></i>
                            </div>

                            <div class="form-group col-md-12 google-captcha">
                                @php echo loadReCaptcha() @endphp
                            </div>
                            @include('restaurant.partials.custom_captcha')

                            @if($general->agree)
                                <div class="col-md-12 form-group">
                                    <div class="form-group form-check">
                                        <input type="checkbox" id="agree" name="agree" class="form-check-input">
                                        <label class="form-check-label fs--14px" for="agree">@lang('I have read agreed with the')

                                            @foreach ($policyElements as $item)
                                                <a href="{{route('policy.details',[$item->id,slug(@$item->data_values->title)])}}" class="text--base"> {{__(@$item->data_values->title)}} @if(!$loop->last),@endif</a>
                                            @endforeach

                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group col-md-12">
                                <button type="submit" id="recaptcha" class="submit-btn mt-25 b-radius--capsule">@lang('Register') <i class="las la-sign-in-alt"></i></button>
                            </div>
                            <div class="form-group mb-0 col-md-12">
                                <a href="{{route('restaurant.login')}}" class="text-muted text--small"><i class="las la-lock"></i>@lang('Already Have an account ?')</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="existModalCenter" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('You are with us')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>@lang('You already have an account please Sign in ')</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                <a href="{{ route('restaurant.login') }}" class="btn btn--primary">@lang('Login')</a>
            </div>

        </div>
    </div>
</div>

@endsection

@push('style')
<style>
    .country-code .input-group-prepend .input-group-text{
        background: #fff !important;
    }
    .country-code select{
        border: none;
    }
    .country-code select:focus{
        border: none;
        outline: none;
    }
    .hover-input-popup {
        position: relative;
    }
    .hover-input-popup:hover .input-popup {
        opacity: 1;
        visibility: visible;
    }
    .input-popup {
        position: absolute;
        bottom: 130%;
        left: 50%;
        width: 280px;
        background-color: #1a1a1a;
        color: #fff;
        padding: 20px;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        -ms-border-radius: 5px;
        -o-border-radius: 5px;
        -webkit-transform: translateX(-50%);
        -ms-transform: translateX(-50%);
        transform: translateX(-50%);
        opacity: 0;
        visibility: hidden;
        -webkit-transition: all 0.3s;
        -o-transition: all 0.3s;
        transition: all 0.3s;
    }
    .input-popup::after {
        position: absolute;
        content: '';
        bottom: -19px;
        left: 50%;
        margin-left: -5px;
        border-width: 10px 10px 10px 10px;
        border-style: solid;
        border-color: transparent transparent #1a1a1a transparent;
        -webkit-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        transform: rotate(180deg);
    }
    .input-popup p {
        padding-left: 20px;
        position: relative;
    }
    .input-popup p::before {
        position: absolute;
        content: '';
        font-family: 'Line Awesome Free';
        font-weight: 900;
        left: 0;
        top: 4px;
        line-height: 1;
        font-size: 18px;
    }
    .input-popup p.error {
        text-decoration: line-through;
    }
    .input-popup p.error::before {
        content: "\f057";
        color: #ea5455;
    }
    .input-popup p.success::before {
        content: "\f058";
        color: #28c76f;
    }
    .fs--14px {
        font-size: 14px !important;
    }
</style>
@endpush
@push('script-lib')
<script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush
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

        (function ($) {
            @if($mobile_code)
            $(`option[data-code={{ $mobile_code }}]`).attr('selected','');
            @endif

            $('select[name=country]').change(function(){
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            @if($general->secure_password)
                $('input[name=password]').on('input',function(){
                    secure_password($(this));
                });
            @endif

            $('.checkUser').on('focusout',function(e){
                var url = '{{ route('restaurant.checkRestaurant') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {mobile:mobile,_token:token}
                }
                if ($(this).attr('name') == 'email') {
                    var data = {email:value,_token:token}
                }
                if ($(this).attr('name') == 'username') {
                    var data = {username:value,_token:token}
                }
                $.post(url,data,function(response) {
                  if (response['data'] && response['type'] == 'email') {
                    $('#existModalCenter').modal('show');
                  }else if(response['data'] != null){
                    $(`.${response['type']}Exist`).text(`${response['type']} already exist`);
                  }else{
                    $(`.${response['type']}Exist`).text('');
                  }
                });
            });

        })(jQuery);

    </script>
@endpush
