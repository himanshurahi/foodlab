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
                                    <th>@lang('Trx')</th>
                                    <th>@lang('Transacted')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Post Balance')</th>
                                    <th>@lang('Details')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse ($transactions as $item)
                                    <tr>
                                        <td data-label="@lang('Trx')">{{ $item->trx }}</td>
                                        <td data-label="@lang('Transacted')">{{ showDateTime($item->created_at) }}</td>
                                        <td data-label="@lang('Amount')">
                                            <span class="font-weight-bold @if($item->trx_type == '+')text--success @else text--danger @endif">
                                                {{ $item->trx_type }} {{showAmount($item->amount)}} {{ $general->cur_text }}
                                            </span>
                                        </td>
                                        <td data-label="@lang('Post Balance')">{{ showAmount($item->post_balance) }} {{ __($general->cur_text) }}</td>
                                        <td data-label="@lang('Details')">{{ __($item->details) }}</td>
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
                    {{ $transactions->links('restaurant.partials.paginate') }}
                </div>
            </div>
        </div>
    </div>
@endsection
