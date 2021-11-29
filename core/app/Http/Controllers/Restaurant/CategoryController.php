<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories()
    {
        $pageTitle = 'All Categories';
        $categories = Category::where('restaurant_id',auth()->guard('restaurant')->user()->id)->with('foods')->paginate(getPaginate());
        $emptyMessage = 'No data found';
        return view('restaurant.food.category',compact('pageTitle','categories','emptyMessage'));
    }

    public function storeCategory(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:40'
        ]);

        $category = new Category();
        $category->restaurant_id = auth()->guard('restaurant')->user()->id;
        $category->name = $request->name;
        $category->status = 0;
        $category->save();

        $notify[] = ['success', 'Category has been added'];
        return back()->withNotify($notify);
    }

    public function updateCategory(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|string|max:40'
        ]);

        $category = Category::where('restaurant_id',auth()->guard('restaurant')->user()->id)->findOrFail($id);
        dd($category);
        $category->name = $request->name;
        $category->save();

        $notify[] = ['success', 'Category has been Updated'];
        return back()->withNotify($notify);
    }

    public function activate(Request $request)
    {
        $request->validate(['id' => 'required|integer||gt:0']);
        $category = Category::where('restaurant_id',auth()->guard('restaurant')->user()->id)->findOrFail($request->id);
        $category->status = 1;
        $category->save();

        $notify[] = ['success', $category->name . ' has been activated'];
        return back()->withNotify($notify);
    }

    public function deactivate(Request $request)
    {
        $request->validate(['id' => 'required|integer||gt:0']);
        $category = Category::where('restaurant_id',auth()->guard('restaurant')->user()->id)->findOrFail($request->id);
        $category->status = 0;
        $category->save();

        $notify[] = ['success', $category->name . ' has been disabled'];
        return back()->withNotify($notify);
    }

    public function searchCategory(Request $request)
    {
        $search = $request->search;
        $pageTitle = 'Category Search - ' . $search;
        $emptyMessage = 'No data found';
        $categories = Category::where('restaurant_id',auth()->guard('restaurant')->user()->id)->where('name', 'like',"%$search%")->paginate(getPaginate());

        return view('restaurant.food.category', compact('pageTitle', 'categories', 'emptyMessage'));
    }
}
