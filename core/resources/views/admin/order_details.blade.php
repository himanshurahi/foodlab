@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-12 col-md-12 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Order Details')</h5>
                    <ul class="list-group">

                        @foreach($order->details as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{__($item->food->name)}} x {{$item->qty}}
                                <span class="font-weight-bold">{{ showAmount($item->price) }} {{$general->cur_text}}</span>
                            </li>
                        @endforeach

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><b>@lang('Subtotal')</b></span>
                            <span class="font-weight-bold">{{ showAmount($order->sub_total) }} {{$general->cur_text}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><b>@lang('Delivery Charge')</b></span>
                            <span class="font-weight-bold">{{ showAmount($order->d_charge) }} {{$general->cur_text}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><b>@lang('Vat')</b></span>
                            <span class="font-weight-bold">{{ showAmount($order->vat) }} {{$general->cur_text}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><b>@lang('Discount')</b></span>
                            <span class="font-weight-bold">{{ showAmount($order->discount) }} {{$general->cur_text}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><b>@lang('Total')</b></span>
                            <span class="font-weight-bold">{{ showAmount($order->total) }} {{$general->cur_text}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><b>@lang('Status')</b></span>
                            @if($order->status == 1)
                                <span class="badge badge-pill bg--primary">@lang('Confirmed')</span>
                            @elseif($order->status == 2)
                                <span class="badge badge-pill bg--success">@lang('Deliverd')</span>
                            @elseif($order->status == 3)
                                <span class="badge badge-pill bg--danger">@lang('Canceled')</span>
                            @elseif($order->status == 4)
                                <span class="badge badge-pill bg--warning">@lang('Pending')</span>
                            @endif
                        </li>
                    </ul>

                    <h5 class="mb-20 mt-20 text-muted">@lang('Delivery Address')</h5>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <span>{{__($order->d_address)}}</span>
                        </li>
                    </ul>

                    @if ($order->status == 3)
                        <h5 class="mb-20 mt-20 text-muted">@lang('Cancellation Reason')</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span>{{__($order->cancel_message)}}</span>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
