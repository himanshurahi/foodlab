@extends($activeTemplate.'layouts.frontend')
@section('content')
@include($activeTemplate.'user.breadcrumb')
<section class="checkout-section ptb-60">
    <div class="container">
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-7 col-lg-7 col-md-8 mb-30">
                <div class="checkout-details-area">
                    <div class="checkout-details-header">
                        <h2 class="title">@lang('Delivery Details')</h2>
                    </div>
                    <div class="checkout-details-widget mb-20">
                        <h5 class="checkout-widget-title">@lang('Delivery Time') :</h5>
                        <div class="checkout-widget-content">
                            <span class="delivery-date">{{\Carbon\Carbon::parse($order->created_at)->format('D, M y')}} @lang('ASAP')</span>
                            <p>@lang('Max Delivery Time') : {{$order->restaurant->d_time}} @lang('minutes')</p>
                        </div>
                    </div>

                    <div class="checkout-details-widget mb-20">
                        <div class="checkout-widget-header d-flex flex-wrap justify-content-between">
                            <h5 class="checkout-widget-title">@lang('Delivery Adress') :</h5>
                        </div>
                        <div class="checkout-widget-content">
                            <div class="personal-details mt-10">
                                <span class="email">{{__($order->d_address)}}</span>
                            </div>
                        </div>
                    </div>

                    @if ($order->status == 1)
                        <div class="checkout-details-widget mb-20">
                            <h5 class="checkout-widget-title">@lang('Delivery Confirmation') :</h5>
                            <form action="{{route('user.make.delivery.confirm')}}" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{\Crypt::encrypt($order->id)}}">
                                <div class="form-group">
                                    <input type="text" class="form--control" name="order_code" placeholder="@lang('Order code from you E-mail or Dashboard')" maxlength="6" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="submit-btn w-100 mt-20">@lang('Confirm')</button>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 mb-30">
                <div class="checkout-sidebar">
                    <div class="cart-header mb-20">
                        <h4 class="title text-center mb-0">{{$order->restaurant->r_name}}</h4>
                    </div>
                    <div class="card-content">
                        <div class="food-item">
                            <div class="food-wrapper">
                                <ul class="food-order-list">
                                    @foreach ($order->details as $item)
                                        <li class="cart-item">{{$item->qty}} x {{__($item->food->name)}}<span>{{$general->cur_text}} {{showAmount($item->price)}}</span></li>
                                    @endforeach

                                    <li>@lang('Subtotal') <span>{{$general->cur_text}} {{showAmount($order->sub_total)}}</span></li>
                                    <li>@lang('Delivery Fee') <span>{{$general->cur_text}} {{showAmount($order->d_charge)}}</span></li>
                                    <li>@lang('Vat') <span>{{$general->cur_text}} {{showAmount($order->vat)}}</span></li>
                                    <li>@lang('Discount') <span>{{$general->cur_text}} {{showAmount($order->discount)}}</span></li>
                                    <li><span class="fw-bold">@lang('Total(Incl.vat)')</span> <span class="fw-bold">{{$general->cur_text}} {{showAmount($order->total)}}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
