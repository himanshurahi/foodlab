<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Rules\FileTypeValidate;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Lib\GoogleAuthenticator;
use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Food;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class RestaurantController extends Controller
{
    use ResetsPasswords;

    public function dashboard()
    {
        $pageTitle = 'Dashboard';
        $totalCategory = Category::where('restaurant_id',auth()->guard('restaurant')->user()->id)->count();
        $totalFood = Food::where('restaurant_id',auth()->guard('restaurant')->user()->id)->count();
        $totalPendingOrders = Order::where('restaurant_id',auth()->guard('restaurant')->user()->id)->where('status',1)->count();
        $totalBalance = auth()->guard('restaurant')->user()->balance;

        // Monthly Deposit & Withdraw Report Graph
        $report['months'] = collect([]);
        $report['order_month_amount'] = collect([]);


        $ordersMonth = Order::where('restaurant_id', auth()->guard('restaurant')->user()->id)->where('created_at', '>=', Carbon::now()->subYear())
            ->where('status', 2)
            ->selectRaw("SUM( CASE WHEN status = 2 THEN total END) as orderAmount")
            ->selectRaw("DATE_FORMAT(created_at,'%M-%Y') as months")
            ->orderBy('created_at')
            ->groupBy('months')
            ->get();

        $ordersMonth->map(function ($orderData) use ($report) {
            $report['months']->push($orderData->months);
            $report['order_month_amount']->push(showAmount($orderData->orderAmount));
        });

        $months = $report['months'];

        for($i = 0; $i < $months->count(); ++$i) {
            $monthVal      = Carbon::parse($months[$i]);
            if(isset($months[$i+1])){
                $monthValNext = Carbon::parse($months[$i+1]);
                if($monthValNext < $monthVal){
                    $temp = $months[$i];
                    $months[$i]   = Carbon::parse($months[$i+1])->format('F-Y');
                    $months[$i+1] = Carbon::parse($temp)->format('F-Y');
                }else{
                    $months[$i]   = Carbon::parse($months[$i])->format('F-Y');
                }
            }
        }

        return view('restaurant.dashboard',compact('pageTitle','totalCategory','totalFood','totalPendingOrders','totalBalance','ordersMonth','months'));
    }

    public function profile()
    {
        $pageTitle = 'Profile';
        $restaurant = Auth::guard('restaurant')->user();
        return view('restaurant.profile', compact('pageTitle', 'restaurant'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'address' => 'sometimes|required|max:80',
            'state' => 'sometimes|required|max:80',
            'zip' => 'sometimes|required|max:40',
            'city' => 'sometimes|required|max:50',
            'open_time' => 'required|max:40',
            'close_time' => 'required|max:40',
            'days'  => 'required|array|min:1',
            'days.*' => 'required|in:1,2,3,4,5,6,7',
            'd_charge' => 'required|numeric',
            'd_time' => 'required|integer|max:999|gt:0',
            'vat' => 'required|numeric|max:100',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])]
        ],[
            'firstname.required'=>'First name field is required',
            'lastname.required'=>'Last name field is required',
            'days.required'=>'You must have to select at least one day',
            'days.*.in'=>'You must have to select within provided days',
            'd_time.max'=>'Delivery should not be more than 999 minutes',
            'd_time.gt'=>'Delivery time should be more than 0 minute',
        ]);

        $restaurant =  Auth::guard('restaurant')->user();

        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;

        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$restaurant->address->country,
            'city' => $request->city,
        ];
        $in['open_time'] = showDateTime($request->open_time,'h:i a');
        $in['close_time'] = showDateTime($request->close_time,'h:i a');
        $in['days'] = $request->days;
        $in['d_charge'] = $request->d_charge;
        $in['d_time'] = $request->d_time;
        $in['vat'] = $request->vat;

        if ($request->hasFile('image')) {
            $location = imagePath()['profile']['restaurant']['path'];
            $size = imagePath()['profile']['restaurant']['size'];
            $filename = uploadImage($request->image, $location, $size, $restaurant->image);
            $in['image'] = $filename;
        }

        $restaurant->fill($in)->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('restaurant.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $restaurant = Auth::guard('restaurant')->user();
        return view('restaurant.password', compact('pageTitle', 'restaurant'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $request->validate($this->rules(), $this->validationErrorMessages());

        $restaurant = Auth::guard('restaurant')->user();
        if (!Hash::check($request->old_password, $restaurant->password)) {
            $notify[] = ['error', 'Old password do not match !!'];
            return back()->withNotify($notify);
        }
        $restaurant->password = bcrypt($request->password);
        $restaurant->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return redirect()->route('restaurant.password')->withNotify($notify);
    }

    protected function rules()
    {
        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        return [
            'password' => ['required','confirmed',$password_validation],
        ];
    }

    public function show2faForm()
    {
        $general = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $restaurant = auth()->guard('restaurant')->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($restaurant->username . '@' . $general->sitename, $secret);
        $pageTitle = 'Two Factor';
        return view('restaurant.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $restaurant = auth()->guard('restaurant')->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($restaurant,$request->code,$request->key);
        if ($response) {
            $restaurant->tsc = $request->key;
            $restaurant->ts = 1;
            $restaurant->save();
            $restaurantAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($restaurant, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$restaurantAgent['ip'],
                'time' => @$restaurantAgent['time']
            ]);
            $notify[] = ['success', 'Google authenticator enabled successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $restaurant = auth()->guard('restaurant')->user();
        $response = verifyG2fa($restaurant,$request->code);
        if ($response) {
            $restaurant->tsc = null;
            $restaurant->ts = 0;
            $restaurant->save();
            $restaurantAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($restaurant, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$restaurantAgent['ip'],
                'time' => @$restaurantAgent['time']
            ]);
            $notify[] = ['success', 'Two factor authenticator disable successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function pendingOrders()
    {
        $pageTitle = 'Pending Orders';
        $emptyMessage = 'No pending orders';

        $orders = Order::where('restaurant_id',auth()->guard('restaurant')->user()->id)->where('status',1)->paginate(getPaginate());
        return view('restaurant.orders',compact('pageTitle','orders','emptyMessage'));
    }

    public function deliveredOrders()
    {
        $pageTitle = 'Delivered Orders';
        $emptyMessage = 'No delivered orders';

        $orders = Order::where('restaurant_id',auth()->guard('restaurant')->user()->id)->where('status',2)->paginate(getPaginate());
        return view('restaurant.orders',compact('pageTitle','orders','emptyMessage'));
    }

    public function canceledOrders()
    {
        $pageTitle = 'Canceled Orders';
        $emptyMessage = 'No canceled orders';

        $orders = Order::where('restaurant_id',auth()->guard('restaurant')->user()->id)->where('status',3)->paginate(getPaginate());
        return view('restaurant.orders',compact('pageTitle','orders','emptyMessage'));
    }

    public function orderSearch(Request $request)
    {
        $search = $request->search;
        $pageTitle = 'Order Search - ' . $search;
        $emptyMessage = 'No order found';
        $orders = Order::where('restaurant_id',auth()->guard('restaurant')->user()->id)->where('status', '!=', 0)->where('order_code',$search)->paginate(getPaginate());

        return view('restaurant.orders',compact('pageTitle','orders','emptyMessage', 'search'));
    }

    public function orderDetails($id)
    {
        try {
            $orderId = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            $notify[] = ['error', 'You can not proceed this action!'];
            return redirect()->route('home')->withNotify($notify);
        }

        $pageTitle = 'Order Details';
        $order = Order::where('restaurant_id', auth()->guard('restaurant')->user()->id)->with('details.food')->findOrFail($orderId);

        return view('restaurant.order_details',compact('pageTitle','order'));
    }

    public function withdrawMoney()
    {
        $withdrawMethod = WithdrawMethod::where('status',1)->get();
        $pageTitle = 'Withdraw Money';
        return view('restaurant.withdraw.methods', compact('pageTitle','withdrawMethod'));
    }

    public function withdrawStore(Request $request)
    {
        $this->validate($request, [
            'method_code' => 'required',
            'amount' => 'required|numeric'
        ]);
        $method = WithdrawMethod::where('id', $request->method_code)->where('status', 1)->firstOrFail();
        $restaurant = auth()->guard('restaurant')->user();
        if ($request->amount < $method->min_limit) {
            $notify[] = ['error', 'Your requested amount is smaller than minimum amount.'];
            return back()->withNotify($notify);
        }
        if ($request->amount > $method->max_limit) {
            $notify[] = ['error', 'Your requested amount is larger than maximum amount.'];
            return back()->withNotify($notify);
        }

        if ($request->amount > $restaurant->balance) {
            $notify[] = ['error', 'You do not have sufficient balance for withdraw.'];
            return back()->withNotify($notify);
        }


        $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = $afterCharge * $method->rate;

        $withdraw = new Withdrawal();
        $withdraw->method_id = $method->id; // wallet method ID
        $withdraw->restaurant_id = $restaurant->id;
        $withdraw->amount = $request->amount;
        $withdraw->currency = $method->currency;
        $withdraw->rate = $method->rate;
        $withdraw->charge = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx = getTrx();
        $withdraw->save();
        session()->put('wtrx', $withdraw->trx);
        return redirect()->route('restaurant.withdraw.preview');
    }

    public function withdrawPreview()
    {
        $withdraw = Withdrawal::with('method','restaurant')->where('user_id',null)->where('restaurant_id','!=' ,null)->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id','desc')->firstOrFail();
        $pageTitle = 'Withdraw Preview';

        return view('restaurant.withdraw.preview', compact('pageTitle','withdraw'));
    }

    public function withdrawSubmit(Request $request)
    {
        $general = GeneralSetting::first();
        $withdraw = Withdrawal::with('method','restaurant')->where('user_id',null)->where('restaurant_id','!=' ,null)->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id','desc')->firstOrFail();

        $rules = [];
        $inputField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($withdraw->method->user_data as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], new FileTypeValidate(['jpg','jpeg','png']));
                    array_push($rules[$key], 'max:2048');
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }

        $this->validate($request, $rules);

        $restaurant = auth()->guard('restaurant')->user();
        if ($restaurant->ts) {
            $response = verifyG2fa($restaurant,$request->authenticator_code);
            if (!$response) {
                $notify[] = ['error', 'Wrong verification code'];
                return back()->withNotify($notify);
            }
        }


        if ($withdraw->amount > $restaurant->balance) {
            $notify[] = ['error', 'Your request amount is larger then your current balance.'];
            return back()->withNotify($notify);
        }

        $directory = date("Y")."/".date("m")."/".date("d");
        $path = imagePath()['verify']['withdraw']['path'].'/'.$directory;
        $collection = collect($request);
        $reqField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($collection as $k => $v) {
                foreach ($withdraw->method->user_data as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $directory.'/'.uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    $notify[] = ['error', 'Could not upload your ' . $request[$inKey]];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $withdraw['withdraw_information'] = $reqField;
        } else {
            $withdraw['withdraw_information'] = null;
        }


        $withdraw->status = 2;
        $withdraw->save();
        $restaurant->balance  -=  $withdraw->amount;
        $restaurant->save();



        $transaction = new Transaction();
        $transaction->restaurant_id = $withdraw->restaurant_id;
        $transaction->amount = $withdraw->amount;
        $transaction->post_balance = $restaurant->balance;
        $transaction->charge = $withdraw->charge;
        $transaction->trx_type = '-';
        $transaction->details = showAmount($withdraw->final_amount) . ' ' . $withdraw->currency . ' Withdraw Via ' . $withdraw->method->name;
        $transaction->trx =  $withdraw->trx;
        $transaction->save();

        $adminNotification = new AdminNotification();
        $adminNotification->restaurant_id = $restaurant->id;
        $adminNotification->title = 'New withdraw request from '.$restaurant->username;
        $adminNotification->click_url = urlPath('admin.withdraw.details',$withdraw->id);
        $adminNotification->save();

        notify($restaurant, 'WITHDRAW_REQUEST', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => showAmount($withdraw->final_amount),
            'amount' => showAmount($withdraw->amount),
            'charge' => showAmount($withdraw->charge),
            'currency' => $general->cur_text,
            'rate' => showAmount($withdraw->rate),
            'trx' => $withdraw->trx,
            'post_balance' => showAmount($restaurant->balance),
            'delay' => $withdraw->method->delay
        ]);

        $notify[] = ['success', 'Withdraw request sent successfully'];
        return redirect()->route('restaurant.withdraw.history')->withNotify($notify);
    }

    public function withdrawLog()
    {
        $pageTitle = "Withdraw Log";
        $withdraws = Withdrawal::where('restaurant_id', '!=', null)->where('restaurant_id', auth()->guard('restaurant')->user()->id)->where('status', '!=', 0)->with('method')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = "No Data Found!";
        return view('restaurant.withdraw.log', compact('pageTitle','withdraws'));
    }

    public function transactions()
    {
        $pageTitle = 'Transactions';
        $transactions = Transaction::where('restaurant_id', '!=', null)->where('restaurant_id', auth()->guard('restaurant')->user()->id)->paginate(getPaginate());
        $emptyMessage = 'No transaction found';

        return view('restaurant.transactions', compact('pageTitle','transactions','emptyMessage'));
    }
}
