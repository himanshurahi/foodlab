<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class OrderController extends Controller
{
    public function pendingOrders()
    {
        $pageTitle = 'Pending Orders';
        $emptyMessage = 'No pending orders';

        $orders = Order::where('status',1)->paginate(getPaginate());
        return view('admin.orders',compact('pageTitle','orders','emptyMessage'));
    }

    public function deliveredOrders()
    {
        $pageTitle = 'Delivered Orders';
        $emptyMessage = 'No delivered orders';

        $orders = Order::where('status',2)->paginate(getPaginate());
        return view('admin.orders',compact('pageTitle','orders','emptyMessage'));
    }

    public function canceledOrders()
    {
        $pageTitle = 'Canceled Orders';
        $emptyMessage = 'No canceled orders';

        $orders = Order::where('status',3)->paginate(getPaginate());
        return view('admin.orders',compact('pageTitle','orders','emptyMessage'));
    }

    public function orderSearch(Request $request)
    {
        $search = $request->search;
        $pageTitle = 'Order Search - ' . $search;
        $emptyMessage = 'No order found';
        $orders = Order::where('status', '!=', 0)->where('order_code',$search)->paginate(getPaginate());

        return view('admin.orders',compact('pageTitle','orders','emptyMessage', 'search'));
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
        $order = Order::with('details.food')->findOrFail($orderId);

        return view('admin.order_details',compact('pageTitle','order'));
    }

    public function orderCancel(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|gt:0',
            'message' => 'required|max:250'
        ]);

        $order = Order::findOrFail($request->id);
        $general = GeneralSetting::first();

        $order->status = 3;
        $order->cancel_message = $request->message;
        $order->save();

        $order->user->balance += $order->total;
        $order->user->save();

        $gnl = GeneralSetting::first();

        $transaction = new Transaction();
        $transaction->user_id = $order->user->id;
        $transaction->amount = $order->total;
        $transaction->post_balance = $order->user->balance;
        $transaction->charge = 0;
        $transaction->trx_type = '+';
        $transaction->details = showAmount($order->total) . ' ' . $gnl->cur_text .' Added with Your Own Balance as Refund For Cancellation of a Order.';
        $transaction->trx =  getTrx();
        $transaction->save();

        $foodList = '';

        foreach ($order->details as $item) {
            $foodList .= '# '.$item->food->name.' x '.$item->qty.' = '.$general->cur_text.' '.showAmount($item->price).'<br>';
        }

        notify($order->user, 'USER_ORDER_CANCELED', [
            'order_code' => $order->order_code,
            'd_charge' => showAmount($order->d_charge),
            'food_list' => $foodList,
            'currency' => $general->cur_text,
            'sub_total' => showAmount($order->sub_total),
            'vat' => showAmount($order->vat),
            'discount' => showAmount($order->discount),
            'total' => showAmount($order->total),
            'rejection_message' => $request->message,
            'post_balance' => $order->user->balance
        ]);

        $notify[] = ['success', 'Order has been canceled.'];
        return  redirect()->route('admin.orders.pending')->withNotify($notify);
    }
}
