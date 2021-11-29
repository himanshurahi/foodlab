@extends('admin.layouts.app')

@section('panel')

    <div class="row">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('SL')</th>
                                    <th scope="col">@lang('Image')</th>
                                    <th scope="col">@lang('Name')</th>
                                    <th scope="col">@lang('Price')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse ($foods as $item)
                                    <tr>
                                        <td data-label="@lang('SL')">{{ $loop->index+1 }}</td>
                                        <td data-label="@lang('Image')">
                                            <div class="user justify-content-center">
                                                <div class="thumb"><img src="{{ getImage(imagePath()['food']['path'].'/'. $item->image,imagePath()['food']['size'])}}" alt="@lang('image')"></div>
                                            </div>
                                        </td>
                                        <td data-label="@lang('Name')">{{ $item->name }}</td>
                                        <td data-label="@lang('Price')">{{ showAmount($item->price) }} {{ $general->cur_text }}</td>
                                        <td data-label="@lang('Action')">

                                            <a href="javascript:void(0)" class="icon-btn updateBtn"  data-route="{{ route('admin.restaurants.food.update',$item->id) }}" data-resourse="{{$item}}" data-toggle="modal" data-target="#updateBtn" data-image="{{ getImage(imagePath()['food']['path'].'/'. $item->image,imagePath()['food']['size'])}}" ><i class="la la-pencil-alt"></i></a>

                                            @if ($item->status == 0)
                                                <button type="button"
                                                        class="icon-btn btn--success ml-1 activateBtn"
                                                        data-toggle="modal" data-target="#activateModal"
                                                        data-id="{{$item->id}}"
                                                        data-original-title="@lang('Enable')">
                                                        <i class="la la-eye"></i>
                                                </button>
                                            @else
                                                <button type="button"
                                                        class="icon-btn btn--danger ml-1 deactivateBtn"
                                                        data-toggle="modal" data-target="#deactivateModal"
                                                        data-id="{{$item->id}}"
                                                        data-original-title="@lang('Disable')">
                                                        <i class="la la-eye-slash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $foods->links('admin.partials.paginate') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Add METHOD MODAL --}}
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Add New Food')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.restaurants.food.store',$category->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"><code>@lang('Before adding food, make sure that this restaurant really does have this food and a valid restaurant opening, closing time and service days.')</code></label>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Name') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control check-length" data-length="40" placeholder="@lang('Example : Enter food name')" value="{{ old('name') }}" name="name" required>
                            <span class="remaining float-right"> @lang('40 characters remaining')</span>
                        </div>

                        <div class="form-group">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('Details') <span class="text-danger">*</span></label>
                                <textarea name="details" class="check-length form-control" data-length="191" placeholder="@lang('Enter Food Details')" rows="4">{{ old('details') }}</textarea>
                                <span class="remaining float-right"> @lang('191 characters remaining')</span>
                            </div>
                        </div>

                        <div class="form-group">
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

                        <div class="form-group">
                            <b>@lang('Food Image')</b>
                            <div class="image-upload mt-2">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image: url({{ getImage('',imagePath()['food']['size']) }})">
                                            <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="avatar-edit">
                                        <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg" required>
                                        <label for="profilePicUpload1" class="bg--success"> @lang('Upload')</label>
                                        <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg, jpg, png')</b>.
                                        @lang('Image Will be resized to'): <b>{{imagePath()['food']['size']}}</b> @lang('px').

                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Update METHOD MODAL --}}
    <div id="updateBtn" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Update Food')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" class="edit-route" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"><code>@lang('Before updating food, make sure that this restaurant really does have this food and a valid restaurant opening, closing time and service days.')</code></label>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Name') <span class="text-danger">*</span></label>
                            <input type="text"class="form-control check-length name" data-length="40" placeholder="@lang('Example : Enter food name')" name="name" required>
                            <span class="remaining float-right"> @lang('40 characters remaining')</span>
                        </div>

                        <div class="form-group">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('Details') <span class="text-danger">*</span></label>
                                <textarea name="details" class="check-length details form-control" data-length="191" placeholder="@lang('Enter Food Details')" rows="4"></textarea>
                                <span class="remaining float-right"> @lang('191 characters remaining')</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Price') <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">{{ $general->cur_text }}</div>
                                </div>
                                <input type="number" step="any" class="form-control price" placeholder="0" name="price" required/>
                                <div class="input-group-append">
                                    <div class="input-group-text"><span
                                        class="currency_symbol">{{ $general->cur_sym }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <b>@lang('Food Image')</b>
                            <div class="image-upload mt-2">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview update-image-preview">
                                            <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="avatar-edit">
                                        <input type="file" class="profilePicUpload" name="image" id="profilePicUpload2" accept=".png, .jpg, .jpeg">
                                        <label for="profilePicUpload2" class="bg--success"> @lang('Upload')</label>
                                        <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg, jpg, png')</b>.
                                        @lang('Image Will be resized to'): <b>{{imagePath()['food']['size']}}</b> @lang('px').

                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ACTIVATE METHOD MODAL --}}
    <div id="activateModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Food Activation Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.restaurants.food.activate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to activate this food?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Activate')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DEACTIVATE METHOD MODAL --}}
    <div id="deactivateModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Food Disable Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.restaurants.food.deactivate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to disable this food')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Disable')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('breadcrumb-plugins')
        <a href="javascript:void(0)" class="btn btn--primary mr-3 mt-2 addBtn"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>

        @if(request()->routeIs('admin.restaurants.foods'))
            <form action="{{ route('admin.restaurants.food.search',$category->id) }}" method="GET" class="form-inline float-sm-right bg--white mt-2">
                <div class="input-group has_append">
                    <input type="text" name="search" class="form-control" placeholder="@lang('Food Name')" value="{{ request()->search??null }}">
                    <div class="input-group-append">
                        <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
        @else
            <form action="{{ route('admin.restaurants.food.search',$category->id) }}" method="GET" class="form-inline float-sm-right bg--white mt-2">
                <div class="input-group has_append">
                    <input type="text" name="search" class="form-control" placeholder="@lang('Food Name')" value="{{ request()->search??null }}">
                    <div class="input-group-append">
                        <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
        @endif
    @endpush
@endsection

@push('script')
<script>
    'use strict';

    $('.check-length').on('input', function(){
        let maxLength = $(this).data('length');
        let currentLength = $(this).val().length;

        let remain = maxLength - currentLength;
        let result =  `${remain} characters remaining`;
        let remainElement = $(this).parent('.form-group').find('.remaining');

        remainElement.css({
            fontWeight: 'bold',
            fontSize: '14px',
            display: 'block',
            textAlign: 'end',
        });

        if(remain <= 20){
            remainElement.css('color', 'red');
        }else if(remain <= 50){
            remainElement.css('color', 'green');
        }else{
            remainElement.css('color', 'black');
        }

        remainElement.html(`${remain} characters remaining`);
    });

    $('.check-length').on('keypress', function(){
        let maxLength = $(this).data('length');
        let currentLength = $(this).val().length;

        if(currentLength >= maxLength){
            return false;
        }
    });

    (function ($) {
        $('.addBtn').on('click', function () {
            var modal = $('#addModal');
            modal.modal('show');
        });

        $('.updateBtn').on('click', function () {
            var modal = $('#updateBtn');

            var resourse = $(this).data('resourse');

            var route = $(this).data('route');
            $('.name').val(resourse.name);
            $('.details').val(resourse.details);
            $('.price').val(parseFloat(resourse.price).toFixed(2));
            $('.update-image-preview').css({"background-image": "url("+$(this).data('image')+")"});
            $('.edit-route').attr('action',route);

            var nameLength = modal.find('.name').val().length;
            modal.find('.name').parent('.form-group').find('.remaining').text(40 - nameLength + 'characters remaining');
            var detailsLength = modal.find('.details').val().length;
            modal.find('.details').parent('.form-group').find('.remaining').text(191 - detailsLength + 'characters remaining');


        });

        $('.activateBtn').on('click', function () {
            var modal = $('#activateModal');
            modal.find('input[name=id]').val($(this).data('id'));
        });

        $('.deactivateBtn').on('click', function () {
            var modal = $('#deactivateModal');
            modal.find('input[name=id]').val($(this).data('id'));
        });

    })(jQuery);
</script>
@endpush
