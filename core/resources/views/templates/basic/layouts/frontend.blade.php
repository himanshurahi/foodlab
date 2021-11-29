<!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title> {{ $general->sitename(__($pageTitle)) }}</title>
    @include('partials.seo')

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Rakkas&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- fontawesome css link -->
    <link rel="stylesheet" href="{{asset('assets/global/css/all.min.css')}}">
    <!-- bootstrap css link -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/bootstrap.min.css')}}">
    <!-- swipper css link -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/swiper.min.css')}}">
    <!-- line-awesome-icon css -->
    <link rel="stylesheet" href="{{asset('assets/global/css/line-awesome.min.css')}}">
    <!-- animate.css -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/animate.css')}}">
    <!-- main style css link -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/style.css')}}">
    <!-- custom style css link -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/custom.css')}}">

    <!-- site color -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/color.php?color1='.$general->base_color)}}">

    @stack('style-lib')

    @stack('style')
</head>
<body @if(request()->routeIs('restaurant.details')) data-bs-spy="scroll" data-bs-offset="270" data-bs-target=".food-details-tab" @endif>

    <div class="preloader">
        <div id="cooking">
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div id="area">
                <div id="sides">
                    <div id="pan"></div>
                    <div id="handle"></div>
                </div>
                <div id="pancake">
                    <div id="pastry"></div>
                </div>
            </div>
        </div>
    </div>

    <a href="#" class="scrollToTop">
        <i class="las la-dot-circle"></i>
        <span>@lang('Top')</span>
    </a>

    @include($activeTemplate.'partials.header')
        <div class="page-wrapper">
            @yield('content')
        </div>
    @include($activeTemplate.'partials.footer')

    @php
        $cookie = App\Models\Frontend::where('data_keys','cookie.data')->first();
    @endphp

    @if(@$cookie->data_values->status && !session('cookie_accepted'))
        <div class="cookie-remove">
            <div class="cookie__wrapper">
                <div class="container">
                    <div class="flex-wrap align-items-center justify-content-between">
                        <h4 class="title">@lang('Cookie Policy')</h4>
                        <p class="txt my-2">
                            @php echo @$cookie->data_values->description @endphp
                        </p>
                        <div class="button-wrapper">
                            <button class="cmn--btn btn--base policy cookie">@lang('Accept')</button>
                            <a class="read-policy" href="{{ @$cookie->data_values->link }}" target="_blank" class=" mt-2">@lang('Read Policy')</a>
                            <a href="javascript:void(0)" class="btn--close cookie-close"><i class="las la-times"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <!-- Optional JavaScript -->

    <!-- jquery -->
    <script src="{{asset('assets/global/js/jquery-3.6.0.min.js')}}"></script>
    <!-- bootstrap js -->
    <script src="{{asset($activeTemplateTrue.'js/bootstrap.bundle.min.js')}}"></script>
    <!-- swipper js -->
    <script src="{{asset($activeTemplateTrue.'js/swiper.min.js')}}"></script>
    <!-- heandline js -->
    <script src="{{asset($activeTemplateTrue.'js/heandline.js')}}"></script>
    <!-- wow js file -->
    <script src="{{asset($activeTemplateTrue.'js/wow.min.js')}}"></script>
    <!-- main -->
    <script src="{{asset($activeTemplateTrue.'js/main.js')}}"></script>

    @stack('script-lib')

    @stack('script')

    @include('partials.plugins')

    @include('partials.notify')


    <script>
        (function ($) {
            "use strict";
            $(".langSel").on("change", function() {
                window.location.href = "{{route('home')}}/change/"+$(this).val() ;
            });

            $('.cookie').on('click',function () {

                var url = "{{ route('cookie.accept') }}";

                $.get(url,function(response){

                    if(response.success){
                    notify('success',response.success);
                    $('.cookie-remove').html('');
                    }
                });
            });

            $('.cookie-close').on('click',function () {
                $('.cookie-remove').html('');
            });

        })(jQuery);
    </script>

</body>
</html>
