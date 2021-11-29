@extends($activeTemplate.'layouts.frontend')
@section('content')
@include($activeTemplate.'user.breadcrumb')

    <div class="card-area ptb-60">
        <div class="container">
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-6 mb-30">
                    <div class="card custom--card">
                        <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                @lang('Two Factor Authenticator')
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="card-form-wrapper">
                                @if(Auth::user()->ts)
                                    <div class="form-group mx-auto text-center">
                                        <a href="javascript:void(0)" class="btn--base" data-bs-toggle="modal" data-bs-target="#disableModal">@lang('Disable Two Factor Authenticator')</a>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" name="key" value="{{$secret}}" class="form--control" id="referralURL" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text copytext" id="copyBoard"> <i class="fa fa-copy"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mx-auto text-center">
                                        <img class="mx-auto"
                                            src="{{$qrCodeUrl}}">
                                    </div>
                                    <div class="form-group mx-auto text-center">
                                        <a href="javascript:void(0)" class="btn--base" data-bs-toggle="modal" data-bs-target="#enableModal">@lang('Enable Two Factor Authenticator')</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mb-30">
                    <div class="card custom--card">
                        <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                @lang('Google Authenticator')
                            </h4>
                        </div>
                        <div class="card-body">
                            <h4 class="mb-2">@lang('Download Google Authenticator App')</h4>
                            <p>@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.')</p>
                            <hr />
                            <a class="btn--base" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                                target="_blank">@lang('Download App')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Enable Modal -->
    <div id="enableModal" class="modal fade" role="dialog">
        <div class="modal-dialog ">
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Verify Your Otp')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <form action="{{route('user.twofactor.enable')}}" method="POST">
                    @csrf
                    <div class="modal-body ">
                        <div class="form-group">
                            <input type="hidden" name="key" value="{{$secret}}">
                            <input type="text" class="form-control" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn--base">@lang('Verify')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Disable Modal -->
    <div id="disableModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Verify Your Otp Disable')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <form action="{{route('user.twofactor.disable')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn--base">@lang('Verify')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        (function($){
            "use strict";

            $('.copytext').on('click',function(){
                var copyText = document.getElementById("referralURL");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                iziToast.success({message: "Copied: " + copyText.value, position: "topRight"});
            });
        })(jQuery);
    </script>
@endpush


