@extends($activeTemplate.'layouts.frontend')

@section('content')
    <div class="confirm-payment">
        <div class="container ptb-80">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <img src="{{$deposit->gatewayCurrency()->methodImage()}}" class="card-img-top" alt="@lang('Image')" class="w-100">
                            <div class="card-right">
                                <h3 class="mt-3 text-center">@lang('Please Pay') <span class="text--danger">{{showAmount($deposit->final_amo)}} {{$deposit->method_currency}}</span></h3>
                                <h3 class="mt-3 text-center">@lang('To Get') <span class="text--success">{{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</span></h3>
                                <form action="{{$data->url}}" method="{{$data->method}}" class="text-center">
                                    <input type="hidden" custom="{{$data->custom}}" name="hidden">
                                    <script src="{{$data->checkout_js}}"
                                            @foreach($data->val as $key=>$value)
                                            data-{{$key}}="{{$value}}"
                                        @endforeach >
                                    </script>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";
            $('input[type="submit"]').addClass("mt-3 btn--base w-auto bg--base border-0");
        })(jQuery);
    </script>
@endpush


@push('style')

<style>
    .confirm-payment .card{
        box-shadow: 0 2px 8px 0 rgb(0 0 0 / 8%);
        border: none
    }
    .confirm-payment .card-body{
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }
    @media only screen and (max-width: 767px) {
        .confirm-payment .card-body{
            display: block;
            text-align: center;
        }
    }
    .confirm-payment .card-body img{
        width: 290px;
    }
    @media only screen and (max-width: 767px) {
        .confirm-payment .card-body img{
            width: 100%;
        }
    }
    .confirm-payment .card-body .card-right{
        width: calc(100% - 290px);
    }
    @media only screen and (max-width: 767px) {
        .confirm-payment .card-body .card-right{
            width: 100%;
        }
    }
</style>

@endpush
