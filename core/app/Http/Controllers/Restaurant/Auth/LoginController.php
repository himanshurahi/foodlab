<?php
namespace App\Http\Controllers\Restaurant\Auth;

use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Models\Extension;
use App\Models\RestaurantLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = 'restaurant';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('restaurant.guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $pageTitle = "Restaurant Owner Login";
        return view('restaurant.auth.login', compact('pageTitle'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('restaurant');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {

        $this->validateLogin($request);

        if(isset($request->captcha)){
            if(!captchaVerify($request->captcha, $request->captcha_secret)){
                $notify[] = ['error',"Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }
        
        $lv = @getLatestVersion();
        $general = GeneralSetting::first();
        if (@systemDetails()['version'] < @json_decode($lv)->version) {
            $general->sys_version = $lv;
        } else {
            $general->sys_version = null;
        }
        $general->save();

//

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $customRecaptcha = Extension::where('act', 'custom-captcha')->where('status', 1)->first();
        $validation_rule = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        if ($customRecaptcha) {
            $validation_rule['captcha'] = 'required';
        }

        $request->validate($validation_rule);

    }


    public function logout(Request $request)
    {
        $this->guard('restaurant')->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('/restaurant');
    }

    public function resetPassword()
    {
        $pageTitle = 'Account Recovery';
        return view('restaurant.reset', compact('pageTitle'));
    }

    public function authenticated(Request $request, $restaurant)
    {
        if ($restaurant->status == 0) {
            $this->guard()->logout();
            $notify[] = ['error','Your account has been deactivated.'];
            return redirect()->route('restaurant.login')->withNotify($notify);
        }

        $restaurant = auth()->guard('restaurant')->user();
        $restaurant->tv = $restaurant->ts == 1 ? 0 : 1;
        $restaurant->save();
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = RestaurantLogin::where('restaurant_ip',$ip)->first();
        $restaurantLogin = new RestaurantLogin();

        if ($exist) {
            $restaurantLogin->longitude =  $exist->longitude;
            $restaurantLogin->latitude =  $exist->latitude;
            $restaurantLogin->city =  $exist->city;
            $restaurantLogin->country_code = $exist->country_code;
            $restaurantLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $restaurantLogin->longitude =  @implode(',',$info['long']);
            $restaurantLogin->latitude =  @implode(',',$info['lat']);
            $restaurantLogin->city =  @implode(',',$info['city']);
            $restaurantLogin->country_code = @implode(',',$info['code']);
            $restaurantLogin->country =  @implode(',', $info['country']);
        }

        $restaurantAgent = osBrowser();
        $restaurantLogin->restaurant_id = $restaurant->id;
        $restaurantLogin->restaurant_ip =  $ip;

        $restaurantLogin->browser = @$restaurantAgent['browser'];
        $restaurantLogin->os = @$restaurantAgent['os_platform'];
        $restaurantLogin->save();

        return redirect()->route('restaurant.dashboard');
    }
}
