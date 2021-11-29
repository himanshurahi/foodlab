@extends('restaurant.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-4 col-md-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Current Balance') : <b>{{ showAmount(auth()->guard('restaurant')->user()->balance)}}  {{ __($general->cur_text) }}</b></h5>

                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Requested Amount')
                            <span class="font-weight-bold">{{showAmount($withdraw->amount)  }} {{__($general->cur_text)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-danger">@lang('Withdrawal Charge')</span>
                            <span class="font-weight-bold text-danger">{{showAmount($withdraw->charge) }} {{__($general->cur_text)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-info">@lang('After Charge')</span>
                            <span class="font-weight-bold text-info">{{showAmount($withdraw->after_charge) }} {{__($general->cur_text)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Conversion Rate')
                            <span class="font-weight-bold">1 {{__($general->cur_text)}} = {{showAmount($withdraw->rate)  }} {{__($withdraw->currency)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-success">@lang('You Will Get')</span>
                            <span class="font-weight-bold text-success">{{showAmount($withdraw->final_amount) }} {{__($withdraw->currency)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-primary">@lang('Balance Will be')</span>
                            <span class="font-weight-bold text-primary">{{showAmount($withdraw->restaurant->balance - ($withdraw->amount))}} {{__($general->cur_text)}}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-md-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <form action="{{route('restaurant.withdraw.submit')}}" method="post" enctype="multipart/form-data">
                        @csrf

                        @if($withdraw->method->user_data)
                            @foreach($withdraw->method->user_data as $k => $v)
                                @if($v->type == "text")
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold">{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</label>

                                        <input class="form-control" type="text" name="{{$k}}" value="{{old($k)}}" placeholder="{{__($v->field_level)}}" @if($v->validation == "required") required @endif>

                                        @if ($errors->has($k))
                                            <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                        @endif
                                    </div>
                                @elseif($v->type == "textarea")
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold">{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</label>

                                        <textarea class="form-control" name="{{$k}}" placeholder="{{__($v->field_level)}}" @if($v->validation == "required") required @endif>{{old($k)}}</textarea>

                                        @if ($errors->has($k))
                                            <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                        @endif
                                    </div>
                                @elseif($v->type == "file")
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold">{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</label>

                                        <input class="form-control" type="file" name="{{$k}}" accept="image/*" @if($v->validation == "required") required @endif>
                                    </div>
                                @endif

                            @endforeach
                        @endif

                        @if(auth()->guard('restaurant')->user()->ts)
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('Google Authenticator Code') <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="authenticator_code" required>
                            </div>
                        @endif

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Confirm')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
