@extends($activeTemplate.'layouts.frontend')
@section('content')

    <section class="inner-banner-section banner-section bg_img" data-background="{{ getImage(imagePath()['profile']['restaurant']['path'].'/'. $restaurant->image,imagePath()['profile']['restaurant']['size'])}}"></section>

    <div class="cart-sidebar-area" id="cart-sidebar-area">
        <div class="cart-sidebar-content">
            <div class="cart-header d-flex flex-wrap align-items-center justify-content-between mb-20">
                <h4 class="title text-center mb-0">@lang('Your Order From') {{__($restaurant->r_name)}}</h4>
                <span class="side-sidebar-close-btn"><i class="fas fa-times"></i></span>
            </div>
            <div class="card-content">
                <div class="food-item">
                    <div class="food-wrapper">
                        <div class="food-name-qty">
                        </div>
                        <div class="food-cart-slider-area">
                            <h4 class="title">@lang('More Foods To Order')</h4>
                            <div class="food-cart-slider-inner">
                                <div class="food-cart-slider">
                                    <div class="swiper-wrapper">
                                        @foreach($restaurant->foods()->with('category')->where('status',1)->get() as $item)
                                            <div class="swiper-slide">
                                                <div class="food-item">
                                                    <div class="food-wrapper">
                                                        <div class="food-thumb">
                                                            <img src="{{ getImage(imagePath()['food']['path'].'/'. $item->image,imagePath()['food']['size'])}}" alt="food">
                                                        </div>
                                                        <div class="food-content">
                                                            <h6 class="title">{{__($item->name)}}</h6>
                                                            <p>{{__($item->category->name)}}</p>
                                                            <span class="cart-badge add-food-cart" data-foodid="{{$item->id}}"><i class="las la-plus"></i></span>
                                                            <span class="food-fee">{{$general->cur_text}} {{showAmount($item->price)}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="slider-prev">
                                        <i class="las la-angle-left"></i>
                                    </div>
                                    <div class="slider-next">
                                        <i class="las la-angle-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="food-order-list">
                            <li>@lang('Subtotal') <span class="subTotal"></span></li>
                            <li>@lang('Delivery Fee') <span class="deliveryFee"></span></li>
                            <li>@lang('Vat') <span class="vat"></span></li>
                            <li><span class="fw-bold">@lang('Total(Incl.vat)')</span> <span class="fw-bold total"></span></li>
                        </ul>
                        <div class="checkout-btn mt-20">
                            <a href="{{route('user.checkout')}}" class="btn--base w-100">@lang('Proceed to checkout')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="details-section bg--gray">
        <div class="restaurants-details-area">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="restaurant-details-wrapper">
                            <div class="restaurants-details-content">
                                <h2 class="title">{{__($restaurant->r_name)}}</h2>
                                <div class="ratings d-flex flex-wrap align-items-center">
                                    <i class="las la-star"></i>
                                    <span>{{$restaurant->avg_rating}}/5</span>
                                </div>
                                @foreach ($restaurant->vouchars->where('status',1) as $item)
                                    <p>
                                        @lang('Use VOUCHER') : <span class="text--base fw-bold">{{$item->code}}</span> & @lang('Enjoy')

                                        @if($item->type == 1 && ($item->fixed))
                                            <span class="text--base fw-bold">{{showAmount($item->fixed)}} {{$general->cur_text}}</span> @lang('Off on Orders Above') <span class="text--base fw-bold">{{showAmount($item->min_limit)}} {{$general->cur_text}}</span>
                                        @elseif($item->type == 2 && ($item->percentage))
                                            <span class="text--base fw-bold">{{$item->percentage}} %</span> @lang('Off on Orders Above') <span class="text--base fw-bold">{{showAmount($item->min_limit)}} {{$general->cur_text}}</span>
                                        @endif.
                                    </p>
                                @endforeach
                            </div>
                            <div class="restaurant-information">
                                <a href="#0" class="restaurant-info"><i class="las la-info-circle"></i> @lang('Restaurant Information')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="restaurants-details-header">
            <div class="restaurants-header-area">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <ul class="food-details-tab nav">
                                @foreach ($restaurant->categories()->where('status',1)->get() as $item)
                                    @if($item->foods()->where('status',1)->count() == 0)
                                        @continue
                                    @endif
                                    <li class="nav-item"><a href="#{{slug($item->name)}}" class="nav-link">{{__($item->name)}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach($restaurant->categories()->with('foods')->where('status',1)->get() as $item)
            @if($item->foods()->where('status',1)->count() == 0)
                @continue
            @endif
            <div class="food-section ptb-30 mb-30" id="{{slug($item->name)}}">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-12">
                            <div class="section-header">
                                <h2 class="section-title">{{__($item->name)}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-30-none">
                        @foreach($item->foods()->where('status',1)->get() as $data)
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 mb-30">
                                <div class="food-item">
                                    <div class="cart-button add-food-cart" data-foodid="{{$data->id}}">
                                        <div class="food-wrapper d-flex flex-wrap justify-content-between">
                                            <div class="food-content">
                                                <h4 class="title">{{__($data->name)}}</h4>
                                                <p>{{str_limit(__($data->details),140)}}</p>
                                                <span class="food-fee">{{__($general->cur_text)}} {{showAmount($data->price)}}</span>
                                            </div>
                                            <div class="food-thumb">
                                                <img src="{{ getImage(imagePath()['food']['path'].'/'. $data->image,imagePath()['food']['size'])}}" alt="food">
                                                <span class="cart-badge"><i class="las la-plus"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    <div id="loginMessage" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Login Required')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>@lang('Order requires login')</h5>
                </div>
                <div class="modal-footer">
                    <a href="{{route('user.login')}}" class="btn--base">@lang('Login Now')</a>
                </div>
            </div>
        </div>
    </div>

    <div id="infoModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg_img" data-background="{{ getImage(imagePath()['profile']['restaurant']['path'].'/'. $restaurant->image,imagePath()['profile']['restaurant']['size'])}}">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="restaurant-info-wrapper">
                        <div class="restaurants-details-content text-center">
                            <h2 class="title">{{__($restaurant->r_name)}}</h2>
                            <div class="ratings text--base">
                                <i class="las la-star"></i>
                                <span>{{$restaurant->avg_rating}}/5</span>
                            </div>
                            <ul class="restaurant-food-list">

                                @foreach ($restaurant->categories as $item)
                                    <li>{{__($item->name)}}</li>
                                @endforeach
                            </ul>
                            <div class="restaurant-time">
                                <span>@lang('Open') {{$restaurant->open_time}} - {{$restaurant->close_time}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="food-tab">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button"
                                    role="tab" aria-controls="about" aria-selected="true">@lang('About')</button>
                                <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" type="button" role="tab"
                                    aria-controls="review" aria-selected="false">@lang('Reviews')</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="about-tab">
                                <div class="delivery-area">
                                    <div class="delivery-time mb-10">
                                        <h4 class="title">@lang('Delivey hours')</h4>
                                        @php
                                            $dayTxt = '';
                                            foreach ($restaurant->days as $item) {
                                                if ($item == 1) {
                                                    $dayTxt.= 'Mon-';
                                                }
                                                if ($item == 2) {
                                                    $dayTxt.= 'Tue-';
                                                }
                                                if ($item == 3) {
                                                    $dayTxt.= 'Wed-';
                                                }
                                                if ($item == 4) {
                                                    $dayTxt.= 'Thu-';
                                                }
                                                if ($item == 5) {
                                                    $dayTxt.= 'Fri-';
                                                }
                                                if ($item == 6) {
                                                    $dayTxt.= 'Sat-';
                                                }
                                                if ($item == 7) {
                                                    $dayTxt.= 'Sun-';
                                                }
                                            }
                                        @endphp
                                        <span class="sub-title">{{substr($dayTxt,0,-1)}}</span> [<span>{{$restaurant->open_time}} - {{$restaurant->close_time}}</span>]
                                    </div>
                                    <div class="delivery-adress mb-10">
                                        <h4 class="title">@lang('Adress')</h4>
                                        <span class="sub-title">{{$restaurant->address->address}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                                <div class="food-review-area">
                                    <div class="food-review-header">
                                        @if ($restaurant->ratings->count() > 0)
                                            <h3 class="title">{{$restaurant->ratings->count()}} @lang('Reviews')</h3>
                                        @else
                                            <h3 class="title">@lang('No review found yet')</h3>
                                        @endif
                                    </div>
                                    @if ($restaurant->ratings->count() > 0)
                                        @foreach ($restaurant->ratings as $item)
                                            <div class="food-review-item">
                                                <div class="food-review-body">
                                                    <div class="left">
                                                        <h5 class="name">{{$item->user->getFullnameAttribute()}}</h5>
                                                        <span class="date">{{showDateTime($item->careated_at,'d/m/Y')}}</span>
                                                    </div>
                                                    <div class="right">
                                                        <div class="ratings">
                                                            <i class="las la-star"></i>
                                                            <span>{{$item->rating}}/5</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p>{{__($item->review)}}</p>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        (function ($) {

            @auth
                $(document).on('click', '.add-food-cart', function (e) {

                    $('.cart-sidebar-area').addClass('active');

                    var foodId = $(this).data('foodid');

                    $.ajax({
                        type: "get",
                        url: "{{route('user.orders.add')}}",
                        data: {food_id:foodId},
                        dataType: "json",

                        success: function (response) {

                            if (response.orders){
                                var html = ``;

                                $.each(response.orders, function (index, value) {

                                    html += `<div class="food-content">
                                                <h4 class="title">${value.food.name}</h4>
                                            </div>
                                            <div class="food-action d-flex flex-wrap align-items-center justify-content-between">
                                                <div class="food-action-text">
                                                    <span>@lang('Quantity')</span>
                                                </div>
                                                <div class="food-quantity">
                                                    <div class="food-plus-minus">
                                                        <div class="dec qtybutton minus-food-cart" data-foodid="${value.food.id}">-</div>
                                                        <input class="food-plus-minus-box integer-validation" type="text" name="foodqty" value="${value.qty}" readonly>
                                                        <div class="inc qtybutton add-food-cart" data-foodid="${value.food.id}">+</div>
                                                    </div>
                                                </div>
                                            </div>`;
                                });

                                $('.food-name-qty').html(html);

                                $(document).find('.subTotal').text(response.subTotal);
                                $(document).find('.deliveryFee').text(response.deliveryFee);
                                $(document).find('.vat').text(response.vat);
                                $(document).find('.total').text(response.total);

                            }else{
                                $('.cart-sidebar-area').removeClass('active');
                                notify('error', response.error);
                            }
                        }
                    });
                });

                $(document).on('click', '.minus-food-cart', function (e) {
                    $('.cart-sidebar-area').addClass('active');

                    var foodId = $(this).data('foodid');

                    $.ajax({
                        type: "get",
                        url: "{{route('user.orders.sub')}}",
                        data: {food_id:foodId},
                        dataType: "json",
                        success: function (response) {
                            if (response.orders){

                                var html = ``;

                                $.each(response.orders, function (index, value) {

                                    html += `<div class="food-content">
                                                <h4 class="title">${value.food.name}</h4>
                                            </div>
                                            <div class="food-action d-flex flex-wrap align-items-center justify-content-between">
                                                <div class="food-action-text">
                                                    <span>@lang('Quantity')</span>
                                                </div>
                                                <div class="food-quantity">
                                                    <div class="food-plus-minus">
                                                        <div class="dec qtybutton minus-food-cart" data-foodid="${value.food.id}">-</div>
                                                        <input class="food-plus-minus-box integer-validation" type="text" name="foodqty" value="${value.qty}" readonly>
                                                        <div class="inc qtybutton add-food-cart" data-foodid="${value.food.id}">+</div>
                                                    </div>
                                                </div>
                                            </div>`;
                                });

                                $('.food-name-qty').html(html);

                                $(document).find('.subTotal').text(response.subTotal);
                                $(document).find('.deliveryFee').text(response.deliveryFee);
                                $(document).find('.vat').text(response.vat);
                                $(document).find('.total').text(response.total);

                            }else{
                                $('.cart-sidebar-area').removeClass('active');
                                notify('error', response.error);
                            }
                        }
                    });
                });

                $(document).on("keypress", ".integer-validation", (function (e) {
                    var t = e.charCode ? e.charCode : e.keyCode;
                    if (t!=13 && 8 != t && (t < 2534 || t > 2543) && (t < 48 || t > 57)) return !1
                }));

                // food + - start here
                $(function () {
                    var CartPlusMinus = $('.food-plus-minus');
                    CartPlusMinus.prepend('<div class="dec qtybutton">-</div>');
                    CartPlusMinus.append('<div class="inc qtybutton">+</div>');

                    $(document).on("click",'.qtybutton', function () {
                        var $button = $(this);
                        var oldValue = $button.parent().find("input").val();
                        if ($button.text() === "+") {
                            var newVal = parseInt(oldValue) + 1;

                        } else {
                            // Don't allow decrementing below zero
                            if (oldValue > 1) {
                                var newVal = parseInt(oldValue) - 1;
                            } else {
                                newVal = 1;
                            }
                        }
                        $button.parent().find("input").val(newVal);
                    });
                });
            @else
                $('.add-food-cart').on('click', function() {
                    var modal = $('#loginMessage');
                    modal.modal('show');
                });
            @endauth

            $('.restaurant-info').on('click', function() {
                var modal = $('#infoModal');
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
