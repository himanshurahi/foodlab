<div class="sidebar {{ sidebarVariation()['selector'] }} {{ sidebarVariation()['sidebar'] }} {{ @sidebarVariation()['overlay'] }} {{ @sidebarVariation()['opacity'] }}"
     data-background="{{getImage('assets/admin/images/sidebar/2.jpg','400x800')}}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{route('restaurant.dashboard')}}" class="sidebar__main-logo"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('image')"></a>
            <a href="{{route('restaurant.dashboard')}}" class="sidebar__logo-shape"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" alt="@lang('image')"></a>
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{menuActive('restaurant.dashboard')}}">
                    <a href="{{route('restaurant.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('restaurant.category*')}}">
                    <a href="{{route('restaurant.category')}}" class="nav-link ">
                        <i class="menu-icon las la-utensils"></i>
                        <span class="menu-title">@lang('Manage Food')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('restaurant.twofactor*')}}">
                    <a href="{{ route('restaurant.twofactor') }}" class="nav-link ">
                        <i class="menu-icon las la-shield-alt"></i>
                        <span class="menu-title">@lang('2FA Security')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('restaurant.orders*',3)}}">
                        <i class="menu-icon las la-cart-arrow-down"></i>
                        <span class="menu-title">@lang('Orders')</span>
                        @if(0 < $pending_orders_count)
                            <span class="menu-badge pill bg--primary ml-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{menuActive('restaurant.orders*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('restaurant.orders.pending')}} ">
                                <a href="{{route('restaurant.orders.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending Orders')</span>
                                    @if($pending_orders_count)
                                        <span class="menu-badge pill bg--primary ml-auto">{{$pending_orders_count}}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('restaurant.orders.delivered')}} ">
                                <a href="{{route('restaurant.orders.delivered')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Delivered Orders')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('restaurant.orders.canceled')}} ">
                                <a href="{{route('restaurant.orders.canceled')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Canceled orders')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('restaurant.withdraw*',3)}}">
                        <i class="menu-icon la la-bank"></i>
                        <span class="menu-title">@lang('Withdrawals')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('restaurant.withdraw*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('restaurant.withdraw')}} ">
                                <a href="{{route('restaurant.withdraw')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Withdraw Money')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('restaurant.withdraw.history')}} ">
                                <a href="{{route('restaurant.withdraw.history')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Withdraw History')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item {{menuActive('restaurant.transactions*')}}">
                    <a href="{{route('restaurant.transactions')}}" class="nav-link ">
                        <i class="menu-icon las la-exchange-alt"></i>
                        <span class="menu-title">@lang('Transactions')</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- sidebar end -->
