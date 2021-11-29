@php
    $footerContent = getContent('footer.content',true);
    $socialElements = getContent('social_icon.element',false,null,true);
    $policyElements = getContent('policy_pages.element');
@endphp

<section class="footer-area">
    <div class="footer-section pt-60">
        <div class="container">
            <div class="footer-wrapper open">
                <div class="footer-toggle"><span class="right-icon"></span><span class="title">@lang('Usefull Links') </span></div>
                <div class="footer-bottom-area">
                    <div class="row justify-content-center mb-30-none">
                        <div class="col-xl-4 col-lg-3 col-md-6 col-sm-6 mb-30">
                            <div class="footer-widget">
                                <h3 class="title">{{__(@$footerContent->data_values->title)}}</h3>
                                <p>{{__(@$footerContent->data_values->short_details)}}</p>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 mb-30">
                            <div class="footer-widget">
                                <h3 class="title">@lang('Quick Links')</h3>
                                <ul class="footer-links">
                                    <li><a href="{{route('home')}}">@LANG('Home')</a></li>
                                    <li><a href="{{route('latest.restaurants')}}">@lang('Restaurants')</a></li>
                                    @foreach($pages as $k => $data)
                                        <li><a href="{{route('pages',[$data->slug])}}">{{__($data->name)}}</a></li>
                                    @endforeach
                                    <li><a href="{{route('blogs')}}">@lang('Blogs')</a></li>
                                    <li><a href="{{route('contact')}}">@lang('Contact')</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 mb-30">
                            <div class="footer-widget">
                                <h3 class="title">@lang('Company Policy')</h3>
                                <ul class="footer-links">
                                    @foreach ($policyElements as $item)
                                        <li><a href="{{route('policy.details',[$item->id,slug(@$item->data_values->title)])}}">{{__(@$item->data_values->title)}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-3 col-md-6 col-sm-6 mb-30">
                            <div class="footer-widget">
                                <h3 class="title">{{__(@$footerContent->data_values->subscribe_title)}}</h3>
                                <p>{{__(@$footerContent->data_values->subscribe_short_details)}}</p>
                                <form class="subscribe-form">
                                    <input type="email" id="subscriber" name="email" placeholder="@lang('Email Adress')..">
                                    <button type="button" class="subs"><i class="fas fa-paper-plane"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-wrapper">
        <div class="container">
            <div class="copyright-area d-flex flex-wrap justify-content-between align-items-center">
                <div class="copyright">
                    <p>{{__(@$footerContent->data_values->copyright_details)}}</p>
                </div>
                <div class="social-area">
                    <ul class="footer-social">
                        @foreach ($socialElements as $item)
                            <li><a href="{{@$item->data_values->url}}" @if($loop->first) class="active" @endif> @php echo @$item->data_values->social_icon @endphp </a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@push('script')
    <script>
         (function ($) {
            $('.subs').on('click',function () {
                var email = $('#subscriber').val();
                var csrf = '{{csrf_token()}}'
                var url = "{{ route('subscriber.store') }}";
                var data = {email:email, _token:csrf};

                $.post(url, data,function(response){
                    if(response.success){
                        notify('success', response.success);
                        $('#subscriber').val('');
                    }else{
                        notify('error', response.error);
                    }
                });

            });
        })(jQuery);
    </script>
@endpush
