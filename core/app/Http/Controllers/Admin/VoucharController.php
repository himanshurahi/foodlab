<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Vouchar;
use Illuminate\Http\Request;

class VoucharController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Vouchars';
        $vouchars = Vouchar::latest()->with('restaurant')->paginate(getPaginate());
        $emptyMessage = 'No vouchar found';

        $restaurants = Restaurant::where('status',1)->where('open_time','!=',null)->where('close_time','!=',null)->where('days','!=',null)->where('d_time','>',0)->latest()->get();

        return view('admin.vouchar',compact('pageTitle','vouchars','emptyMessage','restaurants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|integer|gt:0',
            'type' => 'required|in:1,2',
            'min_limit' => 'required|numeric',
            'code' => 'required|string|max:40',
            'fixed' => 'sometimes|required|numeric',
            'percentage' => 'sometimes|required|numeric|max:100'
        ]);


        if ($request->fixed) {
            if ($request->fixed > $request->min_limit) {
                $notify[] = ['error', 'Can not make discount more than miniumum limit'];
                return back()->withNotify($notify);
            }
        }

        $vouchar = new Vouchar();
        $vouchar->restaurant_id = $request->restaurant_id;
        $vouchar->type = $request->type;
        $vouchar->min_limit = $request->min_limit;
        $vouchar->code = $request->code;
        $vouchar->fixed = $request->fixed;
        $vouchar->percentage = $request->percentage;
        $vouchar->status = $request->status ? 1 : 0;
        $vouchar->save();

        $notify[] = ['success', 'Vouchar added successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'restaurant_id' => 'required|integer|gt:0',
            'type' => 'required|in:1,2',
            'min_limit' => 'required|numeric',
            'code' => 'required|string|max:40',
            'fixed' => 'sometimes|required|numeric',
            'percentage' => 'sometimes|required|numeric|max:100'
        ]);

        $vouchar = Vouchar::findOrFail($id);

        if ($request->fixed) {
            if ($request->fixed > $request->min_limit) {
                $notify[] = ['error', 'Can not make discount more than miniumum limit'];
                return back()->withNotify($notify);
            }
        }

        $vouchar->restaurant_id = $request->restaurant_id;
        $vouchar->type = $request->type;
        $vouchar->min_limit = $request->min_limit;
        $vouchar->code = $request->code;
        $vouchar->fixed = $request->fixed;
        $vouchar->percentage = $request->percentage;
        $vouchar->status = $request->status ? 1 : 0;
        $vouchar->save();

        $notify[] = ['success', 'Vouchar updated successfully'];
        return back()->withNotify($notify);
    }

}
