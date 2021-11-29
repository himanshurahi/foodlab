<header class="header-section  @if(request()->routeIs('home')) @else position-relative box-shadow @endif">
    <div class="header">
        <div class="header-bottom-area">
            <div class="container-fluid">
                <div class="header-menu-content">
                    <nav class="navbar navbar-expand-lg p-0">
                        <a class="site-logo site-title" href="{{route('home')}}"><img src="{{ getImage(imagePath()['logoIcon']['path'] .'/logo.png') }}" alt="site-logo"></a>
                        <div class="language-select-area d-block d-lg-none ms-auto">
                            <select class="language-select langSel">
                                @foreach($language as $item)
                                    <option value="{{ __($item->code) }}" @if(session('lang') == $item->code) selected  @endif>{{ __($item->code) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fas fa-bars"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav main-menu ms-auto me-auto">
                                <li><a href="{{route('home')}}">@lang('Home')</a></li>
                                <li><a href="{{route('latest.restaurants')}}">@lang('Restaurants')</a></li>
                                @foreach($pages as $k => $data)
                                    <li><a href="{{route('pages',[$data->slug])}}">{{__($data->name)}}</a></li>
                                @endforeach
                                <li><a href="{{route('blogs')}}">@lang('Blogs')</a></li>
                                <li><a href="{{route('contact')}}">@lang('Contact')</a></li>
                            </ul>
                            <div class="language-select-area d-none d-xl-block">
                                <select class="language-select langSel">
                                    @foreach($language as $item)
                                        <option value="{{ __($item->code) }}" @if(session('lang') == $item->code) selected  @endif>{{ __($item->code) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @auth
                                <div class="header-right dropdown">
                                    <button type="button" data-bs-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                                        <div class="header-user-area d-flex flex-wrap align-items-center justify-content-between">
                                            <div class="header-user-thumb">
                                                <a href="javascript:void(0)"><img src="{{ getImage(imagePath()['profile']['user']['path'].'/'. auth()->user()->image,imagePath()['profile']['user']['size']) }}" alt="user"></a>
                                            </div>
                                            <div class="header-user-content">
                                                <span>{{auth()->user()->username}}</span>
                                            </div>
                                            <span class="header-user-icon"><i class="las la-chevron-circle-down"></i></span>
                                        </div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu--sm p-0 border-0 dropdown-menu-right">
                                        <a href="{{route('user.home')}}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                            <i class="dropdown-menu__icon las la-tachometer-alt"></i>
                                            <span class="dropdown-menu__caption">@lang('Dashboard')</span>
                                        </a>
                                        <a href="{{route('user.orders')}}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                            <i class="dropdown-menu__icon las la-utensils"></i>
                                            <span class="dropdown-menu__caption">@lang('Orders')</span>
                                        </a>
                                        <a href="{{ route('user.change.password') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                            <i class="dropdown-menu__icon las la-key"></i>
                                            <span class="dropdown-menu__caption">@lang('Change Password')</span>
                                        </a>
                                        <a href="{{route('user.profile.setting')}}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                            <i class="dropdown-menu__icon las la-user-circle"></i>
                                            <span class="dropdown-menu__caption">@lang('Profile Settings')</span>
                                        </a>
                                        <a href="{{ route('user.twofactor') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                            <i class="dropdown-menu__icon las la-lock"></i>
                                            <span class="dropdown-menu__caption">@lang('2FA Security')</span>
                                        </a>
                                        <a href="{{route('user.deposit')}}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                            <i class="dropdown-menu__icon las la-wallet"></i>
                                            <span class="dropdown-menu__caption">@lang('Make Balance')</span>
                                        </a>
                                        <a href="{{route('user.deposit.history')}}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                            <i class="dropdown-menu__icon las la-list"></i>
                                            <span class="dropdown-menu__caption">@lang('Balance Log')</span>
                                        </a>
                                        <a href="{{route('user.transactions')}}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                            <i class="dropdown-menu__icon las la-exchange-alt"></i>
                                            <span class="dropdown-menu__caption">@lang('Transactions')</span>
                                        </a>
                                        <a href="{{route('ticket')}}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                            <i class="dropdown-menu__icon las la-ticket-alt"></i>
                                            <span class="dropdown-menu__caption">@lang('Support Ticket')</span>
                                        </a>
                                        <a href="{{ route('user.logout') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                            <i class="dropdown-menu__icon las la-sign-out-alt"></i>
                                            <span class="dropdown-menu__caption">@lang('Logout')</span>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="header-action">
                                    <a href="{{route('user.register')}}" class="btn--base"><i class="las la-user-circle"></i>@lang('Register')</a>
                                    <a href="{{route('user.login')}}" class="btn--base"><i class="las la-user-circle"></i>@lang('Login')</a>
                                </div>
                            @endauth
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
