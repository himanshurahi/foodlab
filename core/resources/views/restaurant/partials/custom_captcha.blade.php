@php
	$captcha = loadCustomCaptcha();
@endphp
@if($captcha)
    <div class="form-group col-md-12">
        @php echo $captcha @endphp
        <input type="text" name="captcha" placeholder="@lang('Enter Code')" class="form-control mt-4 b-radius--capsule">
    </div>
@endif
