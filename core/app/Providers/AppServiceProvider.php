<?php

namespace App\Providers;

use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\Order;
use App\Models\Page;
use App\Models\Restaurant;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['request']->server->set('HTTPS', true);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        $activeTemplate = activeTemplate();
        $general = GeneralSetting::first();
        $viewShare['general'] = $general;
        $viewShare['activeTemplate'] = $activeTemplate;
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        $viewShare['language'] = Language::all();
        $viewShare['pages'] = Page::where('tempname',$activeTemplate)->where('slug','!=','home')->where('is_default',0)->get();
        view()->share($viewShare);


        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'banned_users_count'           => User::banned()->count(),
                'email_unverified_users_count' => User::emailUnverified()->count(),
                'sms_unverified_users_count'   => User::smsUnverified()->count(),
                'pending_ticket_count'         => SupportTicket::whereIN('status', [0,2])->count(),
                'pending_deposits_count'    => Deposit::pending()->count(),
                'pending_withdraw_count'    => Withdrawal::pending()->count(),

                'banned_restaurants_count'           => Restaurant::banned()->count(),
                'email_unverified_restaurants_count' => Restaurant::emailUnverified()->count(),
                'sms_unverified_restaurants_count'   => Restaurant::smsUnverified()->count(),
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications'=>AdminNotification::where('read_status',0)->with('user','restaurant')->orderBy('id','desc')->get(),
            ]);
        });

        view()->composer('restaurant.partials.sidenav', function ($view) {
            $view->with([
                'pending_orders_count'    => Order::where('restaurant_id',auth()->guard('restaurant')->user()->id)->where('status',1)->count(),
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        if($general->force_ssl){
            \URL::forceScheme('https');
        }


        Paginator::useBootstrap();

    }
}
