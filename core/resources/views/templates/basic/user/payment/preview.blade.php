@extends($activeTemplate.'layouts.frontend')
@section('content')

<section class="payment-section payment-preview-section">
    <div class="container ptb-60">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="payment-item d-flex flex-wrap align-items-center">
                    <div class="payment-thumb">
                        <img src="{{ $data->gatewayCurrency()->methodImage() }}" alt="@lang('Image')" class="deposit--img" />
                    </div>
                    <div class="payment-content">
                        <ul class="payment-list">
                            <li>
                                @lang('Amount'):
                                <strong class="text--primary">{{showAmount($data->amount)}} </strong> {{__($general->cur_text)}}
                            </li>
                            <li>
                                @lang('Charge'):
                                <strong class="text--danger">{{showAmount($data->charge)}}</strong> {{__($general->cur_text)}}
                            </li>
                            <li>
                                @lang('Payable'): <strong class="text--info"> {{showAmount($data->amount + $data->charge)}}</strong> {{__($general->cur_text)}}
                            </li>
                            <li>
                                @lang('Conversion Rate'): <strong class="text--success">1 {{__($general->cur_text)}} = {{showAmount($data->rate)}}  {{__($data->baseCurrency())}}</strong>
                            </li>
                            <li>
                                @lang('In') {{$data->baseCurrency()}}:
                                <strong class="text--info">{{showAmount($data->final_amo)}}</strong>
                            </li>


                            @if($data->gateway->crypto==1)
                                <li>
                                    @lang('Conversion with')
                                    <b> {{ __($data->method_currency) }}</b> @lang('and final value will Show on next step')
                                </li>
                            @endif
                        </ul>
                        <div class="payment-btn">
                            @if( 1000 >$data->method_code)
                                <a href="{{route('user.deposit.confirm')}}" class="btn--base">@lang('Pay Now')</a>
                            @else
                                <a href="{{route('user.deposit.manual.confirm')}}" class="btn--base">@lang('Pay Now')</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


