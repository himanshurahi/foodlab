@extends('restaurant.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('SL')</th>
                                    <th scope="col">@lang('Order Code')</th>
                                    <th scope="col">@lang('Subtotal')</th>
                                    <th scope="col">@lang('Delivery Charge')</th>
                                    <th scope="col">@lang('Vat')</th>
                                    <th scope="col">@lang('Discount')</th>
                                    <th scope="col">@lang('Total')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse ($orders as $item)
                                    <tr>
                                        <td data-label="@lang('SL')">{{ $loop->index+1 }}</td>
                                        <td data-label="@lang('Order Code')">{{$item->order_code}}</td>
                                        <td data-label="@lang('Subtotal')">{{showAmount($item->sub_total)}}{{$general->cur_text}}</td>
                                        <td data-label="@lang('Delivery Charge')">{{showAmount($item->d_charge)}}{{$general->cur_text}}</td>
                                        <td data-label="@lang('Vat')">{{showAmount($item->vat)}}{{$general->cur_text}}</td>
                                        <td data-label="@lang('Discount')">{{showAmount($item->discount)}}{{$general->cur_text}}</td>
                                        <td data-label="@lang('Total')">{{showAmount($item->total)}}{{$general->cur_text}}</td>
                                        <td data-label="@lang('Action')">

                                            <a href="{{route('restaurant.orders.details',Crypt::encrypt($item->id))}}" class="icon-btn">@lang('Details')</a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ $emptyMessage }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $orders->links('restaurant.partials.paginate') }}
                </div>
            </div>
        </div>
    </div>


    @push('breadcrumb-plugins')
        @if((request()->routeIs('restaurant.orders.pending')) || (request()->routeIs('restaurant.orders.delivered')) || (request()->routeIs('restaurant.orders.canceled')))
            <form action="{{ route('restaurant.orders.search')}}" method="GET" class="form-inline float-sm-right bg--white mt-2">
                <div class="input-group has_append">
                    <input type="text" name="search" class="form-control" placeholder="@lang('Order Code')" value="{{ $search ?? '' }}">
                    <div class="input-group-append">
                        <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
        @else
            <form action="{{ route('restaurant.orders.search')}}" method="GET" class="form-inline float-sm-right bg--white mt-2">
                <div class="input-group has_append">
                    <input type="text" name="search" class="form-control" placeholder="@lang('Order Code')" value="{{ $search ?? '' }}">
                    <div class="input-group-append">
                        <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
        @endif
    @endpush
@endsection
