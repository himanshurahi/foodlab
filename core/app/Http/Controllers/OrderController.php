<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Detail;
use App\Models\Food;
use App\Models\GatewayCurrency;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Transaction;
use App\Models\Vouchar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function __construct(){
        $this->activeTemplate = activeTemplate();
    }

    public function orders()
    {
        $pageTitle = 'Orders';
        $orders = Order::where('user_id',auth()->user()->id)->where('status', '!=' ,0)->latest()->paginate(getPaginate());
        $emptyMessage = 'No order found';
        return view($this->activeTemplate . 'user.orders', compact('pageTitle','orders','emptyMessage'));
    }

    public function pendingOrders()
    {
        $pageTitle = 'Pending Orders';
        $orders = Order::where('user_id',auth()->user()->id)->where('status',4)->latest()->paginate(getPaginate());
        $emptyMessage = 'No pending order found';
        return view($this->activeTemplate . 'user.orders', compact('pageTitle','orders','emptyMessage'));
    }

    public function confirmedOrders()
    {
        $pageTitle = 'Confirmed Orders';
        $orders = Order::where('user_id',auth()->user()->id)->where('status',1)->latest()->paginate(getPaginate());
        $emptyMessage = 'No confirmed order found';
        return view($this->activeTemplate . 'user.orders', compact('pageTitle','orders','emptyMessage'));
    }

    public function deliveredOrders()
    {
        $pageTitle = 'Delivered Orders';
        $orders = Order::where('user_id',auth()->user()->id)->where('status',2)->latest()->paginate(getPaginate());
        $emptyMessage = 'No delivered order found';
        return view($this->activeTemplate . 'user.orders', compact('pageTitle','orders','emptyMessage'));
    }

    public function canceledOrders()
    {
        $pageTitle = 'Canceled Orders';
        $orders = Order::where('user_id',auth()->user()->id)->where('status',3)->latest()->paginate(getPaginate());
        $emptyMessage = 'No canceled order found';
        return view($this->activeTemplate . 'user.orders', compact('pageTitle','orders','emptyMessage'));
    }

    public function orderDetails($id)
    {
        try {
            $orderId = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            $notify[] = ['error', 'You can not proceed this action!'];
            return redirect()->route('home')->withNotify($notify);
        }

        $order = Order::where('id', $orderId)->with('details.food')->firstOrFail();

        if ($order->user_id != auth()->user()->id) {

            $notify[] = ['error', 'You can not proceed this action!'];
            return redirect()->route('home')->withNotify($notify);
        }

        $pageTitle = 'Order Details';

        return view($this->activeTemplate . 'user.delivery_confirm', compact('pageTitle','order'));
    }

    public function addOrders(Request $request)
    {
        if (auth()->check()) {

            $validate = Validator::make($request->all(),[
                'food_id' => 'integer|required|gt:0'
            ]);

            if($validate->fails()){
                return response()->json(['error' => $validate->errors()]);
            }

            $food = Food::where('status',1)->whereHas('restaurant', function($restaurant){
                $restaurant->where('status',1);
            })->whereHas('category', function($category){
                $category->where('status',1);
            })->find($request->food_id);

            if(!$food){
                return response()->json(['error' => 'Food not found or deactivated']);
            }


            if(session('orders_by_user')){
                $orders = session('orders_by_user');
            }else{
                $orders = [];
                session()->put('restaurant_id',$food->restaurant->id);
            }

            if($food->restaurant->id != session('restaurant_id')){
                $orders = [];
                session()->put('restaurant_id',$food->restaurant->id);
            }

            $qty = 1;

            $newData = [
                'food' => $food,
                'qty' => $qty
            ];

            if(isset($orders[$food->id])){
                $orders[$food->id] = [
                    'food' => $food,
                    'qty' => $orders[$food->id]['qty'] + 1
                ];
            }else{
                $orders[$food->id] = $newData;
            }

            session()->put('orders_by_user',$orders);

            if(count($orders) > 0){
                $subTotal = 0;
                $deliveryFee = $food->restaurant->d_charge;

                foreach ($orders as $item) {
                    $subTotal += $item['food']->price * $item['qty'];
                }

                $vat = ($subTotal * $food->restaurant->vat) / 100;
                $total = $subTotal + $deliveryFee + $vat;

                return response()->json([
                    'orders' => $orders,
                    'subTotal' => showAmount($subTotal),
                    'deliveryFee' => showAmount($deliveryFee),
                    'vat' => showAmount($vat),
                    'total' => showAmount($total),
                ]);
            }else{
                return response()->json(['error' => 'No order found']);
            }

        }else{
            return response()->json(['error' => 'Order requires login']);
        }
    }

    public function subOrders(Request $request)
    {
        if (auth()->check()) {

            $validate = Validator::make($request->all(),[
                'food_id' => 'integer|required|gt:0'
            ]);

            if($validate->fails()){
                return response()->json(['error' => $validate->errors()]);
            }

            $food = Food::where('status',1)->whereHas('restaurant', function($restaurant){
                $restaurant->where('status',1);
            })->whereHas('category', function($category){
                $category->where('status',1);
            })->find($request->food_id);

            if(!$food){
                return response()->json(['error' => 'Food not found or deactivated']);
            }

            if(session('orders_by_user') && session('restaurant_id')){
                $orders = session('orders_by_user');

                if(isset($orders[$food->id])){

                    if ($orders[$food->id]['qty'] > 0) {
                        $orders[$food->id] = [
                            'food' => $food,
                            'qty' => $orders[$food->id]['qty'] - 1,
                        ];
                    }

                    if ($orders[$food->id]['qty'] <= 0) {
                        unset($orders[$food->id]);
                    }
                }

                session()->put('orders_by_user',$orders);

                if(count($orders) > 0){

                    $subTotal = 0;
                    $deliveryFee = $food->restaurant->d_charge;

                    foreach ($orders as $item) {
                        $subTotal += $item['food']->price * $item['qty'];
                    }

                    $vat = ($subTotal * $food->restaurant->vat) / 100;
                    $total = $subTotal + $deliveryFee + $vat;

                    return response()->json([
                        'orders' => $orders,
                        'subTotal' => showAmount($subTotal),
                        'deliveryFee' => showAmount($deliveryFee),
                        'vat' => showAmount($vat),
                        'total' => showAmount($total),
                    ]);
                }else{
                    return response()->json(['error' => 'No order found']);
                }

            }else{
                return response()->json(['error' => 'You can not proceed this action']);
            }
        }else{
            return response()->json(['error' => 'Order requires login']);
        }
    }

    public function checkout()
    {
        if (auth()->check()) {

            $pageTitle = 'Checkout';
            $user = auth()->user();

            session()->forget('v_code');

            if(session('orders_by_user') && session('restaurant_id')){

                $restaurant = Restaurant::where('status',1)->where('open_time','!=',null)->where('close_time','!=',null)->where('days','!=',null)->where('d_time','>',0)->findOrFail(session('restaurant_id'));

                $orders = session('orders_by_user');

                if (count($orders) > 0) {
                    $subTotal = 0;
                    $deliveryFee = $restaurant->d_charge;

                    foreach ($orders as $item) {
                        $subTotal += $item['food']->price * $item['qty'];
                    }

                    $vat = ($subTotal * $restaurant->vat) / 100;
                    $total = $subTotal + $deliveryFee + $vat;

                    $gatewayCurrency = GatewayCurrency::where('min_amount', '<=' , $total)->where('max_amount', '>=' , $total)->whereHas('method', function ($gate) {
                                    $gate->where('status', 1);
                                })->with('method')->orderby('method_code')->get();

                    return view($this->activeTemplate . 'checkout', compact('pageTitle','orders','restaurant','subTotal','deliveryFee','vat','total','user','gatewayCurrency'));
                }else{
                    $notify[] = ['error', 'No order found!'];
                    return redirect()->route('home')->withNotify($notify);
                }

            }else{
                $notify[] = ['error', 'You can not proceed this action!'];
                return redirect()->route('home')->withNotify($notify);
            }
        }else{
            $notify[] = ['error', 'This action requires login'];
            return back()->withNotify($notify);
        }
    }

    public function voucharApply(Request $request)
    {
        if (auth()->check()) {

            $validate = Validator::make($request->all(),[
                'v_code' => 'required|max:40'
            ]);

            if($validate->fails()){
                return response()->json(['error' => $validate->errors()]);
            }

            if(session('orders_by_user') && session('restaurant_id')){

                $restaurant = Restaurant::where('status',1)->where('open_time','!=',null)->where('close_time','!=',null)->where('days','!=',null)->where('d_time','>',0)->find(session('restaurant_id'));

                $vCodes =  $restaurant->vouchars->where('status',1)->pluck('code')->toArray();

                if (count($vCodes) > 0) {

                    if(!in_array($request->v_code,$vCodes)){
                        return response()->json(['error' => 'Voucher code is invalid']);
                    }

                    $orders = session('orders_by_user');

                    if(count($orders) > 0){

                        $subTotal = 0;
                        $deliveryFee = $restaurant->d_charge;

                        foreach ($orders as $item) {
                            $subTotal += $item['food']->price * $item['qty'];
                        }

                        $vat = ($subTotal * $restaurant->vat) / 100;
                        $total = $subTotal + $deliveryFee + $vat;

                        $vouchar = Vouchar::where('status',1)->where('code',$request->v_code)->first();

                        if($vouchar->type == 1 && ($vouchar->fixed)) {

                            if ($subTotal < $vouchar->min_limit) {
                                return response()->json(['error' => 'Minimum order limit is '.showAmount($vouchar->min_limit)]);
                            }

                            $discount = $vouchar->fixed;
                            $grandTotal = $total - $discount;

                            session()->put('v_code',$request->v_code);

                            return response()->json([
                                'discount' => showAmount($discount),
                                'grandTotal' => showAmount($grandTotal),
                                'success' => 'Voucher applied successfully'
                            ]);

                        }elseif ($vouchar->type == 2 && ($vouchar->percentage)) {

                            if ($subTotal < $vouchar->min_limit) {
                                return response()->json(['error' => 'Minimum order limit is '.showAmount($vouchar->min_limit)]);
                            }

                            $discount = ($subTotal * $vouchar->percentage) / 100;
                            $grandTotal = $total - $discount;

                            session()->put('v_code',$request->v_code);

                            return response()->json([
                                'discount' => showAmount($discount),
                                'grandTotal' => showAmount($grandTotal),
                                'success' => 'Voucher applied successfully'
                            ]);

                        }else{
                            return response()->json(['error' => 'There is no vouchar for this restaurant']);
                        }

                    }else{
                        return response()->json(['error' => 'No order found']);
                    }

                }else{
                    return response()->json(['error' => 'There is no vouchar for this restaurant']);
                }

            }else{
                return response()->json(['error' => 'You can not proceed this action']);
            }
        }else{
            return response()->json(['error' => 'This action requires login']);
        }
    }

    public function voucharRemove()
    {
        if (auth()->check()) {

            if(session('orders_by_user') && session('restaurant_id') && session('v_code')){

                $restaurant = Restaurant::where('status',1)->where('open_time','!=',null)->where('close_time','!=',null)->where('days','!=',null)->where('d_time','>',0)->find(session('restaurant_id'));

                $orders = session('orders_by_user');

                if(count($orders) > 0){
                    $subTotal = 0;
                    $deliveryFee = $restaurant->d_charge;

                    foreach ($orders as $item) {
                        $subTotal += $item['food']->price * $item['qty'];
                    }

                    $vat = ($subTotal * $restaurant->vat) / 100;
                    $total = $subTotal + $deliveryFee + $vat;

                    session()->forget('v_code');

                    return response()->json([
                        'total' => showAmount($total),
                        'success' => 'Voucher removed successfully'
                    ]);
                }else{
                    return response()->json(['error' => 'No order found']);
                }

            }else{
                return response()->json(['error' => 'You can not proceed this action']);
            }
        }else{
            return response()->json(['error' => 'This action requires login']);
        }
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'd_address' => 'required|max:191',
            'message' => 'nullable|max:191'
        ]);



        if(session('orders_by_user') && session('restaurant_id')){

            $restaurant = Restaurant::where('status',1)->where('open_time','!=',null)->where('close_time','!=',null)->where('days','!=',null)->where('d_time','>',0)->findOrFail(session('restaurant_id'));

            $orders = session('orders_by_user');

            if(count($orders) > 0){
                $subTotal = 0;
                $deliveryFee = $restaurant->d_charge;

                foreach ($orders as $item) {
                    $subTotal += $item['food']->price * $item['qty'];
                }

                $vat = ($subTotal * $restaurant->vat) / 100;
                $total = $subTotal + $deliveryFee + $vat;
                $discount = 0;

                $user = auth()->user();

                if (session('v_code')) {

                    $vouchar = Vouchar::where('status',1)->where('code',session('v_code'))->first();

                    if($vouchar->type == 1 && ($vouchar->fixed)) {

                        if ($subTotal < $vouchar->min_limit) {

                            $notify[] = ['error', 'Minimum order limit is '.showAmount($vouchar->min_limit)];
                            return redirect()->route('home')->withNotify($notify);
                        }

                        $discount = $vouchar->fixed;
                        $total -= $discount;

                    }elseif ($vouchar->type == 2 && ($vouchar->percentage)) {

                        if ($subTotal < $vouchar->min_limit) {

                            $notify[] = ['error', 'Minimum order limit is '.showAmount($vouchar->min_limit)];
                            return redirect()->route('home')->withNotify($notify);
                        }

                        $discount = ($subTotal * $vouchar->percentage) / 100;
                        $total -= $discount;

                    }else{

                        $notify[] = ['error', 'There is no vouchar for this restaurant'];
                        return redirect()->route('home')->withNotify($notify);
                    }
                }

                $order = new Order();
                $order->user_id = $user->id;
                $order->restaurant_id = $restaurant->id;
                $order->order_code = getTrx(6);
                $order->sub_total = $subTotal;
                $order->d_charge = $restaurant->d_charge;
                $order->vat = $vat;
                $order->v_code = session('v_code') ? session('v_code'):null;
                $order->discount = $discount;
                $order->total = $total;
                $order->d_address = $request->d_address;
                $order->message = $request->message;
                $order->status = 0;
                $order->save();


                foreach ($orders as $item) {
                    $foodDetails = new Detail();
                    $foodDetails->order_id = $order->id;
                    $foodDetails->food_id = $item['food']->id;
                    $foodDetails->qty = $item['qty'];
                    $foodDetails->price = $item['qty'] * $item['food']->price;
                    $foodDetails->save();
                }

                if (($request->payment_method == 0)) {

                    if ($total > $user->balance) {
                        $notify[] = ['error', 'You do not have enough balance'];
                        return back()->withNotify($notify);
                    }

                    $user->balance -= $total;
                    $user->save();

                    $order->status = 1;
                    $order->save();

                    $gnl = GeneralSetting::first();

                    $transaction = new Transaction();
                    $transaction->user_id = $user->id;
                    $transaction->amount = $total;
                    $transaction->post_balance = $user->balance;
                    $transaction->charge = 0;
                    $transaction->trx_type = '-';
                    $transaction->details = showAmount($total) . ' ' . $gnl->cur_text .' Subtracted From Your Own Balance For Ordering Foods.';
                    $transaction->trx =  getTrx();
                    $transaction->save();

                    $foodList = '';

                    foreach ($orders as $item) {
                        $foodList .= '# '.$item['food']->name.' x '.$item['qty'].' = '.$gnl->cur_text.' '.showAmount($foodDetails->price).'<br>';
                    }

                    notify($user, 'USER_ORDER_CONFIRMATION', [
                        'order_code' => $order->order_code,
                        'd_time' => $restaurant->d_time,
                        'food_list' => $foodList,
                        'currency' => $gnl->cur_text,
                        'sub_total' => showAmount($subTotal),
                        'd_charge' => showAmount($restaurant->d_charge),
                        'vat' => showAmount($vat),
                        'discount' => showAmount($discount),
                        'total' => showAmount($total),
                        'method_name' => 'Your Own Balance',
                        'post_balance' => showAmount($user->balance)
                    ]);

                    notify($restaurant, 'RESTAURANT_ORDER_CONFIRMATION', [
                        'order_code' => $order->order_code,
                        'd_time' => $restaurant->d_time,
                        'food_list' => $foodList,
                        'currency' => $gnl->cur_text,
                        'sub_total' => showAmount($order->sub_total),
                        'd_charge' => showAmount($order->d_charge),
                        'vat' => showAmount($order->vat),
                        'discount' => showAmount($order->discount),
                        'total' => showAmount($order->total),
                    ]);

                    session()->forget(['orders_by_user','restaurant_id','v_code']);

                    return redirect()->route('user.confirm.delivery',Crypt::encrypt($order->id));

                }else{

                    $gate = GatewayCurrency::whereHas('method', function ($gate) {
                        $gate->where('status', 1);
                    })->findOrFail($request->payment_method);

                    if (!$gate) {
                        $notify[] = ['error', 'Invalid gateway'];
                        return back()->withNotify($notify);
                    }

                    if ($gate->min_amount > $total || $gate->max_amount < $total) {
                        $notify[] = ['error', 'The total amount does not match the payment gateway limit'];
                        return back()->withNotify($notify);
                    }

                    $charge = $gate->fixed_charge + ($total * $gate->percent_charge / 100);
                    $payable = $total + $charge;
                    $final_amo = $payable * $gate->rate;

                    $data = new Deposit();
                    $data->user_id = $user->id;
                    $data->order_id = $order->id;
                    $data->method_code = $gate->method_code;
                    $data->method_currency = strtoupper($gate->currency);
                    $data->amount = $total;
                    $data->charge = $charge;
                    $data->rate = $gate->rate;
                    $data->final_amo = $final_amo;
                    $data->btc_amo = 0;
                    $data->btc_wallet = "";
                    $data->trx = getTrx();
                    $data->try = 0;
                    $data->status = 0;
                    $data->save();

                    session()->put('Track', $data->trx);
                    return redirect()->route('user.deposit.preview');

                }

            }else{
                $notify[] = ['error', 'No order found'];
                return redirect()->route('home')->withNotify($notify);
            }

        }else{
            $notify[] = ['error', 'You can not proceed this action!'];
            return redirect()->route('home')->withNotify($notify);
        }

    }

    public function confirmDelivery($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            $notify[] = ['error', 'You can not proceed this action!'];
            return redirect()->route('home')->withNotify($notify);
        }

        $order = Order::where('status',1)->with('details.food')->findOrFail($id);

        if ($order->user_id != auth()->user()->id) {

            $notify[] = ['error', 'You can not proceed this action!'];
            return redirect()->route('home')->withNotify($notify);
        }

        $pageTitle = 'Delivery Confirmation';

        return view($this->activeTemplate . 'user.delivery_confirm', compact('pageTitle','order'));
    }

    public function makeDeliveryConfirm(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'order_code' => 'required|max:6'
        ]);

        try {
            $id = Crypt::decrypt($request->order_id);
        } catch (\Throwable $th) {
            $notify[] = ['error', 'You can not proceed this action!'];
            return redirect()->route('home')->withNotify($notify);
        }

        $order = Order::where('status',1)->with('details.food')->findOrFail($id);

        if ($order->user_id != auth()->user()->id) {

            $notify[] = ['error', 'You can not proceed this action!'];
            return redirect()->route('home')->withNotify($notify);
        }


        if ($request->order_code == $order->order_code) {

            $order->status = 2;
            $order->save();

            $order->restaurant->balance += $order->total;
            $order->restaurant->save();

            $gnl = GeneralSetting::first();

            $transaction = new Transaction();
            $transaction->restaurant_id = $order->restaurant->id;
            $transaction->amount = $order->total;
            $transaction->post_balance = $order->restaurant->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '+';
            $transaction->details = showAmount($order->total) . ' ' . $gnl->cur_text .' Added With Balance For Delivering Order.';
            $transaction->trx =  getTrx();
            $transaction->save();

            notify($order->restaurant, 'DELIVERY_BAL_ADD', [
                'order_code' => $order->order_code,
                'amount' => showAmount($order->total),
                'trx' => $transaction->trx,
                'post_balance' => $order->restaurant->balance,
                'currency' => $gnl->cur_text
            ]);

            $notify[] = ['success', 'Enjoy your meal'];
            return redirect()->route('user.orders')->withNotify($notify);

        }else{
            $notify[] = ['error', 'Invalid order code!'];
            return back()->withNotify($notify);
        }
    }
}
