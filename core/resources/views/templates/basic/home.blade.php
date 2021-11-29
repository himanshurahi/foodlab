@extends($activeTemplate.'layouts.frontend')
@section('content')
    @php
        $bannerContent = getContent('banner.content',true);
        $bannerElements = getContent('banner.element');
    @endphp
    <section class="banner-section bg_img" data-background="{{ getImage('assets/images/frontend/banner/'.@$bannerContent->data_values->image,'1920x1090') }}">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-xl-6 col-lg-6 text-center">
                    <div class="banner-content">
                        <h1 class="title cd-headline clip">
                            <span class="cd-words-wrapper">
                                @foreach ($bannerElements as $item)
                                    <b @if($loop->first) class="is-visible" @endif>{{__(@$item->data_values->animated_title)}}</b>
                                @endforeach
                            </span>
                        </h1>
                        <h4 class="sub-title">{{__(@$bannerContent->data_values->sub_heading)}}</h4>
                        <form class="banner-form" action="{{route('search')}}" method="GET">
                            <div class="form-group">
                                <input type="text" class="form-control" name="search" placeholder="@lang('By restaurant or food name')">
                                <button type="submit" class="submit-btn">@lang('Search')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($sections->secs != null)
        @foreach(json_decode($sections->secs) as $sec)
            @include($activeTemplate.'sections.'.$sec)
        @endforeach
    @endif
@endsection
