@php
    $blogElements = getContent('blog.element',false,4,true);
@endphp

<section class="blog-section ptb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="section-header">
                    <h2 class="section-title">@lang('Latest Blogs')</h2>
                    <div class="see-all-btn">
                        <a href="{{route('blogs')}}" class="custom-btn">@lang('See all blogs') <i class="las la-angle-double-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-30-none">
            @foreach ($blogElements as $item)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-6 mb-30">
                    <div class="blog-item">
                        <div class="blog-thumb h-auto">
                            <a href="{{ route('blog.details',[slug(__(@$item->data_values->title)),$item->id]) }}"><img src="{{ getImage('assets/images/frontend/blog/thumb_'.@$item->data_values->image,'800x800') }}" alt="food"></a>
                        </div>
                        <div class="blog-content">
                            <h4 class="title"><a href="{{ route('blog.details',[slug(__(@$item->data_values->title)),$item->id]) }}">{{str_limit(__(@$item->data_values->title),60)}}</a></h4>
                            <div class="blog-btn mt-20">
                                <a href="{{ route('blog.details',[slug(__(@$item->data_values->title)),$item->id]) }}" class="custom-btn">@lang('Read More') <i class="las la-angle-double-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
