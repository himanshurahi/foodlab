<?php

namespace App\Http\Controllers\Restaurant\Auth;

use App\Models\Restaurant;
use App\Models\RestaurantPasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
        |--------------------------------------------------------------------------
        | Password Reset Controller
        |--------------------------------------------------------------------------
        |
        | This controller is responsible for handling password reset emails and
        | includes a trait which assists in sending these notifications from
        | your application to your users. Feel free to explore this trait.
        |
        */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('restaurant.guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        $pageTitle = 'Account Recovery';
        return view('restaurant.auth.passwords.email', compact('pageTitle'));
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('restaurants');
    }

    public function sendResetCodeEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $restaurant = Restaurant::where('email', $request->email)->first();
        if (!$restaurant) {
            return back()->withErrors(['Email Not Available']);
        }

        $code = verificationCode(6);
        $restaurantPasswordReset = new RestaurantPasswordReset();
        $restaurantPasswordReset->email = $restaurant->email;
        $restaurantPasswordReset->token = $code;
        $restaurantPasswordReset->status = 0;
        $restaurantPasswordReset->created_at = date("Y-m-d h:i:s");
        $restaurantPasswordReset->save();

        $restaurantIpInfo = getIpInfo();
        $restaurantBrowser = osBrowser();
        sendEmail($restaurant, 'PASS_RESET_CODE', [
            'code' => $code,
            'operating_system' => $restaurantBrowser['os_platform'],
            'browser' => $restaurantBrowser['browser'],
            'ip' => $restaurantIpInfo['ip'],
            'time' => $restaurantIpInfo['time']
        ]);

        $pageTitle = 'Account Recovery';
        $notify[] = ['success', 'Password reset email sent successfully'];
        return view('restaurant.auth.passwords.code_verify', compact('pageTitle', 'notify'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required']);
        $notify[] = ['success', 'You can change your password.'];
        $code = str_replace(' ', '', $request->code);
        return redirect()->route('restaurant.password.reset.form', $code)->withNotify($notify);
    }
}
