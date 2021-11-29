@extends('restaurant.layouts.app')

@section('panel')

    <div class="row mb-none-30">

        <div class="col-xl-12 col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('Restaurant Information')</h5>

                    <form action="{{ route('restaurant.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Cover Image')</label>
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview" style="background-image: url({{ getImage(imagePath()['profile']['restaurant']['path'].'/'.$restaurant->image,imagePath()['profile']['restaurant']['size']) }})">
                                                    <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                <label for="profilePicUpload1" class="bg--success">@lang('Upload Image')</label>
                                                <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg'), @lang('jpg').</b> @lang('Image will be resized into ')<b>{{imagePath()['profile']['restaurant']['size']}}</b> @lang('px') </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-control-label font-weight-bold">@lang('First Name') <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="firstname" value="{{ $restaurant->firstname }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-control-label font-weight-bold">@lang('Last Name') <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="lastname" value="{{ $restaurant->lastname }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-control-label font-weight-bold">@lang('E-mail')</label>
                                        <input class="form-control" type="text" value="{{ $restaurant->email }}" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-control-label font-weight-bold">@lang('Mobile No')</label>
                                        <input class="form-control" type="text" value="{{ $restaurant->mobile }}" disabled>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <h4>@lang('Business Details')</h4>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="form-control-label font-weight-bold">@lang('Restaurant Name')</label>
                                        <input class="form-control" type="text" value="{{ $restaurant->r_name }}" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-control-label font-weight-bold">@lang('Business Location')</label>
                                        <input class="form-control" type="text" value="{{ $restaurant->location->name }}" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-control-label font-weight-bold">@lang('Country')</label>
                                        <input class="form-control" type="text" value="{{ @$restaurant->address->country }}" disabled>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="form-control-label font-weight-bold">@lang('Address') <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="address" value="{{ @$restaurant->address->address }}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label font-weight-bold">@lang('State') <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="state" value="{{ @$restaurant->address->state }}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label font-weight-bold">@lang('Zip Code') <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="zip" value="{{ @$restaurant->address->zip }}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label font-weight-bold">@lang('City') <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="city" value="{{ @$restaurant->address->city }}" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-control-label font-weight-bold">@lang('Opening Time') <span class="text-danger">*</span></label>
                                        <input class="form-control timepicker" type="text" name="open_time" value="{{ $restaurant->open_time }}" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-control-label font-weight-bold">@lang('Closing Time') <span class="text-danger">*</span></label>
                                        <input class="form-control timepicker" type="text" name="close_time" value="{{ $restaurant->close_time }}" required>
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
                                            <input class="form-control" type="number" name="d_time" value="{{ $restaurant->d_time }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Service Days') <span class="text-danger">*</span></label>
                                            <select class="select2-multi-select" name="days[]" multiple="multiple" required>
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
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('restaurant.password')}}" class="btn btn-sm btn--primary box--shadow1 text--small" ><i class="fa fa-key"></i>@lang('Password Setting')</a>
@endpush

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
            $('.select2-multi-select').select2({
                dropdownParent: $('.card-body form')
            });
        })(jQuery)
    </script>
@endpush

