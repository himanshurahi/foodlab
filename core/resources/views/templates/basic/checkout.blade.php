@extends($activeTemplate.'layouts.frontend')
@section('content')
@include($activeTemplate.'user.breadcrumb')
    <section class="checkout-section ptb-60">
        <div class="container">
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-7 col-lg-7 col-md-8 mb-30">
                    <form action="{{route('user.placeorder')}}" method="POST">
                        @csrf
                        <div class="checkout-details-area">
                            <div class="checkout-details-header">
                                <h2 class="title">@lang('Order Details')</h2>
                            </div>
                            <div class="checkout-details-widget mb-20">
                                <h5 class="checkout-widget-title">@lang('Delivery Time') :</h5>
                                <div class="checkout-widget-content">
                                    <span class="delivery-date">{{\Carbon\Carbon::now()->parse()->format('D,  M y')}} (@lang('ASAP'))</span>
                                </div>
                            </div>
                            <div class="checkout-details-widget mb-20">
                                <div class="checkout-widget-header d-flex flex-wrap justify-content-between">
                                    <h5 class="checkout-widget-title">@lang('Personal Details') :</h5>
                                </div>
                                <div class="checkout-widget-content">
                                    <div class="personal-details mt-10">
                                        <h5 class="name">@lang('Name') : {{__($user->fullname)}}</h5>
                                        <span class="email">@lang('Email') : {{$user->email}}</span>
                                        <p class="number">@lang('Contact No') : {{$user->mobile}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout-details-widget mb-20">
                                <h5 class="checkout-widget-title">@lang('Delivery Adress') :</h5>
                                <div class="form-group">
                                    <label>@lang('Adress')*</label>
                                    <textarea class="form--control" placeholder="@lang('Enter your address')" name="d_address" required>{{@$user->address->address}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Message')</label>
                                    <textarea class="form--control" name="message" placeholder="@lang('Message for the restaurant if you have something to say')"></textarea>
                                </div>
                            </div>

                            <div class="checkout-details-widget mb-20">
                                <h5 class="checkout-widget-title">@lang('Payments') :</h5>


                                <input type="hidden" name="currency">
                                <input type="hidden" name="method_code">

                                <div class="form-group">

                                    <select name="payment_method" class="form--control" id="payment-methods" required>
                                        <option value="0"@if($total > $user->balance) disabled @endif>@lang('Own Balance') ({{$general->cur_sym}}{{showAmount($user->balance)}})</option>

                                        @foreach ($gatewayCurrency as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>


                                </div>

                                @if(count($restaurant->vouchars->where('status',1)) > 0)
                                    <div class="form-group vouchar-add-remove">
                                        <label><a href="javascript:void(0)" class="text--base vouchar-apply">@lang('Want to use voucher?')</a></label>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <button type="submit" class="submit-btn w-100 mt-20">@lang('Place Your Order')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-4 mb-30">
                    <div class="checkout-sidebar">
                        <div class="cart-header mb-20">
                            <h4 class="title text-center mb-0">@lang('Your Orders From') {{__($restaurant->r_name)}}</h4>
                        </div>
                        <div class="card-content">
                            <div class="food-item">
                                <div class="food-wrapper">
                                    <ul class="food-order-list">
                                        @foreach ($orders as $item)
                                            <li class="cart-item">{{$item['qty']}} x {{__($item['food']->name)}}<span>{{$general->cur_text}} {{showAmount($item['qty'] * $item['food']->price)}}</span></li>
                                        @endforeach

                                        <li>@lang('Subtotal') <span>{{$general->cur_text}} {{showAmount($subTotal)}}</span></li>
                                        <li>@lang('Delivery Fee') <span>{{$general->cur_text}} {{showAmount($deliveryFee)}}</span></li>
                                        <li>@lang('Vat') <span>{{$general->cur_text}} {{showAmount($vat)}}</span></li>
                                        <li>@lang('Discount') <span class="discount">{{$general->cur_text}} 0.00</span></li>
                                        <li><span class="fw-bold">@lang('Total(Incl.vat)')</span> <span class="fw-bold grand-total">{{$general->cur_text}} {{showAmount($total)}}</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="voucharModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Voucher Details')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" id="v-code" class="form--control" name="v_code" placeholder="@lang('Enter voucher code')">
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0)" class="btn--base vouchar-code-apply">@lang('Apply')</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        (function ($) {

            $(document).on('click', '.vouchar-apply', function() {
                var modal = $('#voucharModal');
                modal.modal('show');
            });

            $('.vouchar-code-apply').on('click', function() {
                var code = $('#v-code').val();

                if (code) {
                    $.ajax({
                        type: "get",
                        url: "{{route('user.vouchar.apply')}}",
                        data: {v_code:code},
                        dataType: "json",

                        success: function (response) {
                            if(response.success){

                                var modal = $('#voucharModal');
                                modal.modal('hide');

                                var html = `<label>@lang('Voucher') ${code} <a href="javascript:void(0)" class="text--base vouchar-remove"> @lang('Remove?')</a></label>`;

                                $(document).find('.discount').text('{{$general->cur_text}} ' + response.discount);
                                $(document).find('.grand-total').text('{{$general->cur_text}} ' + response.grandTotal);
                                $(document).find('.vouchar-add-remove').html(html);

                                notify('success', response.success);
                            }else{
                                notify('error', response.error);
                            }
                        }
                    });
                }else{
                    notify('error', 'Use a voucher code');
                }
            });

            $(document).on('click', '.vouchar-remove', function() {
                $.ajax({
                    type: "get",
                    url: "{{route('user.vouchar.remove')}}",
                    data: "",
                    dataType: "json",

                    success: function (response) {
                        if(response.success){

                            var html = `<label><a href="javascript:void(0)" class="text--base vouchar-apply">@lang('Want to use voucher?')</a></label>`;
                            $(document).find('.vouchar-add-remove').html(html);

                            $(document).find('.discount').text('{{$general->cur_text}} 0.00');
                            $(document).find('.grand-total').text('{{$general->cur_text}} ' + response.total);

                        }else{
                            notify('error', response.error);
                        }
                    }
                });
            });

            $('#payment-methods').on('change',function(){

                var methodCode = $(this).find('option:selected').data('methodcode');
                var currency = $(this).find('option:selected').data('currency');

                $( "input[name=currency]").val(currency);
                $( "input[name=method_code]").val(methodCode);

            }).change();
        })(jQuery);
    </script>
@endpush
