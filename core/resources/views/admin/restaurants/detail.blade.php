@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-md-12">

            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body p-0">
                    <div class="p-3 bg--white">
                        <div class="">
                            <img src="{{ getImage(imagePath()['profile']['restaurant']['path'].'/'.$restaurant->image,imagePath()['profile']['restaurant']['size'])}}" alt="@lang('Profile Image')" class="b-radius--10 w-100">
                        </div>
                        <div class="mt-15">
                            <h4 class="">{{$restaurant->r_name}}</h4>
                            <span class="text--small">@lang('Joined At') <strong>{{showDateTime($restaurant->created_at,'d M, Y h:i A')}}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-5 col-md-5 mb-30">
            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Restaurant information')</h5>
                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="font-weight-bold">{{$restaurant->username}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if($restaurant->status == 1)
                                <span class="badge badge-pill bg--success">@lang('Active')</span>
                            @elseif($restaurant->status == 0)
                                <span class="badge badge-pill bg--danger">@lang('Banned')</span>
                            @endif
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Balance')
                            <span class="font-weight-bold">{{showAmount($restaurant->balance)}}  {{__($general->cur_text)}}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Restaurant action')</h5>
                    <a data-toggle="modal" href="#addSubModal" class="btn btn--success btn--shadow btn-block btn-lg">
                        @lang('Add/Subtract Balance')
                    </a>
                    <a href="{{ route('admin.restaurants.login.history.single', $restaurant->id) }}"
                       class="btn btn--primary btn--shadow btn-block btn-lg">
                        @lang('Login Logs')
                    </a>
                    <a href="{{route('admin.restaurants.email.single',$restaurant->id)}}"
                       class="btn btn--info btn--shadow btn-block btn-lg">
                        @lang('Send Email')
                    </a>
                    <a href="{{route('admin.restaurants.login',$restaurant->id)}}" target="_blank" class="btn btn--dark btn--shadow btn-block btn-lg">
                        @lang('Login as Restaurant')
                    </a>
                    <a href="{{route('admin.restaurants.email.log',$restaurant->id)}}" class="btn btn--warning btn--shadow btn-block btn-lg">
                        @lang('Email Log')
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 mb-30">

            <div class="row mb-none-30 mt-30">
                <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--deep-purple b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.restaurants.categories',$restaurant->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="las la-utensils"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$totalFood}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Food')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->


                <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--indigo b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.restaurants.withdrawals',$restaurant->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="fa fa-wallet"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="currency-sign">{{__($general->cur_sym)}}</span>
                                <span class="amount">{{showAmount($totalWithdraw)}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Withdraw')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->

                <div class="col-xl-4 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--12 b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.restaurants.transactions',$restaurant->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-exchange-alt"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$totalTransaction}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Transaction')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->
            </div>

            <div class="card mt-50">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('Information of') {{$restaurant->fullname}}</h5>

                    <form action="{{route('admin.restaurants.update',[$restaurant->id])}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('First Name')<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="firstname" value="{{$restaurant->firstname}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Last Name') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="lastname" value="{{$restaurant->lastname}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Email') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" value="{{$restaurant->email}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile Number') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="mobile" value="{{$restaurant->mobile}}">
                                </div>
                            </div>
                        </div>


                        <div class="row mt-3">
                            <div class="col-md-12 mb-2">
                                <h4>@lang('Business Details')</h4>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Restaurant Name')</label>
                                    <input class="form-control" type="text" name="r_name" value="{{ $restaurant->r_name }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Business Location')</label>
                                    <select name="location_id" class="form-control" id="location" required>
                                        @foreach ($locations as $item)
                                            <option value="{{$item->id}}">{{__($item->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Address') </label>
                                    <input class="form-control" type="text" name="address" value="{{@$restaurant->address->address}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('City') </label>
                                    <input class="form-control" type="text" name="city" value="{{@$restaurant->address->city}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('State') </label>
                                    <input class="form-control" type="text" name="state" value="{{@$restaurant->address->state}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Zip/Postal') </label>
                                    <input class="form-control" type="text" name="zip" value="{{@$restaurant->address->zip}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Country') </label>
                                    <select name="country" class="form-control">
                                        @foreach($countries as $key => $country)
                                            <option value="{{ $key }}" @if($country->country == @$restaurant->address->country ) selected @endif>{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Opening Time') <span class="text-danger">*</span></label>
                                <input class="form-control timepicker" type="text" name="open_time" value="{{ $restaurant->open_time }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Closing Time') <span class="text-danger">*</span></label>
                                <input class="form-control timepicker" type="text" name="close_time" value="{{ $restaurant->close_time }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Delivery Charge') <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">{{ $general->cur_text }}</div>
                                    </div>
                                    <input type="number" step="any" class="form-control" placeholder="0" name="d_charge" value="{{ getAmount($restaurant->d_charge) }}" required/>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span
                                            class="currency_symbol">{{ $general->cur_sym }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Vat') <code>[@lang('Percentage of Total Bill')]</code> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" placeholder="0" name="vat" value="{{ getAmount($restaurant->vat) }}" required/>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span
                                            class="currency_symbol">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Delivery Time') <code>[@lang('minutes')]</code> <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="d_time" value="{{ $restaurant->d_time }}">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Service Days') <span class="text-danger">*</span></label>
                                    <select class="select2-multi-select" name="days[]" multiple="multiple">
                                            <option value="1" @if($restaurant->days) @if(in_array('1',$restaurant->days)) selected @endif @endif>@lang('Monday')</option>
                                            <option value="2" @if($restaurant->days) @if(in_array('2',$restaurant->days)) selected @endif @endif>@lang('Tuesday')</option>
                                            <option value="3" @if($restaurant->days) @if(in_array('3',$restaurant->days)) selected @endif @endif>@lang('Wednesday')</option>
                                            <option value="4" @if($restaurant->days) @if(in_array('4',$restaurant->days)) selected @endif @endif>@lang('Thursday')</option>
                                            <option value="5" @if($restaurant->days) @if(in_array('5',$restaurant->days)) selected @endif @endif>@lang('Friday')</option>
                                            <option value="6" @if($restaurant->days) @if(in_array('6',$restaurant->days)) selected @endif @endif>@lang('Saturday')</option>
                                            <option value="7" @if($restaurant->days) @if(in_array('7',$restaurant->days)) selected @endif @endif>@lang('Sunday')</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('Status') </label>
                                <input type="checkbox" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Banned')" data-width="100%"
                                       name="status"
                                       @if($restaurant->status) checked @endif>
                            </div>

                            <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('Email Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="ev"
                                       @if($restaurant->ev) checked @endif>

                            </div>

                            <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('SMS Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="sv"
                                       @if($restaurant->sv) checked @endif>

                            </div>
                            <div class="form-group  col-xl-4 col-md-6 col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('2FA Status') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Deactive')" name="ts"
                                       @if($restaurant->ts) checked @endif>
                            </div>

                            <div class="form-group  col-xl-4 col-md-6 col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('2FA Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="tv"
                                       @if($restaurant->tv) checked @endif>
                            </div>

                            <div class="form-group  col-xl-4 col-md-6 col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('Featured') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="featured"
                                       @if($restaurant->featured) checked @endif>
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{-- Add Sub Balance MODAL --}}
    <div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add / Subtract Balance')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.restaurants.add.sub.balance', $restaurant->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Add Balance')" data-off="@lang('Subtract Balance')" name="act" checked>
                            </div>


                            <div class="form-group col-md-12">
                                <label>@lang('Amount')<span class="text-danger">*</span></label>
                                <div class="input-group has_append">
                                    <input type="text" name="amount" class="form-control" placeholder="@lang('Please provide positive amount')">
                                    <div class="input-group-append">
                                        <div class="input-group-text">{{ __($general->cur_sym) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/restaurant/css/bootstrap-material-datetimepicker-bs4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/restaurant/css/Material+Icons.css')}}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/restaurant/js/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('assets/restaurant/js/bootstrap-material-datetimepicker-bs4.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        var timePicker = function () {
            $('.timepicker').bootstrapMaterialDatePicker({
                format: 'HH:mm',
                shortTime: false,
                date: false,
                time: true,
                monthPicker: false,
                year: false,
                switchOnClick: true
            });
        }

        timePicker();

        (function($){
            $('#location').val('{{$restaurant->location_id}}');

            $('.select2-multi-select').select2({
                dropdownParent: $('.card-body form')
            });
        })(jQuery)
    </script>
@endpush


