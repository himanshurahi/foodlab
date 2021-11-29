@extends($activeTemplate.'layouts.frontend')

@section('content')

    <section class="account-section ptb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-6 col-md-8">
                    <div class="account-form-area">
                        <div class="account-header">
                            <h3 class="title">@lang('Reset Password')</h3>
                        </div>
                        <div class="account-btn-area">
                            <div class="account-btn"><i class="las la-key"></i></div>
                        </div>
                        <form class="account-form" method="POST" action="{{ route('user.password.email') }}">
                            @csrf
                            <div class="row ml-b-20">
                                <div class="col-lg-12 form-group">
                                    <label>@lang('Select One')</label>
                                    <select name="type" class="form-control form--control">
                                        <option value="email">@lang('E-Mail Address')</option>
                                        <option value="username">@lang('Username')</option>
                                    </select>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label class="my_value"></label>
                                    <input type="text" class="form-control form--control @error('value') is-invalid @enderror" name="value" value="{{ old('value') }}" required autofocus="off">

                                    @error('value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-lg-12 form-group text-center">
                                    <button type="submit" class="submit-btn"> @lang('Send Password Code')</button>
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

            myVal();
            $('select[name=type]').on('change',function(){
                myVal();
            });
            function myVal(){
                $('.my_value').text($('select[name=type] :selected').text());
            }
        })(jQuery)
    </script>
@endpush
