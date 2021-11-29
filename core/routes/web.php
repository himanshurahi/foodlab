<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::namespace('Gateway')->prefix('ipn')->name('ipn.')->group(function () {
    Route::post('paypal', 'Paypal\ProcessController@ipn')->name('Paypal');
    Route::get('paypal-sdk', 'PaypalSdk\ProcessController@ipn')->name('PaypalSdk');
    Route::post('perfect-money', 'PerfectMoney\ProcessController@ipn')->name('PerfectMoney');
    Route::post('stripe', 'Stripe\ProcessController@ipn')->name('Stripe');
    Route::post('stripe-js', 'StripeJs\ProcessController@ipn')->name('StripeJs');
    Route::post('stripe-v3', 'StripeV3\ProcessController@ipn')->name('StripeV3');
    Route::post('skrill', 'Skrill\ProcessController@ipn')->name('Skrill');
    Route::post('paytm', 'Paytm\ProcessController@ipn')->name('Paytm');
    Route::post('payeer', 'Payeer\ProcessController@ipn')->name('Payeer');
    Route::post('paystack', 'Paystack\ProcessController@ipn')->name('Paystack');
    Route::post('voguepay', 'Voguepay\ProcessController@ipn')->name('Voguepay');
    Route::get('flutterwave/{trx}/{type}', 'Flutterwave\ProcessController@ipn')->name('Flutterwave');
    Route::post('razorpay', 'Razorpay\ProcessController@ipn')->name('Razorpay');
    Route::post('instamojo', 'Instamojo\ProcessController@ipn')->name('Instamojo');
    Route::get('blockchain', 'Blockchain\ProcessController@ipn')->name('Blockchain');
    Route::get('blockio', 'Blockio\ProcessController@ipn')->name('Blockio');
    Route::post('coinpayments', 'Coinpayments\ProcessController@ipn')->name('Coinpayments');
    Route::post('coinpayments-fiat', 'Coinpayments_fiat\ProcessController@ipn')->name('CoinpaymentsFiat');
    Route::post('coingate', 'Coingate\ProcessController@ipn')->name('Coingate');
    Route::post('coinbase-commerce', 'CoinbaseCommerce\ProcessController@ipn')->name('CoinbaseCommerce');
    Route::get('mollie', 'Mollie\ProcessController@ipn')->name('Mollie');
    Route::post('cashmaal', 'Cashmaal\ProcessController@ipn')->name('Cashmaal');
    Route::post('authorize-net', 'AuthorizeNet\ProcessController@ipn')->name('AuthorizeNet');
    Route::post('2check-out', 'TwoCheckOut\ProcessController@ipn')->name('TwoCheckOut');
    Route::post('mercado-pago', 'MercadoPago\ProcessController@ipn')->name('MercadoPago');
});

// User Support Ticket
Route::prefix('ticket')->group(function () {
    Route::get('/', 'TicketController@supportTicket')->name('ticket');
    Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
    Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
    Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
});


/*
|--------------------------------------------------------------------------
| Start Admin Area
|--------------------------------------------------------------------------
*/

Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');

        // Admin Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetCodeEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify.code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::get('profile', 'AdminController@profile')->name('profile');
        Route::post('profile', 'AdminController@profileUpdate')->name('profile.update');
        Route::get('password', 'AdminController@password')->name('password');
        Route::post('password', 'AdminController@passwordUpdate')->name('password.update');

        // Notification
        Route::get('notifications','AdminController@notifications')->name('notifications');
        Route::get('notification/read/{id}','AdminController@notificationRead')->name('notification.read');
        Route::get('notifications/read-all','AdminController@readAll')->name('notifications.readAll');

        //Report Bugs
        Route::get('request-report','AdminController@requestReport')->name('request.report');
        Route::post('request-report','AdminController@reportSubmit');

        Route::get('system-info','AdminController@systemInfo')->name('system.info');

        // Manage Locations
        Route::get('locations', 'LocationController@index')->name('location.index');
        Route::post('location/store', 'LocationController@store')->name('location.store');
        Route::post('location/update/{id}', 'LocationController@update')->name('location.update');
        Route::get('location/search', 'LocationController@search')->name('location.search');


        // Manage Offer And Vouchar
        Route::get('vouchars', 'VoucharController@index')->name('vouchar.index');
        Route::post('vouchar/store', 'VoucharController@store')->name('vouchar.store');
        Route::post('vouchar/update/{id}', 'VoucharController@update')->name('vouchar.update');

        //Orders
        Route::get('pending-orders', 'OrderController@pendingOrders')->name('orders.pending');
        Route::get('delivered-orders', 'OrderController@deliveredOrders')->name('orders.delivered');
        Route::get('canceled-orders', 'OrderController@canceledOrders')->name('orders.canceled');
        Route::get('order-search', 'OrderController@orderSearch')->name('orders.search');
        Route::get('order-details/{id}', 'OrderController@orderDetails')->name('orders.details');
        Route::post('order-cancel', 'OrderController@orderCancel')->name('orders.cancel');

        // Users Manager
        Route::get('users', 'ManageUsersController@allUsers')->name('users.all');
        Route::get('users/active', 'ManageUsersController@activeUsers')->name('users.active');
        Route::get('users/banned', 'ManageUsersController@bannedUsers')->name('users.banned');
        Route::get('users/email-verified', 'ManageUsersController@emailVerifiedUsers')->name('users.email.verified');
        Route::get('users/email-unverified', 'ManageUsersController@emailUnverifiedUsers')->name('users.email.unverified');
        Route::get('users/sms-unverified', 'ManageUsersController@smsUnverifiedUsers')->name('users.sms.unverified');
        Route::get('users/sms-verified', 'ManageUsersController@smsVerifiedUsers')->name('users.sms.verified');
        Route::get('users/with-balance', 'ManageUsersController@usersWithBalance')->name('users.with.balance');

        Route::get('users/{scope}/search', 'ManageUsersController@search')->name('users.search');
        Route::get('user/detail/{id}', 'ManageUsersController@detail')->name('users.detail');
        Route::post('user/update/{id}', 'ManageUsersController@update')->name('users.update');
        Route::post('user/add-sub-balance/{id}', 'ManageUsersController@addSubBalance')->name('users.add.sub.balance');
        Route::get('user/send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('users.email.single');
        Route::post('user/send-email/{id}', 'ManageUsersController@sendEmailSingle')->name('users.email.single');
        Route::get('user/login/{id}', 'ManageUsersController@login')->name('users.login');
        Route::get('user/transactions/{id}', 'ManageUsersController@transactions')->name('users.transactions');
        Route::get('user/deposits/{id}', 'ManageUsersController@deposits')->name('users.deposits');
        Route::get('user/deposits/via/{method}/{type?}/{userId}', 'ManageUsersController@depositViaMethod')->name('users.deposits.method');
        Route::get('user/withdrawals/{id}', 'ManageUsersController@withdrawals')->name('users.withdrawals');
        Route::get('user/withdrawals/via/{method}/{type?}/{userId}', 'ManageUsersController@withdrawalsViaMethod')->name('users.withdrawals.method');

        // User Login History
        Route::get('users/login/history/{id}', 'ManageUsersController@userLoginHistory')->name('users.login.history.single');

        Route::get('users/send-email', 'ManageUsersController@showEmailAllForm')->name('users.email.all');
        Route::post('users/send-email', 'ManageUsersController@sendEmailAll')->name('users.email.send');
        Route::get('users/email-log/{id}', 'ManageUsersController@emailLog')->name('users.email.log');
        Route::get('users/email-details/{id}', 'ManageUsersController@emailDetails')->name('users.email.details');

        // Restaurants Manager
        Route::get('restaurants', 'ManageRestaurantsController@allRestaurants')->name('restaurants.all');
        Route::get('restaurants/active', 'ManageRestaurantsController@activeRestaurants')->name('restaurants.active');
        Route::get('restaurants/banned', 'ManageRestaurantsController@bannedRestaurants')->name('restaurants.banned');
        Route::get('restaurants/email-verified', 'ManageRestaurantsController@emailVerifiedRestaurants')->name('restaurants.email.verified');
        Route::get('restaurants/email-unverified', 'ManageRestaurantsController@emailUnverifiedRestaurants')->name('restaurants.email.unverified');
        Route::get('restaurants/sms-unverified', 'ManageRestaurantsController@smsUnverifiedRestaurants')->name('restaurants.sms.unverified');
        Route::get('restaurants/sms-verified', 'ManageRestaurantsController@smsVerifiedRestaurants')->name('restaurants.sms.verified');
        Route::get('restaurants/with-balance', 'ManageRestaurantsController@restaurantsWithBalance')->name('restaurants.with.balance');

        Route::get('restaurants/{scope}/search', 'ManageRestaurantsController@search')->name('restaurants.search');
        Route::get('restaurant/detail/{id}', 'ManageRestaurantsController@detail')->name('restaurants.detail');
        Route::post('restaurant/update/{id}', 'ManageRestaurantsController@update')->name('restaurants.update');
        Route::post('restaurant/add-sub-balance/{id}', 'ManageRestaurantsController@addSubBalance')->name('restaurants.add.sub.balance');
        Route::get('restaurant/send-email/{id}', 'ManageRestaurantsController@showEmailSingleForm')->name('restaurants.email.single');
        Route::post('restaurant/send-email/{id}', 'ManageRestaurantsController@sendEmailSingle')->name('restaurants.email.single');
        Route::get('restaurant/login/{id}', 'ManageRestaurantsController@login')->name('restaurants.login');
        Route::get('restaurant/transactions/{id}', 'ManageRestaurantsController@transactions')->name('restaurants.transactions');
        Route::get('restaurant/withdrawals/{id}', 'ManageRestaurantsController@withdrawals')->name('restaurants.withdrawals');
        Route::get('restaurant/withdrawals/via/{method}/{type?}/{userId}', 'ManageUsersController@withdrawalsViaMethod')->name('users.withdrawals.method');

        // Restaurants Login History
        Route::get('restaurants/login/history/{id}', 'ManageRestaurantsController@restaurantLoginHistory')->name('restaurants.login.history.single');

        Route::get('restaurants/send-email', 'ManageRestaurantsController@showEmailAllForm')->name('restaurants.email.all');
        Route::post('restaurants/send-email', 'ManageRestaurantsController@sendEmailAll')->name('restaurants.email.send');
        Route::get('restaurants/email-log/{id}', 'ManageRestaurantsController@emailLog')->name('restaurants.email.log');
        Route::get('restaurants/email-details/{id}', 'ManageRestaurantsController@emailDetails')->name('restaurants.email.details');

        // Restaurant Foods
        Route::get('restaurant/food-categories/{id}', 'ManageRestaurantsController@categories')->name('restaurants.categories');
        Route::post('restaurant/food-category/new/{id}', 'ManageRestaurantsController@storeCategory')->name('restaurants.category.store');
        Route::post('restaurant/food-category/update/{catId}/{restId}', 'ManageRestaurantsController@updateCategory')->name('restaurants.category.update');
        Route::get('category/search/{id}', 'ManageRestaurantsController@searchCategory')->name('restaurants.category.search');
        Route::post('restaurant/food-category/activate', 'ManageRestaurantsController@categoryActivate')->name('restaurants.category.activate');
        Route::post('restaurant/food-category/deactivate', 'ManageRestaurantsController@categoryDeactivate')->name('restaurants.category.deactivate');

        Route::get('restaurant/foods/{id}', 'ManageRestaurantsController@foods')->name('restaurants.foods');
        Route::post('restaurant/food/store/{id}', 'ManageRestaurantsController@foodStore')->name('restaurants.food.store');
        Route::post('restaurant/food/update/{id}', 'ManageRestaurantsController@foodUpdate')->name('restaurants.food.update');
        Route::post('restaurant/food/activate', 'ManageRestaurantsController@foodActivate')->name('restaurants.food.activate');
        Route::post('restaurant/food/deactivate', 'ManageRestaurantsController@foodDeactivate')->name('restaurants.food.deactivate');
        Route::get('restaurant/food/search/{id}', 'ManageRestaurantsController@foodSearch')->name('restaurants.food.search');

        // Subscriber
        Route::get('subscriber', 'SubscriberController@index')->name('subscriber.index');
        Route::get('subscriber/send-email', 'SubscriberController@sendEmailForm')->name('subscriber.sendEmail');
        Route::post('subscriber/remove', 'SubscriberController@remove')->name('subscriber.remove');
        Route::post('subscriber/send-email', 'SubscriberController@sendEmail')->name('subscriber.sendEmail');


        // Deposit Gateway
        Route::name('gateway.')->prefix('gateway')->group(function(){
            // Automatic Gateway
            Route::get('automatic', 'GatewayController@index')->name('automatic.index');
            Route::get('automatic/edit/{alias}', 'GatewayController@edit')->name('automatic.edit');
            Route::post('automatic/update/{code}', 'GatewayController@update')->name('automatic.update');
            Route::post('automatic/remove/{code}', 'GatewayController@remove')->name('automatic.remove');
            Route::post('automatic/activate', 'GatewayController@activate')->name('automatic.activate');
            Route::post('automatic/deactivate', 'GatewayController@deactivate')->name('automatic.deactivate');


            // Manual Methods
            Route::get('manual', 'ManualGatewayController@index')->name('manual.index');
            Route::get('manual/new', 'ManualGatewayController@create')->name('manual.create');
            Route::post('manual/new', 'ManualGatewayController@store')->name('manual.store');
            Route::get('manual/edit/{alias}', 'ManualGatewayController@edit')->name('manual.edit');
            Route::post('manual/update/{id}', 'ManualGatewayController@update')->name('manual.update');
            Route::post('manual/activate', 'ManualGatewayController@activate')->name('manual.activate');
            Route::post('manual/deactivate', 'ManualGatewayController@deactivate')->name('manual.deactivate');
        });


        // DEPOSIT SYSTEM
        Route::name('deposit.')->prefix('deposit')->group(function(){
            Route::get('/', 'DepositController@deposit')->name('list');
            Route::get('pending', 'DepositController@pending')->name('pending');
            Route::get('rejected', 'DepositController@rejected')->name('rejected');
            Route::get('approved', 'DepositController@approved')->name('approved');
            Route::get('successful', 'DepositController@successful')->name('successful');
            Route::get('details/{id}', 'DepositController@details')->name('details');

            Route::post('reject', 'DepositController@reject')->name('reject');
            Route::post('approve', 'DepositController@approve')->name('approve');
            Route::get('via/{method}/{type?}', 'DepositController@depositViaMethod')->name('method');
            Route::get('/{scope}/search', 'DepositController@search')->name('search');
            Route::get('date-search/{scope}', 'DepositController@dateSearch')->name('dateSearch');

        });


        // WITHDRAW SYSTEM
        Route::name('withdraw.')->prefix('withdraw')->group(function(){
            Route::get('pending', 'WithdrawalController@pending')->name('pending');
            Route::get('approved', 'WithdrawalController@approved')->name('approved');
            Route::get('rejected', 'WithdrawalController@rejected')->name('rejected');
            Route::get('log', 'WithdrawalController@log')->name('log');
            Route::get('via/{method_id}/{type?}', 'WithdrawalController@logViaMethod')->name('method');
            Route::get('{scope}/search', 'WithdrawalController@search')->name('search');
            Route::get('date-search/{scope}', 'WithdrawalController@dateSearch')->name('dateSearch');
            Route::get('details/{id}', 'WithdrawalController@details')->name('details');
            Route::post('approve', 'WithdrawalController@approve')->name('approve');
            Route::post('reject', 'WithdrawalController@reject')->name('reject');


            // Withdraw Method
            Route::get('method/', 'WithdrawMethodController@methods')->name('method.index');
            Route::get('method/create', 'WithdrawMethodController@create')->name('method.create');
            Route::post('method/create', 'WithdrawMethodController@store')->name('method.store');
            Route::get('method/edit/{id}', 'WithdrawMethodController@edit')->name('method.edit');
            Route::post('method/edit/{id}', 'WithdrawMethodController@update')->name('method.update');
            Route::post('method/activate', 'WithdrawMethodController@activate')->name('method.activate');
            Route::post('method/deactivate', 'WithdrawMethodController@deactivate')->name('method.deactivate');
        });

        // Report
        Route::get('report/transaction', 'ReportController@transaction')->name('report.transaction');
        Route::get('report/transaction/search', 'ReportController@transactionSearch')->name('report.transaction.search');
        Route::get('report/login/history', 'ReportController@loginHistory')->name('report.login.history');
        Route::get('report/login/ipHistory/{ip}', 'ReportController@loginIpHistory')->name('report.login.ipHistory');
        Route::get('report/email/history', 'ReportController@emailHistory')->name('report.email.history');


        // Admin Support
        Route::get('tickets', 'SupportTicketController@tickets')->name('ticket');
        Route::get('tickets/pending', 'SupportTicketController@pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'SupportTicketController@closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'SupportTicketController@answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'SupportTicketController@ticketReply')->name('ticket.view');
        Route::post('ticket/reply/{id}', 'SupportTicketController@ticketReplySend')->name('ticket.reply');
        Route::get('ticket/download/{ticket}', 'SupportTicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'SupportTicketController@ticketDelete')->name('ticket.delete');


        // Language Manager
        Route::get('/language', 'LanguageController@langManage')->name('language.manage');
        Route::post('/language', 'LanguageController@langStore')->name('language.manage.store');
        Route::post('/language/delete/{id}', 'LanguageController@langDel')->name('language.manage.del');
        Route::post('/language/update/{id}', 'LanguageController@langUpdate')->name('language.manage.update');
        Route::get('/language/edit/{id}', 'LanguageController@langEdit')->name('language.key');
        Route::post('/language/import', 'LanguageController@langImport')->name('language.importLang');



        Route::post('language/store/key/{id}', 'LanguageController@storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'LanguageController@deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'LanguageController@updateLanguageJson')->name('language.update.key');



        // General Setting
        Route::get('general-setting', 'GeneralSettingController@index')->name('setting.index');
        Route::post('general-setting', 'GeneralSettingController@update')->name('setting.update');
        Route::get('optimize', 'GeneralSettingController@optimize')->name('setting.optimize');

        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css','GeneralSettingController@customCss')->name('setting.custom.css');
        Route::post('custom-css','GeneralSettingController@customCssSubmit');


        //Cookie
        Route::get('cookie','GeneralSettingController@cookie')->name('setting.cookie');
        Route::post('cookie','GeneralSettingController@cookieSubmit');


        // Plugin
        Route::get('extensions', 'ExtensionController@index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'ExtensionController@update')->name('extensions.update');
        Route::post('extensions/activate', 'ExtensionController@activate')->name('extensions.activate');
        Route::post('extensions/deactivate', 'ExtensionController@deactivate')->name('extensions.deactivate');



        // Email Setting
        Route::get('email-template/global', 'EmailTemplateController@emailTemplate')->name('email.template.global');
        Route::post('email-template/global', 'EmailTemplateController@emailTemplateUpdate')->name('email.template.global');
        Route::get('email-template/setting', 'EmailTemplateController@emailSetting')->name('email.template.setting');
        Route::post('email-template/setting', 'EmailTemplateController@emailSettingUpdate')->name('email.template.setting');
        Route::get('email-template/index', 'EmailTemplateController@index')->name('email.template.index');
        Route::get('email-template/{id}/edit', 'EmailTemplateController@edit')->name('email.template.edit');
        Route::post('email-template/{id}/update', 'EmailTemplateController@update')->name('email.template.update');
        Route::post('email-template/send-test-mail', 'EmailTemplateController@sendTestMail')->name('email.template.test.mail');


        // SMS Setting
        Route::get('sms-template/global', 'SmsTemplateController@smsTemplate')->name('sms.template.global');
        Route::post('sms-template/global', 'SmsTemplateController@smsTemplateUpdate')->name('sms.template.global');
        Route::get('sms-template/setting','SmsTemplateController@smsSetting')->name('sms.templates.setting');
        Route::post('sms-template/setting', 'SmsTemplateController@smsSettingUpdate')->name('sms.template.setting');
        Route::get('sms-template/index', 'SmsTemplateController@index')->name('sms.template.index');
        Route::get('sms-template/edit/{id}', 'SmsTemplateController@edit')->name('sms.template.edit');
        Route::post('sms-template/update/{id}', 'SmsTemplateController@update')->name('sms.template.update');
        Route::post('email-template/send-test-sms', 'SmsTemplateController@sendTestSMS')->name('sms.template.test.sms');

        // SEO
        Route::get('seo', 'FrontendController@seoEdit')->name('seo');


        // Frontend
        Route::name('frontend.')->prefix('frontend')->group(function () {


            Route::get('templates', 'FrontendController@templates')->name('templates');
            Route::post('templates', 'FrontendController@templatesActive')->name('templates.active');


            Route::get('frontend-sections/{key}', 'FrontendController@frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'FrontendController@frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'FrontendController@frontendElement')->name('sections.element');
            Route::post('remove', 'FrontendController@remove')->name('remove');

            // Page Builder
            Route::get('manage-pages', 'PageBuilderController@managePages')->name('manage.pages');
            Route::post('manage-pages', 'PageBuilderController@managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'PageBuilderController@managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete', 'PageBuilderController@managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'PageBuilderController@manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'PageBuilderController@manageSectionUpdate')->name('manage.section.update');
        });
    });
});


/*
|--------------------------------------------------------------------------
| Start Restaurant Area
|--------------------------------------------------------------------------
*/

Route::namespace('Restaurant')->prefix('restaurant')->name('restaurant.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'RegisterController@register')->middleware('regStatus');
        Route::post('check-mail', 'RegisterController@checkRestaurant')->name('checkRestaurant');

        // Restaurant Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetCodeEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify.code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware('restaurant')->group(function () {
        Route::get('authorization', 'RestaurantAuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'RestaurantAuthorizationController@sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'RestaurantAuthorizationController@emailVerification')->name('verify.email');
        Route::post('verify-sms', 'RestaurantAuthorizationController@smsVerification')->name('verify.sms');
        Route::post('verify-g2fa', 'RestaurantAuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware(['checkRestaurantStatus'])->group(function () {
            Route::get('dashboard', 'RestaurantController@dashboard')->name('dashboard');
            Route::get('profile', 'RestaurantController@profile')->name('profile');
            Route::post('profile', 'RestaurantController@profileUpdate')->name('profile.update');
            Route::get('password', 'RestaurantController@password')->name('password');
            Route::post('password', 'RestaurantController@passwordUpdate')->name('password.update');

            //2FA
            Route::get('twofactor', 'RestaurantController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'RestaurantController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'RestaurantController@disable2fa')->name('twofactor.disable');

            //Manage Food Category
            Route::get('category', 'CategoryController@categories')->name('category');
            Route::post('category/new', 'CategoryController@storeCategory')->name('category.store');
            Route::post('category/update/{id}', 'CategoryController@updateCategory')->name('category.update');
            Route::get('category/search', 'CategoryController@searchCategory')->name('category.search');
            Route::post('category/activate', 'CategoryController@activate')->name('category.activate');
            Route::post('category/deactivate', 'CategoryController@deactivate')->name('category.deactivate');

            //Manage Food
            Route::get('food/{id}', 'FoodController@foods')->name('category.food');
            Route::post('food/store/{id}', 'FoodController@store')->name('category.food.store');
            Route::post('food/update/{id}', 'FoodController@update')->name('category.food.update');
            Route::post('food/activate', 'FoodController@activate')->name('category.food.activate');
            Route::post('food/deactivate', 'FoodController@deactivate')->name('category.food.deactivate');
            Route::get('food/search/{id}', 'FoodController@search')->name('category.food.search');

            //Orders
            Route::get('pending-orders', 'RestaurantController@pendingOrders')->name('orders.pending');
            Route::get('delivered-orders', 'RestaurantController@deliveredOrders')->name('orders.delivered');
            Route::get('canceled-orders', 'RestaurantController@canceledOrders')->name('orders.canceled');
            Route::get('order-search', 'RestaurantController@orderSearch')->name('orders.search');
            Route::get('order-details/{id}', 'RestaurantController@orderDetails')->name('orders.details');

            // Withdraw
            Route::get('/withdraw', 'RestaurantController@withdrawMoney')->name('withdraw');
            Route::post('/withdraw', 'RestaurantController@withdrawStore')->name('withdraw.money');
            Route::get('/withdraw/preview', 'RestaurantController@withdrawPreview')->name('withdraw.preview');
            Route::post('/withdraw/preview', 'RestaurantController@withdrawSubmit')->name('withdraw.submit');
            Route::get('/withdraw/history', 'RestaurantController@withdrawLog')->name('withdraw.history');

            // Withdraw
            Route::get('/transactions', 'RestaurantController@transactions')->name('transactions');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Start User Area
|--------------------------------------------------------------------------
*/


Route::name('user.')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register')->middleware('regStatus');
    Route::post('check-mail', 'Auth\RegisterController@checkUser')->name('checkUser');

    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetCodeEmail')->name('password.email');
    Route::get('password/code-verify', 'Auth\ForgotPasswordController@codeVerify')->name('password.code.verify');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/verify-code', 'Auth\ForgotPasswordController@verifyCode')->name('password.verify.code');
});

Route::name('user.')->prefix('user')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify.email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify.sms');
        Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware(['checkStatus'])->group(function () {
            Route::get('dashboard', 'UserController@home')->name('home');

            Route::get('profile-setting', 'UserController@profile')->name('profile.setting');
            Route::post('profile-setting', 'UserController@submitProfile');
            Route::get('change-password', 'UserController@changePassword')->name('change.password');
            Route::post('change-password', 'UserController@submitPassword');

            //2FA
            Route::get('twofactor', 'UserController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'UserController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'UserController@disable2fa')->name('twofactor.disable');


            // Deposit
            Route::any('/deposit', 'Gateway\PaymentController@deposit')->name('deposit');
            Route::post('deposit/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');
            Route::get('deposit/preview', 'Gateway\PaymentController@depositPreview')->name('deposit.preview');
            Route::get('deposit/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');
            Route::get('deposit/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
            Route::post('deposit/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
            Route::any('deposit/history', 'UserController@depositHistory')->name('deposit.history');

            // Withdraw
            Route::get('/withdraw', 'UserController@withdrawMoney')->name('withdraw');
            Route::post('/withdraw', 'UserController@withdrawStore')->name('withdraw.money');
            Route::get('/withdraw/preview', 'UserController@withdrawPreview')->name('withdraw.preview');
            Route::post('/withdraw/preview', 'UserController@withdrawSubmit')->name('withdraw.submit');
            Route::get('/withdraw/history', 'UserController@withdrawLog')->name('withdraw.history');

            // Orders
            Route::get('/all-orders', 'OrderController@orders')->name('orders');
            Route::get('/pending-orders', 'OrderController@pendingOrders')->name('orders.pending');
            Route::get('/confirmed-orders', 'OrderController@confirmedOrders')->name('orders.confirmed');
            Route::get('/delivered-orders', 'OrderController@deliveredOrders')->name('orders.delivered');
            Route::get('/canceled-orders', 'OrderController@canceledOrders')->name('orders.canceled');
            Route::get('/orders-details/{id}', 'OrderController@orderDetails')->name('order.details');
            Route::get('/orders/add', 'OrderController@addOrders')->name('orders.add');
            Route::get('/orders/sub', 'OrderController@subOrders')->name('orders.sub');

            //Checkout
            Route::get('/checkout', 'OrderController@checkout')->name('checkout');
            Route::post('/place-order', 'OrderController@placeOrder')->name('placeorder');

            //Vouchar Apply
            Route::get('/vouchar-apply', 'OrderController@voucharApply')->name('vouchar.apply');
            Route::get('/vouchar-remove', 'OrderController@voucharRemove')->name('vouchar.remove');

            //Confirm Delivery
            Route::get('/confirm-delivery/{id}', 'OrderController@confirmDelivery')->name('confirm.delivery');
            Route::post('/make-delivery-confirm', 'OrderController@makeDeliveryConfirm')->name('make.delivery.confirm');

            //Rating
            Route::post('rating', 'UserController@rating')->name('rating');

            //Transactions
            Route::get('transactions', 'UserController@transactions')->name('transactions');

        });
    });
});

// Cookie Accept
Route::get('/cookie/accept', 'SiteController@cookieAccept')->name('cookie.accept');

// Search
Route::get('/search', 'SiteController@search')->name('search');

// Our Restaurants
Route::get('/our-restaurants', 'SiteController@ourRestaurants')->name('our.restaurants');

// Latest Restaurants
Route::get('/latest-restaurants', 'SiteController@latestRestaurants')->name('latest.restaurants');

// Restaurant Details
Route::get('/restaurant/{id}/{slug}', 'SiteController@restaurantDetails')->name('restaurant.details');

// Subscriber Store
Route::post('subscriber', 'SiteController@subscriberStore')->name('subscriber.store');


// Policy
Route::get('/policy/{id}/{slug}', 'SiteController@policyDetails')->name('policy.details');

Route::get('/contact', 'SiteController@contact')->name('contact');
Route::post('/contact', 'SiteController@contactSubmit');
Route::get('/change/{lang?}', 'SiteController@changeLanguage')->name('lang');

Route::get('/cookie/accept', 'SiteController@cookieAccept')->name('cookie.accept');

// Blog
Route::get('blogs', 'SiteController@blogs')->name('blogs');
Route::get('blog/{slug}/{id}', 'SiteController@blogDetails')->name('blog.details');

Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholder.image');


Route::get('/{slug}', 'SiteController@pages')->name('pages');
Route::get('/', 'SiteController@index')->name('home');
