<?php

namespace App\Http\Controllers;
use App\Models\AdminNotification;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Page;
use App\Models\Restaurant;
use App\Models\Subscriber;
use App\Models\SupportAttachment;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SiteController extends Controller
{
    public function __construct(){
        $this->activeTemplate = activeTemplate();
    }

    public function index(){
        $count = Page::where('tempname',$this->activeTemplate)->where('slug','home')->count();
        if($count == 0){
            $page = new Page();
            $page->tempname = $this->activeTemplate;
            $page->name = 'HOME';
            $page->slug = 'home';
            $page->save();
        }

        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }

        $pageTitle = 'Home';
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','home')->first();
        return view($this->activeTemplate . 'home', compact('pageTitle','sections'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname',$this->activeTemplate)->where('slug',$slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        return view($this->activeTemplate . 'pages', compact('pageTitle','sections'));
    }


    public function contact()
    {
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','contact')->first();
        $pageTitle = "Contact Us";
        return view($this->activeTemplate . 'contact',compact('pageTitle','sections'));
    }


    public function contactSubmit(Request $request)
    {

        $attachments = $request->file('attachments');
        $allowedExts = array('jpg', 'png', 'jpeg', 'pdf');

        $this->validate($request, [
            'name' => 'required|max:191',
            'email' => 'required|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
        ]);


        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = 2;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.ticket.view',$ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'ticket created successfully!'];

        return redirect()->route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return redirect()->back();
    }

    public function blogDetails($slug,$id){

        $blog = Frontend::where('id',$id)->where('data_keys','blog.element')->firstOrFail();
        $pageTitle = $blog->data_values->title;
        $blogElements = Frontend::where('data_keys', 'blog.element')->latest()->limit(10)->get();

        return view($this->activeTemplate.'blog_details',compact('blog','pageTitle','blogElements'));
    }


    public function cookieAccept(){
        session()->put('cookie_accepted',true);
        return response()->json(['success' => 'Cookie accepted successfully']);
    }

    public function placeholderImage($size = null){
        $imgWidth = explode('x',$size)[0];
        $imgHeight = explode('x',$size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if($imgHeight < 100 && $fontSize > 30){
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function restaurantDetails($id,$slug)
    {
        $restaurant = Restaurant::where('status',1)->where('open_time','!=',null)->where('close_time','!=',null)->where('days','!=',null)->where('d_time','>',0)->whereHas('categories',function($q){
            $q->where('status',1)->whereHas('foods',function($query){
                $query->where('status',1);
            });
        })->with('foods','categories')->findOrFail($id);
        $pageTitle = $restaurant->r_name;

        return view($this->activeTemplate . 'restaurant_details', compact('pageTitle','restaurant'));
    }

    public function subscriberStore(Request $request) {

        $validate = Validator::make($request->all(),[
            'email' => 'required|email|unique:subscribers',
        ]);

        if($validate->fails()){
            return response()->json(['error' => $validate->errors()]);
        }

        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->save();

        return response()->json(['success' => 'Subscribed Successfully!']);
    }

    public function policyDetails($id,$slug)
    {
        $policy = Frontend::where('data_keys', 'policy_pages.element')->findOrFail($id);
        $pageTitle = $policy->data_values->title;
        return view($this->activeTemplate . 'policy', compact('pageTitle','policy'));
    }

    public function search(Request $request)
    {
        $pageTitle = 'Searched for '.$request->search;
        $emptyMessage = 'No data found';
        $search = $request->search;

        $restaurants = Restaurant::where('status',1)->where('open_time','!=',null)->where('close_time','!=',null)->where('days','!=',null)->where('d_time','>',0)->whereHas('categories',function($rCat){
            $rCat->where('status',1)->whereHas('foods',function($catFood){
                    $catFood->where('status',1);
                });
        })->whereHas('foods',function($rFood){
            $rFood->where('status',1)->whereHas('category',function($foodCat){
                    $foodCat->where('status',1);
                });
        })

        ->where(function($q) use ($search) {
            $q->orWhere('r_name','LIKE',"%$search%")
            ->orWhereHas('categories',function($category) use ($search){
                $category->where('status',1)->whereHas('foods',function($catFood){
                    $catFood->where('status',1);
                })->where('name','LIKE',"%$search%");
            })
            ->orWhereHas('foods',function($foods) use ($search){
                $foods->where('status',1)->whereHas('category',function($foodCat){
                    $foodCat->where('status',1);
                })->where('name','LIKE',"%$search%");
            })
            ->orWhereHas('location',function($location) use ($search){
                $location->where('name','LIKE',"%$search%");
            });
        })->latest()->paginate(getPaginate(12));

        return view($this->activeTemplate . 'search', compact('pageTitle','restaurants'));
    }

    public function ourRestaurants()
    {
        $pageTitle = 'Our Restaurants';
        $restaurants = Restaurant::where('status',1)->where('open_time','!=',null)->where('close_time','!=',null)->where('days','!=',null)->where('d_time','>',0)->whereHas('categories',function($q){
            $q->where('status',1)->whereHas('foods',function($query){
                $query->where('status',1);
            });
        })->with('categories','vouchars')->paginate(getPaginate(12));

        return view($this->activeTemplate . 'search', compact('pageTitle','restaurants'));
    }

    public function latestRestaurants()
    {
        $pageTitle = 'Our Restaurants';
        $restaurants = Restaurant::where('status',1)->where('open_time','!=',null)->where('close_time','!=',null)->where('days','!=',null)->where('d_time','>',0)->latest()->whereHas('categories',function($q){
            $q->where('status',1)->whereHas('foods',function($query){
                $query->where('status',1);
            });
        })->with('categories','vouchars')->paginate(getPaginate(12));

        return view($this->activeTemplate . 'search', compact('pageTitle','restaurants'));
    }

    public function blogs()
    {

        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','blog')->first();
        $pageTitle = 'Posts';
        $blogs = Frontend::where('data_keys','blog.element')->latest()->paginate(getPaginate(12));
        return view($this->activeTemplate . 'blogs', compact('pageTitle','blogs','sections'));
    }
}
