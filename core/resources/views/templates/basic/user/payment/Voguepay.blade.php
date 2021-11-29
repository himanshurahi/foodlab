@extends($activeTemplate.'layouts.frontend')
@section('content')
    <div class="confirm-payment">
        <div class="container ptb-80">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="{{$deposit->gatewayCurrency()->methodImage()}}" class="card-img-top" alt="@lang('Image')" class="w-100">
                            <div class="card-right">
                                <h3 class="mt-4">@lang('Please Pay') <span class="text--danger">{{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</span></h3>
                                <h3 class="">@lang('To Get') <span class="text--success">{{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</span></h3>
                                <button type="button" class=" mt-3 btn--base" id="btn-confirm">@lang('Pay Now')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="//pay.voguepay.com/js/voguepay.js"></script>
    <script>
        "use strict";
        var closedFunction = function() {
        }
        var successFunction = function(transaction_id) {
            window.location.href = '{{ route(gatewayRedirectUrl()) }}';
        }
        var failedFunction=function(transaction_id) {
            window.location.href = '{{ route(gatewayRedirectUrl()) }}' ;
        }

        function pay(item, price) {
            //Initiate voguepay inline payment
            Voguepay.init({
                v_merchant_id: "{{ $data->v_merchant_id}}",
                total: price,
                notify_url: "{{ $data->notify_url }}",
                cur: "{{$data->cur}}",
                merchant_ref: "{{ $data->merchant_ref }}",
                memo:"{{$data->memo}}",
                recurrent: true,
                frequency: 10,
                developer_code: '60a4ecd9bbc77',
                custom: "{{ $data->custom }}",
                customer: {
                  name: 'Customer name',
                  country: 'Country',
                  address: 'Customer address',
                  city: 'Customer city',
                  state: 'Customer state',
                  zipcode: 'Customer zip/post code',
                  email: 'example@example.com',
                  phone: 'Customer phone'
                },
                closed:closedFunction,
                success:successFunction,
                failed:failedFunction
            });
        }

        (function ($) {

            $('#btn-confirm').on('click', function (e) {
                e.preventDefault();
                pay('Buy', {{ $data->Buy }});
            });

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
