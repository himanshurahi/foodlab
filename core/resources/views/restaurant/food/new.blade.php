@extends('restaurant.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h5>@lang('Food Image')</h5>
                                    <div class="image-upload mt-2">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview" style="background-image: url({{ getImage('',imagePath()['food']['size'])}})">
                                                    <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg" required>
                                                <label for="profilePicUpload1" class="bg--success"> @lang('Image')</label>
                                                <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg, jpg, png')</b>.
                                                @lang('Image Will be resized to'): <b>{{imagePath()['food']['size']}}</b> @lang('px')

                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <label class="form-control-label font-weight-bold">@lang('Name') <span class="text-danger">*</span></label>
                                        <input type="text"class="form-control" placeholder="@lang('Example : Enter food name')" value="{{ old('name') }}" name="name" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label font-weight-bold">@lang('Price') <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">{{ $general->cur_text }}</div>
                                            </div>
                                            <input type="number" step="any" class="form-control" placeholder="0" name="price" value="{{ old('price') }}" required/>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><span
                                                    class="currency_symbol">{{ $general->cur_sym }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-control-label font-weight-bold">@lang('Details') <span class="text-danger">*</span></label>
                                            <textarea name="details" placeholder="@lang('Enter Food Details')" rows="4"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

{{-- @push('breadcrumb-plugins')
    <a href="{{route('admin.product.index')}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush --}}
