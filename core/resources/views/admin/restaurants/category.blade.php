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
                                    <th scope="col">@lang('Name')</th>
                                    <th scope="col">@lang('Food Count')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse ($categories as $item)
                                    <tr>
                                        <td data-label="@lang('SL')">{{ $loop->index+1 }}</td>
                                        <td data-label="@lang('Name')">{{ $item->name }}</td>
                                        <td data-label="@lang('Food Count')">{{ $item->foods->count() }}</td>
                                        <td data-label="@lang('Action')">

                                            <a href="{{ route('admin.restaurants.foods',$item->id) }}" class="icon-btn btn--info mr-1" data-toggle="tooltip" title="@lang('Manage Food')" data-original-title="@lang('Manage Food')">
                                                <i class="las la-utensils text--shadow"></i>
                                            </a>

                                            <a href="javascript:void(0)" class="icon-btn updateBtn"  data-route="{{ route('admin.restaurants.category.update',[$item->id,$restaurant->id]) }}" data-resourse="{{$item}}" data-toggle="modal" data-target="#updateBtn" ><i class="la la-pencil-alt"></i></a>

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
                    {{ $categories->links('admin.partials.paginate') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Add METHOD MODAL --}}
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Add New Category')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.restaurants.category.store',$restaurant->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"><code>@lang('Before adding food category, make sure that this restaurant really does have food under this category and a valid restaurant opening, closing time and service days.')</code></label>
                        </div>
                        <div class="form-group">
                            <label>@lang('Category Name')</label>
                            <input type="text"class="form-control" placeholder="@lang('Enter Name')" name="name" required>
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
                    <h5 class="modal-title"> @lang('Update Category')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" class="edit-route" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"><code>@lang('Before updating food category, make sure that this restaurant really does have food under this category and a valid restaurant opening, closing time and service days.')</code></label>
                        </div>
                        <div class="form-group">
                            <label>@lang('Category Name')</label>
                            <input type="text"class="form-control name" placeholder="@lang('Enter Name')" name="name" required>
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
                    <h5 class="modal-title">@lang('Category Activation Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.restaurants.category.activate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="restaurant_id" value="{{$restaurant->id}}">
                    <div class="modal-body">
                        <p>@lang('Are you sure to activate this category?')</p>
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
                    <h5 class="modal-title">@lang('Category Disable Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.restaurants.category.deactivate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="restaurant_id" value="{{$restaurant->id}}">
                    <div class="modal-body">
                        <p>@lang('Are you sure to disable this category')</p>
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

        @if(request()->routeIs('admin.restaurants.categories'))
            <form action="{{ route('admin.restaurants.category.search',$restaurant->id) }}" method="GET" class="form-inline float-sm-right bg--white mt-2">
                <div class="input-group has_append">
                    <input type="text" name="search" class="form-control" placeholder="@lang('Category Name')" value="{{ request()->search??null }}">
                    <div class="input-group-append">
                        <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
        @else
            <form action="{{ route('admin.restaurants.category.search',$restaurant->id) }}" method="GET" class="form-inline float-sm-right bg--white mt-2">
                <div class="input-group has_append">
                    <input type="text" name="search" class="form-control" placeholder="@lang('Category Name')" value="{{ request()->search??null }}">
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
            $('.edit-route').attr('action',route);

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
