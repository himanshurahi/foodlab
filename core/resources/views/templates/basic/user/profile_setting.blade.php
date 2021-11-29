@extends($activeTemplate.'layouts.frontend')
@section('content')
    @php
        $bannerContent = getContent('banner.content',true);
    @endphp

    @include($activeTemplate.'user.breadcrumb')
    <div class="card-area ptb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-9">
                    <div class="card custom--card">
                        <div class="card-form-wrapper">
                            <form class="prevent-double-click" action="" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="profile-settings-wrapper">
                                    <div class="preview-thumb profile-wallpaper">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview bg_img" data-background="{{ getImage('assets/images/frontend/banner/'.@$bannerContent->data_values->image,'1920x1090') }}"></div>
                                        </div>
                                    </div>
                                    <div class="profile-thumb-content">
                                        <div class="preview-thumb profile-thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview bg_img" data-background="{{ getImage(imagePath()['profile']['user']['path'].'/'. $user->image,imagePath()['profile']['user']['size']) }}">
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type='file' class="profilePicUpload" name="image" id="profilePicUpload2" accept="image/*">
                                                <label for="profilePicUpload2"><i class="las la-pen"></i></label>
                                            </div>
                                        </div>
                                        <div class="profile-content">
                                            <h6 class="username text--base">{{$user->fullname}}</h6>
                                            <ul class="user-info-list mt-md-2">
                                                <li><i class="las la-envelope"></i>{{$user->email}}</li>
                                                <li><i class="las la-phone"></i> {{$user->mobile}}</li>
                                                <li><i class="las la-map-marked-alt"></i> {{@$user->address->country}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row justify-content-center mb-20-none">
                                        <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                            <label>@lang('First Name')*</label>
                                            <input type="text" class="form-control" name="firstname" placeholder="@lang('First Name')" value="{{$user->firstname}}">
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                            <label>@lang('Last Name')*</label>
                                            <input type="text" class="form-control" name="lastname" placeholder="@lang('Last Name')" value="{{$user->lastname}}">
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                            <label>@lang('Address')*</label>
                                            <input type="text" class="form-control" name="address" placeholder="@lang('Address')" value="{{@$user->address->address}}" required>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                            <label>@lang('State')*</label>
                                            <input type="text" class="form-control" name="state" placeholder="@lang('state')" value="{{@$user->address->state}}" required>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                            <label>@lang('Zip Code')*</label>
                                            <input type="text" class="form-control" name="zip" placeholder="@lang('Zip Code')" value="{{@$user->address->zip}}" required>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                            <label>@lang('City')*</label>
                                            <input type="text" class="form-control" name="city" placeholder="@lang('City')" value="{{@$user->address->city}}" required>
                                        </div>
                                        <div class="col-xl-12 form-group">
                                            <button type="submit" class="submit-btn w-100">@lang('Update Profile')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    "use strict";
    function proPicURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var preview = $(input).parents('.preview-thumb').find('.profilePicPreview');
                $(preview).css('background-image', 'url(' + e.target.result + ')');
                $(preview).addClass('has-image');
                $(preview).hide();
                $(preview).fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".profilePicUpload").on('change', function () {
        proPicURL(this);
    });

    $(".remove-image").on('click', function () {
        $(".profilePicPreview").css('background-image', 'none');
        $(".profilePicPreview").removeClass('has-image');
    })
</script>
@endpush
