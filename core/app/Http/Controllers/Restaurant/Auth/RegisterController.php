<?php
namespace App\Http\Controllers\Restaurant\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\GeneralSetting;
use App\Models\Location;
use App\Models\Restaurant;
use App\Models\RestaurantLogin;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    // protected $redirectTo = '/restaurant/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('restaurant.guest');
        $this->middleware('regStatus')->except('registrationNotAllowed');

        $this->activeTemplate = activeTemplate();
    }

    public function showRegistrationForm()
    {
        $pageTitle = "Sign Up";
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobile_code = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $locations = Location::where('status', 1)->latest()->get();
        return view('restaurant.auth.register', compact('pageTitle','mobile_code','countries','locations'));
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $general = GeneralSetting::first();
        $password_validation = Password::min(6);
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        $agree = 'nullable';
        if ($general->agree) {
            $agree = 'required';
        }
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes = implode(',',array_column($countryData, 'dial_code'));
        $countries = implode(',',array_column($countryData, 'country'));
        $validate = Validator::make($data, [
            'firstname' => 'sometimes|required|string|max:40',
            'lastname' => 'sometimes|required|string|max:40',
            'r_name' => 'required|required|string|max:191',
            'email' => 'required|string|email|max:40|unique:restaurants',
            'location_id' => 'required|integer|gt:0',
            'mobile' => 'required|string|max:40|unique:restaurants',
            'password' => ['required','confirmed',$password_validation],
            'username' => 'required|alpha_num|unique:restaurants|min:6|max:40',
            'captcha' => 'sometimes|required',
            'mobile_code' => 'required|in:'.$mobileCodes,
            'country_code' => 'required|in:'.$countryCodes,
            'country' => 'required|in:'.$countries,
            'agree' => $agree
        ]);
        return $validate;
    }

    public function register(Request $request)
    {
        $location = Location::findOrFail($request->location_id);

        $this->validator($request->all())->validate();
        $exist = Restaurant::where('mobile',$request->mobile_code.$request->mobile)->first();
        if ($exist) {
            $notify[] = ['error', 'The mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }

        if (isset($request->captcha)) {
            if (!captchaVerify($request->captcha, $request->captcha_secret)) {
                $notify[] = ['error', "Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }

        event(new Registered($restaurant = $this->create($request->all())));
        Auth::guard('restaurant')->login($restaurant);

        return redirect()->route('restaurant.dashboard');
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Restaurant
     */
    protected function create(array $data)
    {
        $general = GeneralSetting::first();


        $referBy = session()->get('reference');
        if ($referBy) {
            $referResraurant = Restaurant::where('username', $referBy)->first();
        } else {
            $referResraurant = null;
        }
        //Restaurant Create
        $restaurant = new Restaurant();
        $restaurant->firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $restaurant->lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $restaurant->r_name = isset($data['r_name']) ? $data['r_name'] : null;
        $restaurant->email = strtolower(trim($data['email']));
        $restaurant->location_id = isset($data['location_id']) ? $data['location_id'] : null;
        $restaurant->password = Hash::make($data['password']);
        $restaurant->username = trim($data['username']);
        $restaurant->ref_by = $referResraurant ? $referResraurant->id : 0;
        $restaurant->country_code = $data['country_code'];
        $restaurant->mobile = $data['mobile_code'].$data['mobile'];
        $restaurant->address = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => ''
        ];
        $restaurant->status = 1;
        $restaurant->ev = $general->ev ? 0 : 1;
        $restaurant->sv = $general->sv ? 0 : 1;
        $restaurant->ts = 0;
        $restaurant->tv = 1;
        $restaurant->save();


        $adminNotification = new AdminNotification();
        $adminNotification->restaurant_id = $restaurant->id;
        $adminNotification->title = 'New restaurant registered';
        $adminNotification->click_url = urlPath('admin.restaurants.detail',$restaurant->id);
        $adminNotification->save();


        //Login Log Create
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = RestaurantLogin::where('restaurant_ip',$ip)->first();
        $restaurantLogin = new RestaurantLogin();

        //Check exist or not
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


        return $restaurant;
    }

    public function checkRestaurant(Request $request)
    {
        $exist['data'] = null;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = Restaurant::where('email',$request->email)->first();
            $exist['type'] = 'email';
        }
        if ($request->mobile) {
            $exist['data'] = Restaurant::where('mobile',$request->mobile)->first();
            $exist['type'] = 'mobile';
        }
        if ($request->username) {
            $exist['data'] = Restaurant::where('username',$request->username)->first();
            $exist['type'] = 'username';
        }
        return response($exist);
    }

    public function registered()
    {
        //return redirect()->route('restaurant.dashboard');
    }

}
