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
                                <h3 class="mt-3">@lang('Please Pay') <span class="text--danger"> {{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</span></h3>
                                <h3 class="mb-3">@lang('To Get') <span class="text--success">{{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</span></h3>
                                <button type="button" class="btn--base" id="btn-confirm" onClick="payWithRave()">@lang('Pay Now')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script>
        "use strict"
        var btn = document.querySelector("#btn-confirm");
        btn.setAttribute("type", "button");
        const API_publicKey = "{{$data->API_publicKey}}";

        function payWithRave() {
            var x = getpaidSetup({
                PBFPubKey: API_publicKey,
                customer_email: "{{$data->customer_email}}",
                amount: "{{$data->amount }}",
                customer_phone: "{{$data->customer_phone}}",
                currency: "{{$data->currency}}",
                txref: "{{$data->txref}}",
                onclose: function () {
                },
                callback: function (response) {
                    var txref = response.tx.txRef;
                    var status = response.tx.status;
                    var chargeResponse = response.tx.chargeResponseCode;
                    if (chargeResponse == "00" || chargeResponse == "0") {
                        window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                    } else {
                        window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                    }
                        // x.close(); // use this to close the modal immediately after payment.
                    }
                });
        }
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
