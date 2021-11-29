<?php

namespace App\Http\Controllers\Restaurant\Auth;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Restaurant;
use App\Models\RestaurantPasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;


class ResetPasswordController extends Controller
{
    /*
        |--------------------------------------------------------------------------
        | Password Reset Controller
        |--------------------------------------------------------------------------
        |
        | This controller is responsible for handling password reset requests
        | and uses a simple trait to include this behavior. You're free to
        | explore this trait and override any methods you wish to tweak.
        |
        */

    use ResetsPasswords;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */


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
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token)
    {
        $pageTitle = "Account Recovery";
        $resetToken = RestaurantPasswordReset::where('token', $token)->where('status', 0)->first();

        if (!$resetToken) {
            $notify[] = ['error', 'Token not found!'];
            return redirect()->route('restaurant.password.reset')->withNotify($notify);
        }
        $email = $resetToken->email;
        return view('restaurant.auth.passwords.reset', compact('pageTitle', 'email', 'token'));
    }


    public function reset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        session()->put('fpass_email', $request->email);
        $request->validate($this->rules(), $this->validationErrorMessages());

        $reset = RestaurantPasswordReset::where('token', $request->token)->orderBy('created_at', 'desc')->first();
        $user = Restaurant::where('email', $reset->email)->first();
        if ($reset->status == 1) {
            $notify[] = ['error', 'Invalid code'];
            return redirect()->route('restaurant.login')->withNotify($notify);
        }

        $user->password = bcrypt($request->password);
        $user->save();
        $reset->status = 1;
        $reset->save();

        $userIpInfo = getIpInfo();
        $userBrowser = osBrowser();
        sendEmail($user, 'PASS_RESET_DONE', [
            'operating_system' => $userBrowser['os_platform'],
            'browser' => $userBrowser['browser'],
            'ip' => $userIpInfo['ip'],
            'time' => $userIpInfo['time']
        ]);

        $notify[] = ['success', 'Password changed'];
        return redirect()->route('restaurant.login')->withNotify($notify);
    }


    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required','confirmed',$password_validation],
        ];
    }


}
