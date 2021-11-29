@extends($activeTemplate.'layouts.frontend')

@section('content')

<section class="blog-details-section blog-section ptb-60">
    <div class="container">
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-9 col-lg-8 mb-30">
                <div class="blog-item">
                    <div class="blog-thumb">
                        <a href="blog-details.html"><img src="{{ getImage('assets/images/frontend/blog/'.@$blog->data_values->image,'1440x940') }}" alt="food"></a>
                    </div>
                    <div class="blog-content">
                        <h2 class="title">{{__(@$blog->data_values->title)}}</h2>
                        @php
                            echo __(@$blog->data_values->description_nic);
                        @endphp
                    </div>
                </div>
                <div class="blog-social-area d-flex flex-wrap justify-content-between align-items-center">
                    <h3 class="title">@lang('Share This Post')</h3>
                    <ul class="blog-social">
                        <li><a href="http://www.facebook.com/sharer.php?u={{urlencode(url()->current())}}&p[title]={{slug(@$blog->data_values->title)}}" target="_blank" title="@lang('Facebook')"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="http://twitter.com/share?text={{slug(@$blog->data_values->title)}}&url={{urlencode(url()->current()) }}" target="_blank" title="@lang('Twitter')" class="active"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="http://pinterest.com/pin/create/button/?url={{urlencode(url()->current()) }}&description={{slug(@$blog->data_values->title)}}" target="_blank" title="@lang('Pinterest')"><i class="fab fa-pinterest-p"></i></a></li>
                        <li><a href="https://www.linkedin.com/shareArticle?mini=true&url={{urlencode(url()->current()) }}&title={{slug(@$blog->data_values->title)}}" target="_blank" title="@lang('Linkedin')"><i class="fab fa-linkedin-in"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 mb-30">
                <div class="sidebar">
                    <div class="blog-widget-box">
                        <h5 class="widget-title">@lang('Latest Posts')</h5>
                        <div class="popular-widget-box">
                            @foreach ($blogElements as $item)
                                <div class="single-popular-item d-flex flex-wrap">
                                    <div class="popular-item-thumb">
                                        <img src="{{ getImage('assets/images/frontend/blog/thumb_'.@$item->data_values->image,'800x800') }}" alt="blog">
                                    </div>
                                    <div class="popular-item-content">
                                        <h5 class="title"><a href="{{ route('blog.details',[slug(__(@$item->data_values->title)),$item->id]) }}">{{str_limit(__(@$item->data_values->title),40)}}</a></h5>
                                        <span class="blog-date">{{showDateTime(@$item->data_values->created_at,'d M Y')}}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@push('shareImage')
    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content="{{ __(@$blog->data_values->title) }}">
    <meta itemprop="description" content="{{ strip_tags(__(@$blog->data_values->description_nic)) }}">
    <meta itemprop="image" content="{{ getImage('assets/images/frontend/blog/'.@$blog->data_values->image,'1440x940') }}">

    <!-- Facebook Meta Tags -->
    <meta property="og:image" content="{{ getImage('assets/images/frontend/blog/'.@$blog->data_values->image,'1440x940') }}"/>
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ __(@$blog->data_values->title) }}">
    <meta property="og:description" content="{{ strip_tags(__(@$blog->data_values->description_nic)) }}">
    <meta property="og:image:type" content="{{ getImage('assets/images/frontend/blog/'.@$blog->data_values->image,'1440x940') }}" />
    <meta property="og:image:width" content="1440" />
    <meta property="og:image:height" content="940" />
    <meta property="og:url" content="{{ url()->current() }}">
@endpush

