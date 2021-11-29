@extends('admin.layouts.app')

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

                                            <a href="{{route('admin.orders.details',Crypt::encrypt($item->id))}}" class="icon-btn">@lang('Details')</a>

                                            @if (request()->routeIs('admin.orders.pending'))
                                                <a href="#0" data-id="{{$item->id}}" class="icon-btn bg-danger cancelBtn">@lang('Cancel')</a>
                                            @endif

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
                    {{ $orders->links('admin.partials.paginate') }}
                </div>
            </div>
        </div>
    </div>

    {{-- REJECT MODAL --}}
    <div id="cancelModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Cancel Order Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.orders.cancel')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('cancel')</span> <span class="font-weight-bold text-success"></span> @lang('this order')?</p>

                        <div class="form-group">
                            <label class="font-weight-bold mt-2">@lang('Reason for Cancelation')</label>
                            <textarea name="message" id="message" placeholder="@lang('Reason for Cancelation')" class="form-control" rows="5"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Cancel')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push('breadcrumb-plugins')
        @if((request()->routeIs('admin.orders.pending')) || (request()->routeIs('admin.orders.delivered')) || (request()->routeIs('admin.orders.canceled')))
            <form action="{{ route('admin.orders.search')}}" method="GET" class="form-inline float-sm-right bg--white mt-2">
                <div class="input-group has_append">
                    <input type="text" name="search" class="form-control" placeholder="@lang('Order Code')" value="{{ $search ?? '' }}">
                    <div class="input-group-append">
                        <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
        @else
            <form action="{{ route('admin.orders.search')}}" method="GET" class="form-inline float-sm-right bg--white mt-2">
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

@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.cancelBtn').on('click', function () {
                var modal = $('#cancelModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

