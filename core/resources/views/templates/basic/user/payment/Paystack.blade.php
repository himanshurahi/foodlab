@extends($activeTemplate.'layouts.frontend')
@section('content')
    <div class="confirm-payment">
        <div class="container ptb-80">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <img src="{{$deposit->gatewayCurrency()->methodImage()}}" class="card-img-top" alt="@lang('Image')" class="w-100">
                            <form action="{{ route('ipn.'.$deposit->gateway->alias) }}" method="POST" class="text-center">
                                @csrf
                                <h3 class="mt-4">@lang('Please Pay') <span class="text-danger"> {{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</span></h3>
                                <h3 class="mb-3">@lang('To Get') <span class="text--success">{{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</span></h3>
                                <button type="button" class=" mt-3 btn--base btn-round custom-success text-center btn-lg" id="btn-confirm">@lang('Pay Now')</button>
                                <script
                                    src="//js.paystack.co/v1/inline.js"
                                    data-key="{{ $data->key }}"
                                    data-email="{{ $data->email }}"
                                    data-amount="{{$data->amount}}"
                                    data-currency="{{$data->currency}}"
                                    data-ref="{{ $data->ref }}"
                                    data-custom-button="btn-confirm"
                                >
                                </script>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


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
