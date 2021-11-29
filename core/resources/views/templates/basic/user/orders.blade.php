@extends($activeTemplate.'layouts.frontend')

@section('content')
@include($activeTemplate.'user.breadcrumb')
<section class="table-section ptb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="table-area">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>@lang('Order Code')</th>
                                <th>@lang('Delivery Time')</th>
                                <th>@lang('Order Status')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $key => $item)

                                <tr>
                                    <td data-label="@lang('Order Code')">{{$item->order_code}}</td>
                                    <td data-label="@lang('Delivery Time')">{{\Carbon\Carbon::parse($item->created_at)->format('d M, Y')}}</td>
                                    <td data-label="@lang('Order Status')">
                                        @if ($item->status == 1)
                                            <span class="badge badge--primary text-white">@lang('Confirmed')</span>
                                        @elseif ($item->status == 2)
                                            <span class="badge badge--success text-white">@lang('Delivered')</span>
                                        @elseif ($item->status == 3)
                                            <span class="badge badge--danger text-white">@lang('Canceled')</span>
                                        @elseif ($item->status == 4)
                                            <span class="badge badge--warning text-white">@lang('Pending')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Price')">{{$general->cur_sym}}{{showAmount($item->total)}}</td>
                                    <td data-label="@lang('Action')">
                                        @if ($item->status == 1)
                                            <a href="{{route('user.confirm.delivery',Crypt::encrypt($item->id))}}" class="badge badge--success text-white" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="@lang('Make Delivered')"><i class="las la-truck"></i></a>
                                        @endif

                                        @if (($item->status == 2) || ($item->status == 3) || ($item->status == 4))
                                            <a href="{{route('user.order.details',Crypt::encrypt($item->id))}}" class="badge badge--info text-white" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="@lang('Order Details')"><i class="las la-info-circle"></i></a>
                                        @endif

                                        @if (($item->status == 2) && (!auth()->user()->existedRating($item->id)))
                                            <a href="#0" class="badge badge--primary text-white reviewBtn" data-id="{{$item->id}}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="@lang('Give Review')"><i class="las la-star-half-alt"></i></a>
                                        @endif

                                        @if (($item->status == 3) && ($item->cancel_message))
                                            <a href="#0" class="badge badge--danger text-white feedback" data-feedback="{{$item->cancel_message}}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="@lang('Feedback')"><i class="las la-comment-dots"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{__($emptyMessage)}}</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
                {{$orders->links()}}
            </div>
        </div>
    </div>
</section>

<div id="feedback-modal" class="modal fade" role="dialog">
    <div class="modal-dialog ">
        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title">@lang('Feedback')</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body ">
                <p id="feedback-message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn--base close" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

@if(count($orders) > 0)
    <div class="modal fade cmn--modal" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{route('user.rating')}}" method="POST">
                    @csrf
                        <div class="modal-header">
                            <h5 class="m-0">@lang('Review')</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('Give your rating')</label><br>
                                <div class='starrr' id='star{{ $key }}'></div><br>
                                <input type='hidden' name='rating' value='0' id='star2_input'>
                                <input type="hidden" name="order_id" value="">
                            </div>
                            <div class="form-group">
                                <label>@lang('Write your opinion')</label><br>
                                <textarea name="review" rows="5" class="form--control" required></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn--base" data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn--base">@lang('Yes')</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection

@push('script-lib')
    <script src="{{asset($activeTemplateTrue.'js/starrr.js')}}"></script>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.feedback').on('click', function() {
                var modal = $('#feedback-modal');
                var feedback = $(this).data('feedback');
                modal.find('#feedback-message').text(feedback);
                modal.modal('show');
            });

            $('.reviewBtn').on('click', function () {
                var modal = $('#reviewModal');
                console.log('ok');
                modal.find('input[name=order_id]').val($(this).data('id'));

                var $s2input = $('input[name=rating]');
                var indx = @php echo $orders->count() @endphp;
                var i = 0;
                for (i; i < indx; i++) {
                    $(`#star${i}`).starrr({
                        max: 5,
                        rating: $s2input.val(),
                        change: function(e, value){
                            $s2input.val(value).trigger('input');
                        }
                    });
                }

                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
