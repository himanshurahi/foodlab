<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function foods($id)
    {
        $category = Category::findOrFail($id);
        $pageTitle = $category->name.' - Foods';
        $foods = Food::where('restaurant_id',auth()->guard('restaurant')->user()->id)->where('category_id',$category->id)->paginate(getPaginate());
        $emptyMessage = 'No food found';
        return view('restaurant.food.index',compact('pageTitle','foods', 'category','emptyMessage'));
    }

    public function store(Request $request ,$id)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'details' => 'required|string|max:191',
            'price' => 'required|numeric|min:0',
            'image' => ['required',new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ]);

        $category = Category::where('restaurant_id',auth()->guard('restaurant')->user()->id)->findOrFail($id);

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
        $food->restaurant_id = auth()->guard('restaurant')->user()->id;
        $food->name = $request->name;
        $food->image = $foodImage;
        $food->details = $request->details;
        $food->price = $request->price;
        $food->status = 1;
        $food->save();

        $notify[] = ['success', 'Food details has been added'];
        return back()->withNotify($notify);
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'details' => 'required|string|max:191',
            'price' => 'required|numeric|min:0',
            'image' => [new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ]);

        $food = Food::where('restaurant_id',auth()->guard('restaurant')->user()->id)->findOrFail($id);

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

    public function activate(Request $request)
    {
        $request->validate(['id' => 'required|integer|gt:0']);
        $food = Food::where('restaurant_id',auth()->guard('restaurant')->user()->id)->findOrFail($request->id);
        $food->status = 1;
        $food->save();

        $notify[] = ['success', $food->name . ' has been activated'];
        return back()->withNotify($notify);
    }

    public function deactivate(Request $request)
    {
        $request->validate(['id' => 'required|integer|gt:0']);
        $food = Food::where('restaurant_id',auth()->guard('restaurant')->user()->id)->findOrFail($request->id);
        $food->status = 0;
        $food->save();

        $notify[] = ['success', $food->name . ' has been disabled'];
        return back()->withNotify($notify);
    }

    public function search(Request $request, $id)
    {
        $category = Category::where('restaurant_id',auth()->guard('restaurant')->user()->id)->findOrFail($id);
        $search = $request->search;
        $pageTitle = 'Food Search - ' . $search;
        $emptyMessage = 'No data found';
        $foods = $category->foods()->where('name', 'like',"%$search%")->paginate(getPaginate());

        return view('restaurant.food.index', compact('pageTitle', 'foods', 'category', 'emptyMessage'));
    }
}
