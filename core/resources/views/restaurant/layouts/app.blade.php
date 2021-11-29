@extends('restaurant.layouts.master')

@section('content')
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        @include('restaurant.partials.sidenav')
        @include('restaurant.partials.topnav')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                @include('restaurant.partials.breadcrumb')

                @yield('panel')


            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>



@endsection
