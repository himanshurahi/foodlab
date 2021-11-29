
@php
    $offers = App\Models\Restaurant::where('status',1)->where('open_time','!=',null)->where('close_time','!=',null)->where('days','!=',null)->where('d_time','>',0)->latest()->limit('12')->whereHas('categories',function($q){
        $q->where('status',1)->whereHas('foods',function($query){
            $query->where('status',1);
        });
    })->whereHas('vouchars',function($v){
        $v->where('status',1);
    })->with('categories','vouchars')->get();

    $frees = App\Models\Restaurant::where('status',1)->where('open_time','!=',null)->where('close_time','!=',null)->where('days','!=',null)->where('d_time','>',0)->where('d_charge', '<=', 0)->latest()->limit('12')->whereHas('categories',function($q){
        $q->where('status',1)->whereHas('foods',function($query){
            $query->where('status',1);
        });
    })->with('categories','vouchars')->get();
@endphp

<section class="food-section food-section--style pt-60">
    <div class="container">
        <div class="food-tab">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="offer-tab" data-bs-toggle="tab" data-bs-target="#offer" type="button"
                        role="tab" aria-controls="offer" aria-selected="true">@lang('Our Offer')</button>
                    <button class="nav-link" id="free-tab" data-bs-toggle="tab" data-bs-target="#free" type="button" role="tab"
                        aria-controls="free" aria-selected="false">@lang('Free Delivery')</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="offer" role="tabpanel" aria-labelledby="offer-tab">
                    <div class="row justify-content-center mb-30-none">
                        @foreach($offers as $item)

                            @php
                                $categoryTxt = '';
                                foreach ($item->categories as $data) {
                                    $categoryTxt.= $data->name.', ';
                                }
                            @endphp

                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-6 mb-30">
                                <div class="order-item">
                                    <a href="{{route('restaurant.details',[$item->id,slug($item->r_name)])}}">
                                        <div class="order-thumb">
                                            <img src="{{ getImage(imagePath()['profile']['restaurant']['path'].'/'. $item->image,imagePath()['profile']['restaurant']['size'])}}" alt="@lang('restaurant')">
                                            <span class="delivery-time">{{$item->d_time}} <span class="label">@lang('min')</span></span>

                                            @foreach ($item->vouchars->where('status',1)->take(1) as $vouchar)

                                                @if($vouchar->type == 1 && ($vouchar->fixed))
                                                    <span class="offer-badge">{{$vouchar->code}}</span>
                                                @elseif($vouchar->type == 2 && ($vouchar->percentage))
                                                    <span class="offer-badge">{{$vouchar->percentage}}% @lang('Off')</span>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="order-content">
                                            <div class="order-content-header d-flex flex-wrap align-items-center justify-content-between">
                                                <h4 class="title">{{str_limit(__($item->r_name),28)}} </h4>
                                                <div class="ratings d-flex flex-wrap align-items-center">
                                                    <i class="las la-star"></i>
                                                    <span>{{$item->avg_rating}}/5</span>
                                                </div>
                                            </div>
                                            <span class="sub-title">
                                                {{str_limit(substr($categoryTxt,0,-2),50)}}
                                            </span>
                                            <h6 class="delivery">@if($item->d_charge > 0) {{$general->cur_text}} {{showAmount($item->d_charge)}} @else @lang('Free') @endif <span>@lang('Delivery Fee')</span></h6>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="free" role="tabpanel" aria-labelledby="free-tab">
                    <div class="row justify-content-center mb-30-none">
                        @foreach($frees as $item)

                            @php
                                $categoryTxt = '';
                                foreach ($item->categories as $data) {
                                    $categoryTxt.= $data->name.', ';
                                }
                            @endphp

                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-6 mb-30">
                                <div class="order-item">
                                    <a href="{{route('restaurant.details',[$item->id,slug($item->r_name)])}}">
                                        <div class="order-thumb">
                                            <img src="{{ getImage(imagePath()['profile']['restaurant']['path'].'/'. $item->image,imagePath()['profile']['restaurant']['size'])}}" alt="@lang('restaurant')">
                                            <span class="delivery-time">{{$item->d_time}} <span class="label">@lang('min')</span></span>

                                            @foreach ($item->vouchars->where('status',1)->take(1) as $vouchar)

                                                @if($vouchar->type == 1 && ($vouchar->fixed))
                                                    <span class="offer-badge">{{$vouchar->code}}</span>
                                                @elseif($vouchar->type == 2 && ($vouchar->percentage))
                                                    <span class="offer-badge">{{$vouchar->percentage}}% @lang('Off')</span>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="order-content">
                                            <div class="order-content-header d-flex flex-wrap align-items-center justify-content-between">
                                                <h4 class="title">{{str_limit(__($item->r_name),28)}} </h4>
                                                <div class="ratings d-flex flex-wrap align-items-center">
                                                    <i class="las la-star"></i>
                                                    <span>{{$item->avg_rating}}/5</span>
                                                </div>
                                            </div>
                                            <span class="sub-title">
                                                {{str_limit(substr($categoryTxt,0,-2),50)}}
                                            </span>
                                            <h6 class="delivery">@if($item->d_charge > 0) {{$general->cur_text}} {{showAmount($item->d_charge)}} @else @lang('Free') @endif <span>@lang('Delivery Fee')</span></h6>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
