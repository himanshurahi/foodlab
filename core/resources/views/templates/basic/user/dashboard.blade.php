@extends($activeTemplate.'layouts.frontend')
@section('content')

    <section class="dashboard-section ptb-80">
        <div class="container">
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="dashboard-item">
                        <a href="{{route('user.deposit.history')}}" class="dash-btn">@lang('View all')</a>
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-wallet"></i>
                            </div>
                            <h5 class="title">@lang('Total') <span class="text--base">@lang('Balance')</span></h5>
                            <h4 class="num mb-0">{{$general->cur_sym}} {{showAmount($totalBalance)}}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="dashboard-item">
                        <a href="{{route('user.orders')}}" class="dash-btn">@lang('View all')</a>
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-utensils"></i>
                            </div>
                            <h5 class="title">@lang('Total') <span class="text--base">@lang('Orders')</span></h5>
                            <h4 class="num mb-0">{{$totalOrders}}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="dashboard-item">
                        <a href="{{route('user.orders.pending')}}" class="dash-btn">@lang('View all')</a>
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-spinner"></i>
                            </div>
                            <h5 class="title">@lang('Orders') <span class="text--base">@lang('Pending')</span></h5>
                            <h4 class="num mb-0">{{$pendingOrders}}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="dashboard-item">
                        <a href="{{route('user.orders.confirmed')}}" class="dash-btn">@lang('View all')</a>
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-truck"></i>
                            </div>
                            <h5 class="title">@lang('Orders') <span class="text--base">@lang('Confirmed')</span></h5>
                            <h4 class="num mb-0">{{$confirmedOrders}}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="dashboard-item">
                        <a href="{{route('user.orders.delivered')}}" class="dash-btn">@lang('View all')</a>
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-clipboard-check"></i>
                            </div>
                            <h5 class="title">@lang('Orders') <span class="text--base">@lang('Delivered')</span></h5>
                            <h4 class="num mb-0">{{$deliveredOrders}}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="dashboard-item">
                        <a href="{{route('user.orders.canceled')}}" class="dash-btn">@lang('View all')</a>
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-times-circle"></i>
                            </div>
                            <h5 class="title">@lang('Orders') <span class="text--base">@lang('Canceled')</span></h5>
                            <h4 class="num mb-0">{{$canceledOrders}}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="dashboard-item">
                        <a href="{{route('user.transactions')}}" class="dash-btn">@lang('View all')</a>
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-exchange-alt"></i>
                            </div>
                            <h5 class="title">@lang('Total') <span class="text--base">@lang('Transaction')</span></h5>
                            <h4 class="num mb-0">{{$totalTransaction}}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="dashboard-item">
                        <a href="{{route('ticket')}}" class="dash-btn">@lang('View all')</a>
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-ticket-alt"></i>
                            </div>
                            <h5 class="title">@lang('Total') <span class="text--base">@lang('Total Ticket')</span></h5>
                            <h4 class="num mb-0">{{$totalTicket}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
