<?php

namespace App\Http\Controllers\Restaurant;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;


class RestaurantAuthorizationController extends Controller
{
    public function __construct()
    {
        return $this->activeTemplate = activeTemplate();
    }
    public function checkValidCode($restaurant, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$restaurant->ver_code_send_at) return false;
        if ($restaurant->ver_code_send_at->addMinutes($add_min) < Carbon::now()) return false;
        if ($restaurant->ver_code !== $code) return false;
        return true;
    }


    public function authorizeForm()
    {
        if (auth()->guard('restaurant')->check()) {
            $restaurant = Auth::guard('restaurant')->user();
            if (!$restaurant->status) {
                Auth::logout();
            }elseif (!$restaurant->ev) {
                if (!$this->checkValidCode($restaurant, $restaurant->ver_code)) {
                    $restaurant->ver_code = verificationCode(6);
                    $restaurant->ver_code_send_at = Carbon::now();
                    $restaurant->save();
                    sendEmail($restaurant, 'EVER_CODE', [
                        'code' => $restaurant->ver_code
                    ]);
                }
                $pageTitle = 'Email verification form';
                return view('restaurant.auth.authorization.email', compact('restaurant', 'pageTitle'));
            }elseif (!$restaurant->sv) {
                if (!$this->checkValidCode($restaurant, $restaurant->ver_code)) {
                    $restaurant->ver_code = verificationCode(6);
                    $restaurant->ver_code_send_at = Carbon::now();
                    $restaurant->save();
                    sendSms($restaurant, 'SVER_CODE', [
                        'code' => $restaurant->ver_code
                    ]);
                }
                $pageTitle = 'SMS verification form';
                return view('restaurant.auth.authorization.sms', compact('restaurant', 'pageTitle'));
            }elseif (!$restaurant->tv) {
                $pageTitle = 'Google Authenticator';
                return view('restaurant.auth.authorization.2fa', compact('restaurant', 'pageTitle'));
            }else{
                return redirect()->route('restaurant.dashboard');
            }

        }

        return redirect()->route('restaurant.login');
    }

    public function sendVerifyCode(Request $request)
    {
        $restaurant = Auth::guard('restaurant')->user();


        if ($this->checkValidCode($restaurant, $restaurant->ver_code, 2)) {
            $target_time = $restaurant->ver_code_send_at->addMinutes(2)->timestamp;
            $delay = $target_time - time();
            throw ValidationException::withMessages(['resend' => 'Please Try after ' . $delay . ' Seconds']);
        }
        if (!$this->checkValidCode($restaurant, $restaurant->ver_code)) {
            $restaurant->ver_code = verificationCode(6);
            $restaurant->ver_code_send_at = Carbon::now();
            $restaurant->save();
        } else {
            $restaurant->ver_code = $restaurant->ver_code;
            $restaurant->ver_code_send_at = Carbon::now();
            $restaurant->save();
        }



        if ($request->type === 'email') {
            sendEmail($restaurant, 'EVER_CODE',[
                'code' => $restaurant->ver_code
            ]);

            $notify[] = ['success', 'Email verification code sent successfully'];
            return back()->withNotify($notify);
        } elseif ($request->type === 'phone') {
            sendSms($restaurant, 'SVER_CODE', [
                'code' => $restaurant->ver_code
            ]);
            $notify[] = ['success', 'SMS verification code sent successfully'];
            return back()->withNotify($notify);
        } else {
            throw ValidationException::withMessages(['resend' => 'Sending Failed']);
        }
    }

    public function emailVerification(Request $request)
    {
        $request->validate([
            'email_verified_code'=>'required'
        ]);


        $email_verified_code = str_replace(' ','',$request->email_verified_code);
        $restaurant = Auth::guard('restaurant')->user();

        if ($this->checkValidCode($restaurant, $email_verified_code)) {
            $restaurant->ev = 1;
            $restaurant->ver_code = null;
            $restaurant->ver_code_send_at = null;
            $restaurant->save();
            return redirect()->route('restaurant.dashboard');
        }
        throw ValidationException::withMessages(['email_verified_code' => 'Verification code didn\'t match!']);
    }

    public function smsVerification(Request $request)
    {
        $request->validate([
            'sms_verified_code' => 'required',
        ]);


        $sms_verified_code =  str_replace(' ','',$request->sms_verified_code);

        $restaurant = Auth::guard('restaurant')->user();
        if ($this->checkValidCode($restaurant, $sms_verified_code)) {
            $restaurant->sv = 1;
            $restaurant->ver_code = null;
            $restaurant->ver_code_send_at = null;
            $restaurant->save();
            return redirect()->route('restaurant.dashboard');
        }
        throw ValidationException::withMessages(['sms_verified_code' => 'Verification code didn\'t match!']);
    }
    public function g2faVerification(Request $request)
    {
        $restaurant = Auth::guard('restaurant')->user();
        $request->validate([
            'code' => 'required',
        ]);
        $code = str_replace(' ','',$request->code);
        $response = verifyG2fa($restaurant,$code);
        if ($response) {
            $notify[] = ['success','Verification successful'];
        }else{
            $notify[] = ['error','Wrong verification code'];
        }
        return back()->withNotify($notify);
    }
}
