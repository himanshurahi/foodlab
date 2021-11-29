@extends($activeTemplate.'layouts.frontend')

@section('content')
    <div class="confirm-payment ptb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card card-deposit text-center">
                        <div class="card-body card-body-deposit text-center">
                            <img src="{{$data->img}}" alt="@lang('Image')">
                            <div class="card-right">
                                <h4 class="my-2"> @lang('PLEASE SEND EXACTLY') <span class="text--success"> {{ $data->amount }}</span> {{__($data->currency)}}</h4>
                                <h5 class="mb-2">@lang('TO') <span class="text--success"> {{ $data->sendto }}</span></h5>
                                <h4 class="my-4">@lang('SCAN TO SEND')</h4>
                            </div>
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
