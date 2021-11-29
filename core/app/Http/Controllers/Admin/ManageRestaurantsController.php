<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\EmailLog;
use App\Models\Food;
use App\Models\GeneralSetting;
use App\Models\Location;
use App\Models\Restaurant;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageRestaurantsController extends Controller
{
    public function allRestaurants()
    {
        $pageTitle = 'Manage Restaurants';
        $emptyMessage = 'No restaurant found';
        $restaurants = Restaurant::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.restaurants.list', compact('pageTitle', 'emptyMessage', 'restaurants'));
    }

    public function activeRestaurants()
    {
        $pageTitle = 'Manage Active Restaurants';
        $emptyMessage = 'No active restaurant found';
        $restaurants = Restaurant::active()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.restaurants.list', compact('pageTitle', 'emptyMessage', 'restaurants'));
    }

    public function bannedRestaurants()
    {
        $pageTitle = 'Banned Restaurants';
        $emptyMessage = 'No banned restaurant found';
        $restaurants = Restaurant::banned()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.restaurants.list', compact('pageTitle', 'emptyMessage', 'restaurants'));
    }

    public function emailUnverifiedRestaurants()
    {
        $pageTitle = 'Email Unverified Restaurants';
        $emptyMessage = 'No email unverified restaurant found';
        $restaurants = Restaurant::emailUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.restaurants.list', compact('pageTitle', 'emptyMessage', 'restaurants'));
    }
    public function emailVerifiedRestaurants()
    {
        $pageTitle = 'Email Verified Restaurants';
        $emptyMessage = 'No email verified restaurant found';
        $restaurants = Restaurant::emailVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.restaurants.list', compact('pageTitle', 'emptyMessage', 'restaurants'));
    }


    public function smsUnverifiedRestaurants()
    {
        $pageTitle = 'SMS Unverified Restaurants';
        $emptyMessage = 'No sms unverified restaurant found';
        $restaurants = Restaurant::smsUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.restaurants.list', compact('pageTitle', 'emptyMessage', 'restaurants'));
    }


    public function smsVerifiedRestaurants()
    {
        $pageTitle = 'SMS Verified Restaurants';
        $emptyMessage = 'No sms verified restaurant found';
        $restaurants = Restaurant::smsVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.restaurants.list', compact('pageTitle', 'emptyMessage', 'restaurants'));
    }


    public function restaurantsWithBalance()
    {
        $pageTitle = 'restaurants with balance';
        $emptyMessage = 'No sms verified restaurant found';
        $restaurants = Restaurant::where('balance','!=',0)->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.restaurants.list', compact('pageTitle', 'emptyMessage', 'restaurants'));
    }

    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $restaurants = Restaurant::where(function ($restaurant) use ($search) {
            $restaurant->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        });

        $pageTitle = '';
        if ($scope == 'active') {
            $pageTitle = 'Active ';
            $restaurants = $restaurants->where('status', 1);
        }elseif($scope == 'banned'){
            $pageTitle = 'Banned';
            $restaurants = $restaurants->where('status', 0);
        }elseif($scope == 'emailUnverified'){
            $pageTitle = 'Email Unverified ';
            $restaurants = $restaurants->where('ev', 0);
        }elseif($scope == 'smsUnverified'){
            $pageTitle = 'SMS Unverified ';
            $restaurants = $restaurants->where('sv', 0);
        }elseif($scope == 'withBalance'){
            $pageTitle = 'With Balance ';
            $restaurants = $restaurants->where('balance','!=',0);
        }

        $restaurants = $restaurants->paginate(getPaginate());
        $pageTitle .= 'Restaurant Search - ' . $search;
        $emptyMessage = 'No search result found';
        return view('admin.restaurants.list', compact('pageTitle', 'search', 'scope', 'emptyMessage', 'restaurants'));
    }


    public function detail($id)
    {
        $pageTitle = 'Restaurants Detail';
        $restaurant = Restaurant::findOrFail($id);
        $totalFood = $restaurant->foods->count();
        $totalWithdraw = Withdrawal::where('restaurant_id',$restaurant->id)->where('status',1)->sum('amount');
        $totalTransaction = Transaction::where('restaurant_id',$restaurant->id)->count();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $locations = Location::latest()->get();
        return view('admin.restaurants.detail', compact('pageTitle', 'restaurant','totalWithdraw','totalTransaction','countries','locations','totalFood'));
    }


    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $request->validate([
            'firstname' => 'sometimes|required|string|max:40',
            'lastname' => 'sometimes|required|string|max:40',
            'email' => 'required|email|max:90|unique:restaurants,email,' . $restaurant->id,
            'mobile' => 'required|unique:restaurants,mobile,' . $restaurant->id,
            'country' => 'required',
            'open_time' => 'nullable|max:40',
            'close_time' => 'nullable|max:40',
            'days'  => 'nullable|array|min:1',
            'days.*' => 'required|in:1,2,3,4,5,6,7',
            'd_charge' => 'required|numeric',
            'd_time' => 'required|integer|max:999',
            'vat' => 'required|numeric|max:100',
            'r_name' => 'required|required|string|max:191',
            'location_id' => 'required|integer|gt:0',
        ],[
            'firstname.required'=>'First name field is required',
            'lastname.required'=>'Last name field is required',
            'days.required'=>'You must have to select at least one day',
            'days.*.in'=>'You must have to select within provided days',
            'd_time.max'=>'Delivery time should not be more than 999 minutes',
        ]);

        $countryCode = $request->country;
        $restaurant->mobile = $request->mobile;
        $restaurant->country_code = $countryCode;
        $restaurant->firstname = $request->firstname;
        $restaurant->lastname = $request->lastname;
        $restaurant->email = $request->email;
        $restaurant->r_name = $request->r_name;
        $restaurant->location_id = $request->location_id;
        $restaurant->open_time = showDateTime($request->open_time,'h:i a');
        $restaurant->close_time = showDateTime($request->close_time,'h:i a');
        $restaurant->days = $request->days;
        $restaurant->d_charge = $request->d_charge;
        $restaurant->d_time = $request->d_time;
        $restaurant->vat = $request->vat;
        $restaurant->address = [
                            'address' => $request->address,
                            'city' => $request->city,
                            'state' => $request->state,
                            'zip' => $request->zip,
                            'country' => @$countryData->$countryCode->country,
                        ];
        $restaurant->status = $request->status ? 1 : 0;
        $restaurant->ev = $request->ev ? 1 : 0;
        $restaurant->sv = $request->sv ? 1 : 0;
        $restaurant->ts = $request->ts ? 1 : 0;
        $restaurant->tv = $request->tv ? 1 : 0;
        $restaurant->featured = $request->featured ? 1 : 0;
        $restaurant->save();

        $notify[] = ['success', 'Restaurant detail has been updated'];
        return redirect()->back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate(['amount' => 'required|numeric|gt:0']);

        $restaurant = Restaurant::findOrFail($id);
        $amount = $request->amount;
        $general = GeneralSetting::first(['cur_text','cur_sym']);
        $trx = getTrx();

        if ($request->act) {
            $restaurant->balance += $amount;
            $restaurant->save();
            $notify[] = ['success', $general->cur_sym . $amount . ' has been added to ' . $restaurant->username . '\'s balance'];

            $transaction = new Transaction();
            $transaction->restaurant_id = $restaurant->id;
            $transaction->amount = $amount;
            $transaction->post_balance = $restaurant->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '+';
            $transaction->details = 'Added Balance Via Admin';
            $transaction->trx =  $trx;
            $transaction->save();

            notify($restaurant, 'BAL_ADD', [
                'trx' => $trx,
                'amount' => showAmount($amount),
                'currency' => $general->cur_text,
                'post_balance' => showAmount($restaurant->balance),
            ]);

        } else {
            if ($amount > $restaurant->balance) {
                $notify[] = ['error', $restaurant->username . '\'s has insufficient balance.'];
                return back()->withNotify($notify);
            }
            $restaurant->balance -= $amount;
            $restaurant->save();



            $transaction = new Transaction();
            $transaction->restaurant_id = $restaurant->id;
            $transaction->amount = $amount;
            $transaction->post_balance = $restaurant->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '-';
            $transaction->details = 'Subtract Balance Via Admin';
            $transaction->trx =  $trx;
            $transaction->save();


            notify($restaurant, 'BAL_SUB', [
                'trx' => $trx,
                'amount' => showAmount($amount),
                'currency' => $general->cur_text,
                'post_balance' => showAmount($restaurant->balance)
            ]);
            $notify[] = ['success', $general->cur_sym . $amount . ' has been subtracted from ' . $restaurant->username . '\'s balance'];
        }
        return back()->withNotify($notify);
    }

    public function showEmailSingleForm($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $pageTitle = 'Send Email To: ' . $restaurant->username;
        return view('admin.restaurants.email_single', compact('pageTitle', 'restaurant'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $restaurant = Restaurant::findOrFail($id);
        sendGeneralEmail($restaurant->email, $request->subject, $request->message, $restaurant->username);
        $notify[] = ['success', $restaurant->username . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function restaurantLoginHistory($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $pageTitle = 'Restaurant Login History - ' . $restaurant->username;
        $emptyMessage = 'No restaurant login found.';
        $login_logs = $restaurant->login_logs()->orderBy('id','desc')->with('restaurant')->paginate(getPaginate());
        return view('admin.restaurants.logins', compact('pageTitle', 'emptyMessage', 'login_logs'));
    }

    public function login($id){
        $restaurant = Restaurant::findOrFail($id);
        Auth::guard('restaurant')->login($restaurant);
        return redirect()->route('restaurant.dashboard');
    }

    public function transactions(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Search Restaurant Transactions : ' . $restaurant->username;
            $transactions = $restaurant->transactions()->where('trx', $search)->with('restaurant')->orderBy('id','desc')->paginate(getPaginate());
            $emptyMessage = 'No transactions';
            return view('admin.reports.transactions', compact('pageTitle', 'search', 'restaurant', 'transactions', 'emptyMessage'));
        }
        $pageTitle = 'Rrestaurant Transactions : ' . $restaurant->username;
        $transactions = $restaurant->transactions()->with('restaurant')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No transactions';
        return view('admin.reports.transactions', compact('pageTitle', 'restaurant', 'transactions', 'emptyMessage'));
    }

    public function withdrawals(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Search Restaurant Withdrawals : ' . $restaurant->username;
            $withdrawals = $restaurant->withdrawals()->where('trx', 'like',"%$search%")->orderBy('id','desc')->paginate(getPaginate());
            $emptyMessage = 'No withdrawals';
            return view('admin.withdraw.withdrawals', compact('pageTitle', 'restaurant', 'search', 'withdrawals', 'emptyMessage'));
        }
        $pageTitle = 'Restaurant Withdrawals : ' . $restaurant->username;
        $withdrawals = $restaurant->withdrawals()->orderBy('id','desc')->with('restaurant')->paginate(getPaginate());
        $emptyMessage = 'No withdrawals';
        $restaurantId = $restaurant->id;
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'restaurant', 'withdrawals', 'emptyMessage','restaurantId'));
    }

    public  function withdrawalsViaMethod($method,$type,$restaurantId){
        $method = WithdrawMethod::findOrFail($method);
        $restaurant = Restaurant::findOrFail($restaurantId);
        if ($type == 'approved') {
            $pageTitle = 'Approved Withdrawal of '.$restaurant->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', 1)->where('user_id',$restaurant->id)->with(['user','method'])->orderBy('id','desc')->paginate(getPaginate());
        }elseif($type == 'rejected'){
            $pageTitle = 'Rejected Withdrawals of '.$restaurant->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', 3)->where('user_id',$restaurant->id)->with(['user','method'])->orderBy('id','desc')->paginate(getPaginate());

        }elseif($type == 'pending'){
            $pageTitle = 'Pending Withdrawals of '.$restaurant->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', 2)->where('user_id',$restaurant->id)->with(['user','method'])->orderBy('id','desc')->paginate(getPaginate());
        }else{
            $pageTitle = 'Withdrawals of '.$restaurant->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', '!=', 0)->where('user_id',$restaurant->id)->with(['user','method'])->orderBy('id','desc')->paginate(getPaginate());
        }
        $emptyMessage = 'Withdraw Log Not Found';
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals', 'emptyMessage','method'));
    }

    public function emailLog($id){

        $restaurant = Restaurant::findOrFail($id);
        $pageTitle = 'Email log of '.$restaurant->username;
        $logs = EmailLog::where('restaurant_id',$id)->with('restaurant')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No data found';
        return view('admin.restaurants.email_log', compact('pageTitle','logs','emptyMessage','restaurant'));
    }

    public function emailDetails($id){
        $email = EmailLog::findOrFail($id);
        $pageTitle = 'Email details';
        return view('admin.restaurants.email_details', compact('pageTitle','email'));
    }

    public function showEmailAllForm()
    {
        $pageTitle = 'Send Email To All Restaurants';
        return view('admin.restaurants.email_all', compact('pageTitle'));
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        foreach (Restaurant::where('status', 1)->cursor() as $restaurant) {
            sendGeneralEmail($restaurant->email, $request->subject, $request->message, $restaurant->username);
        }

        $notify[] = ['success', 'All restaurants will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function categories($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $pageTitle = $restaurant->r_name.' - Categories';
        $categories = $restaurant->categories()->latest()->with('foods')->paginate(getPaginate());
        $emptyMessage = 'No category found';
        return view('admin.restaurants.category', compact('pageTitle','categories','emptyMessage','restaurant'));
    }

    public function storeCategory(Request $request,$id)
    {

        $request->validate([
            'name' => 'required|string|max:40'
        ]);

        $restaurant = Restaurant::findOrFail($id);

        $category = new Category();
        $category->restaurant_id = $id;
        $category->name = $request->name;
        $category->status = 0;
        $category->save();

        $notify[] = ['success', 'Category has been added'];
        return back()->withNotify($notify);
    }

    public function updateCategory(Request $request, $catId, $restId)
    {
        $request->validate([
            'name' => 'required|string|max:40'
        ]);

        $category = Category::where('restaurant_id',$restId)->findOrFail($catId);

        $category->name = $request->name;
        $category->save();

        $notify[] = ['success', 'Category has been Updated'];
        return back()->withNotify($notify);
    }

    public function categoryActivate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|gt:0',
            'restaurant_id' => 'required|integer|gt:0',
        ]);
        $category = Category::where('restaurant_id',$request->restaurant_id)->findOrFail($request->id);
        $category->status = 1;
        $category->save();

        $notify[] = ['success', $category->name . ' has been activated'];
        return back()->withNotify($notify);
    }

    public function categoryDeactivate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|gt:0',
            'restaurant_id' => 'required|integer|gt:0',
        ]);
        $category = Category::where('restaurant_id',$request->restaurant_id)->findOrFail($request->id);
        $category->status = 0;
        $category->save();

        $notify[] = ['success', $category->name . ' has been disabled'];
        return back()->withNotify($notify);
    }

    public function searchCategory(Request $request,$id)
    {
        $search = $request->search;
        $pageTitle = 'Category Search - ' . $search;
        $emptyMessage = 'No data found';
        $restaurant = Restaurant::findOrFail($id);
        $categories = Category::where('restaurant_id',$restaurant->id)->where('name', 'like',"%$search%")->paginate(getPaginate());

        return view('admin.restaurants.category', compact('pageTitle', 'categories', 'emptyMessage','restaurant'));
    }

    public function foods($id)
    {
        $category = Category::findOrFail($id);
        $pageTitle = $category->name.' - Foods';
        $foods = $category->foods()->latest()->paginate(getPaginate());
        $emptyMessage = 'No food found';
        return view('admin.restaurants.food',compact('pageTitle','foods', 'category','emptyMessage'));
    }

    public function foodStore(Request $request ,$id)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'details' => 'required|string|max:191',
            'price' => 'required|numeric|min:0',
            'image' => ['required',new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ]);

        $category = Category::findOrFail($id);

        $foodImage = null;
        if($request->hasFile('image')) {
            try{

                $location = imagePath()['food']['path'];
                $size = imagePath()['food']['size'];

                $foodImage = uploadImage($request->image, $location , $size);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $food = new Food();
        $food->category_id = $category->id;
        $food->restaurant_id = $category->restaurant_id;
        $food->name = $request->name;
        $food->image = $foodImage;
        $food->details = $request->details;
        $food->price = $request->price;
        $food->status = 0;
        $food->save();

        $notify[] = ['success', 'Food details has been added'];
        return back()->withNotify($notify);
    }

    public function foodUpdate(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'details' => 'required|string|max:191',
            'price' => 'required|numeric|min:0',
            'image' => [new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ]);

        $food = Food::findOrFail($id);

        $foodImage = $food->image;
        if($request->hasFile('image')) {
            try{

                $location = imagePath()['food']['path'];
                $size = imagePath()['food']['size'];
                $old = $food->image;
                $foodImage = uploadImage($request->image, $location , $size, $old);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $food->name = $request->name;
        $food->image = $foodImage;
        $food->details = $request->details;
        $food->price = $request->price;
        $food->save();

        $notify[] = ['success', 'Food details has been updated'];
        return back()->withNotify($notify);
    }

    public function foodActivate(Request $request)
    {
        $request->validate(['id' => 'required|integer|gt:0']);
        $food = Food::findOrFail($request->id);
        $food->status = 1;
        $food->save();

        $notify[] = ['success', $food->name . ' has been activated'];
        return back()->withNotify($notify);
    }

    public function foodDeactivate(Request $request)
    {
        $request->validate(['id' => 'required|integer|gt:0']);
        $food = Food::findOrFail($request->id);
        $food->status = 0;
        $food->save();

        $notify[] = ['success', $food->name . ' has been disabled'];
        return back()->withNotify($notify);
    }

    public function foodSearch(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $search = $request->search;
        $pageTitle = 'Food Search - ' . $search;
        $emptyMessage = 'No data found';
        $foods = $category->foods()->where('name', 'like',"%$search%")->paginate(getPaginate());

        return view('admin.restaurants.food', compact('pageTitle', 'foods', 'category', 'emptyMessage'));
    }
}
