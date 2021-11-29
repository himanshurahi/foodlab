@extends($activeTemplate.'layouts.frontend')

@section('content')
@include($activeTemplate.'user.breadcrumb')

    <section class="table-section ptb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-12">
                    <div class="table-area">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>@lang('Trx')</th>
                                    <th>@lang('Transacted')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Post Balance')</th>
                                    <th>@lang('Details')</th>
                                </tr>
                            </thead>
                            <tbody>
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
                                        <td colspan="100%" class="text-center">{{__($emptyMessage)}}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                    {{$transactions->links()}}
                </div>
            </div>
        </div>
    </section>
@endsection
