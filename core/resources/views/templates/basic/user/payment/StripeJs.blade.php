@extends($activeTemplate.'layouts.frontend')
@section('content')

    <div class="confirm-payment">
        <div class="container ptb-80">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <img src="{{$deposit->gatewayCurrency()->methodImage()}}" class="card-img-top" alt="@lang('Image')" class="w-100">
                            <form action="{{$data->url}}" method="{{$data->method}}">
                                <h3 class="text-center mt-3">@lang('Please Pay') {{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</h3>
                                <h3 class="my-3 text-center">@lang('To Get') {{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</h3>
                                    <div class="text-center">
                                        <script src="{{$data->src}}"
                                            class="stripe-button"
                                            @foreach($data->val as $key=> $value)
                                            data-{{$key}}="{{$value}}"
                                            @endforeach
                                        >
                                        </script>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        (function ($) {
            "use strict";
            $('button[type="submit"]').addClass(" btn-success btn-round custom-success text-center btn-lg");
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
    .confirm-payment .card-body form{
        width: calc(100% - 290px);
    }
    @media only screen and (max-width: 767px) {
        .confirm-payment .card-body form{
            width: 100%;
        }
    }
</style>

@endpush
