@extends($activeTemplate.'layouts.frontend')

@section('content')
@include($activeTemplate.'user.breadcrumb')

    <section class="table-section ptb-60">
        <div class="container">
            <div class="text-end mb-3">
                <a href="{{route('ticket.open')}}" class="btn btn--base">@lang('Create Ticket')</a>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-12">
                    <div class="table-area">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>@lang('Subject')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Priority')</th>
                                    <th>@lang('Last Reply')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($supports as $key => $support)
                                    <tr>
                                        <td data-label="@lang('Subject')"> <a href="{{ route('ticket.view', $support->ticket) }}" class="font-weight-bold"> [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>
                                        <td data-label="@lang('Status')">
                                            @if($support->status == 0)
                                                <span class="badge badge--success">@lang('Open')</span>
                                            @elseif($support->status == 1)
                                                <span class="badge badge--primary">@lang('Answered')</span>
                                            @elseif($support->status == 2)
                                                <span class="badge badge--warning">@lang('Customer Reply')</span>
                                            @elseif($support->status == 3)
                                                <span class="badge badge--danger">@lang('Closed')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Priority')">
                                            @if($support->priority == 1)
                                                <span class="badge badge--danger">@lang('Low')</span>
                                            @elseif($support->priority == 2)
                                                <span class="badge badge--success">@lang('Medium')</span>
                                            @elseif($support->priority == 3)
                                                <span class="badge badge--primary">@lang('High')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Last Reply')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                                        <td data-label="@lang('Action')">
                                            <a href="{{ route('ticket.view', $support->ticket) }}" class="btn btn--base">
                                                <i class="fa fa-desktop"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">{{__($emptyMessage)}}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                    {{$supports->links()}}
                </div>
            </div>
        </div>
    </section>
@endsection

