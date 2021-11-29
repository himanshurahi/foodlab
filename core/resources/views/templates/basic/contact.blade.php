@extends($activeTemplate.'layouts.frontend')

@section('content')

@php
    $contactContent = getContent('contact_us.content',true);
    $contactElements = getContent('contact_us.element',false);
@endphp

<div class="map-section">
    <div class="maps" id="map"></div>
</div>

<section class="contact-section ptb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12">
                <div class="contact-wrapper">
                    <div class="row justify-content-center">
                        <div class="col-xl-4 col-lg-4">
                            <div class="contact-info-item-area">
                                <div class="contact-info-item-inner mb-30-none">
                                    @foreach($contactElements as $item)
                                        <div class="contact-info-item d-flex flex-wrap align-items-center mb-40">
                                            <div class="contact-info-icon">
                                                @php echo @$item->data_values->icon @endphp
                                            </div>
                                            <div class="contact-info-content">
                                                <h3 class="title">{{__(@$item->data_values->title)}}</h3>
                                                <p>{{__(@$item->data_values->details)}}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6">
                            <div class="contact-form-area">
                                <h3 class="title">{{__(@$contactContent->data_values->title)}}</h3>
                                <p>{{__(@$contactContent->data_values->short_details)}}</p>
                                <form class="contact-form" action="" method="POST">
                                    @csrf
                                    <div class="row justify-content-center mb-10-none">
                                        <div class="col-lg-6 col-md-6 form-group">
                                            <input type="text" name="name" class="form--control" value="@if(auth()->user()) {{ auth()->user()->fullname }} @endif" placeholder="@lang('Your Name')*" @if(auth()->user()) readonly @else required @endif>
                                        </div>
                                        <div class="col-lg-6 col-md-6 form-group">
                                            <input type="text" name="subject" class="form--control" value="{{old('subject')}}" placeholder="@lang('Subject')*" required>
                                        </div>
                                        <div class="col-lg-12 form-group">
                                            <input type="email" name="email" class="form--control" value="@if(auth()->user()) {{ auth()->user()->email }} @endif" placeholder="@lang('Your Email')*" @if(auth()->user()) readonly @else required @endif>
                                        </div>
                                        <div class="col-lg-12 form-group">
                                            <textarea name="message" class="form--control" placeholder="@lang('Your Message')*" required>{{old('message')}}</textarea>
                                        </div>
                                        <div class="col-lg-12 form-group">
                                            <button type="submit" class="submit-btn mt-20">@lang('Send Message')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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

@push('script')

    <script src="https://maps.googleapis.com/maps/api/js?key={{@$contactContent->data_values->map_key}}&callback=initMap&libraries=&v=weekly" async></script>

    <script>
        // Initialize and add the map
        function initMap() {
            // The location of Uluru
            const uluru = { lat: {{@$contactContent->data_values->latitude}}, lng: {{@$contactContent->data_values->longitude}}};
            // The map, centered at Uluru
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: uluru,
            });
            // The marker, positioned at Uluru
            const marker = new google.maps.Marker({
                position: uluru,
                map: map,
            });
        }
    </script>
@endpush
