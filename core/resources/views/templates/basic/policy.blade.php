@extends($activeTemplate.'layouts.frontend')

@section('content')
    <section class="policy-section blog-details-section blog-section ptb-60">
        <div class="container">
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-12 col-lg-12 mb-30">
                    <div class="blog-item">
                        <div class="blog-content">
                            <h2 class="title">{{__(@$policy->data_values->title)}}</h2>
                            @php
                                echo @$policy->data_values->details;
                            @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
