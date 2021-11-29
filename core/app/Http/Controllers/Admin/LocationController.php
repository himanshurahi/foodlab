<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Locations';
        $locations = Location::latest()->paginate(getPaginate());
        $emptyMessage = 'No location found';
        return view('admin.location',compact('pageTitle','locations','emptyMessage'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:40'
        ]);

        $location = new Location();
        $location->name = $request->name;
        $location->status = $request->status ? 1 : 0;
        $location->save();

        $notify[] = ['success', 'Location has been added'];
        return back()->withNotify($notify);
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|string|max:191'
        ]);

        $location = Location::findOrFail($id);
        $location->name = $request->name;
        $location->status = $request->status ? 1 : 0;
        $location->save();

        $notify[] = ['success', 'Location has been Updated'];
        return back()->withNotify($notify);
    }

    public function search(Request $request)
    {

        $search = $request->search;
        $pageTitle = 'Location Search - ' . $search;
        $emptyMessage = 'No location found';
        $locations = Location::where('name', 'like',"%$search%")->latest()->paginate(getPaginate());

        return view('admin.location', compact('pageTitle', 'locations', 'emptyMessage'));
    }
}
