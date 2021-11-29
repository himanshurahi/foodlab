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
                                    <th scope="col">@lang('Restaurant')</th>
                                    <th scope="col">@lang('Type')</th>
                                    <th scope="col">@lang('Discount')</th>
                                    <th scope="col">@lang('Code')</th>
                                    <th scope="col">@lang('Minmum Limit')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse ($vouchars as $item)
                                    <tr>
                                        <td data-label="@lang('SL')">{{ $loop->index+1 }}</td>
                                        <td data-label="@lang('Restaurant')">{{ $item->restaurant->r_name }}</td>
                                        <td data-label="@lang('Type')">
                                            @if ($item->type == 1)
                                                <span class="badge badge--warning">@lang('Fixed')</span>
                                            @elseif ($item->type == 2)
                                                <span class="badge badge--primary">@lang('Percentage')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Discount')">
                                            @if ($item->fixed)
                                                {{showAmount($item->fixed)}} {{$general->cur_text}}
                                            @elseif ($item->percentage)
                                                {{$item->percentage}} %
                                            @endif
                                        </td>
                                        <td data-label="@lang('Code')">{{ $item->code }}</td>
                                        <td data-label="@lang('Minmum Limit')">{{ showAmount($item->min_limit) }}</td>
                                        <td data-label="@lang('Status')">
                                            @if ($item->status == 1)
                                                <span class="badge badge--primary">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Deactive')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Action')">
                                            <a href="javascript:void(0)" class="icon-btn updateBtn" data-route="{{ route('admin.vouchar.update',$item->id) }}" data-resourse="{{$item}}" data-toggle="modal" data-target="#updateBtn" ><i class="la la-pencil-alt"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ $emptyMessage }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $vouchars->links('admin.partials.paginate') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Add METHOD MODAL --}}
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Add New Vouchar')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.vouchar.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Select Restaurant') <span class="text-danger">*</span></label>
                            <select name="restaurant_id" class="form-control" required>
                                @foreach ($restaurants as $item)
                                    <option value="{{$item->id}}">{{__($item->r_name)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Type') <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" id="type-add" required>
                                <option value="1">@lang('Fixed')</option>
                                <option value="2">@lang('Percentage')</option>
                            </select>
                        </div>

                        <div class="form-group" id="fixed-percentage-add">
                            <label>@lang('Enter Discount Amount') <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="any" class="form-control" placeholder="0" name="fixed" required/>
                                <div class="input-group-append">
                                    <div class="input-group-text"><span
                                        class="currency_symbol">{{ $general->cur_text }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Minmum Order') <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="any" class="form-control" placeholder="0" name="min_limit" required>
                                <div class="input-group-append">
                                    <div class="input-group-text"><span
                                        class="currency_symbol">{{ $general->cur_text }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Vouchar Code') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Deactive')" name="status" checked>
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
                    <h5 class="modal-title"> @lang('Update Vouchar')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" class="edit-route" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Select Restaurant') <span class="text-danger">*</span></label>
                            <select name="restaurant_id" class="form-control" required>
                                @foreach ($restaurants as $item)
                                    <option value="{{$item->id}}">{{__($item->r_name)}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('Type') <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" id="type-update" required>
                                <option value="1">@lang('Fixed')</option>
                                <option value="2">@lang('Percentage')</option>
                            </select>
                        </div>

                        <div class="form-group" id="fixed-percentage-update">
                            <label>@lang('Enter Discount Amount') <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="any" class="form-control" placeholder="0" name="fixed" required/>
                                <div class="input-group-append">
                                    <div class="input-group-text"><span
                                        class="currency_symbol">{{ $general->cur_text }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Minmum Order') <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="any" class="form-control min-limit" placeholder="0" name="min_limit" required>
                                <div class="input-group-append">
                                    <div class="input-group-text"><span
                                        class="currency_symbol">{{ $general->cur_text }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Vouchar Code') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control v-code" name="code" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <input id="edit-status" class="status" type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Deactive')" name="status">
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

    @push('breadcrumb-plugins')
        <a href="javascript:void(0)" class="btn btn--primary mr-3 mt-2 addBtn"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
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

        $('#type-add').on('change',function(){
            var value = $(this).find('option:selected').val();

            if (value == 1) {
                var fixedDiscountDiv = `<label>@lang('Enter Discount Amount') <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control" placeholder="0" name="fixed" required/>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><span
                                                    class="currency_symbol">{{ $general->cur_text }}</span>
                                                </div>
                                            </div>
                                        </div>`;
                $('#fixed-percentage-add').html(fixedDiscountDiv);
            }

            if (value == 2) {
                var percentageDiscountDiv = `<label>@lang('Enter Discount Percentage') <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" class="form-control" placeholder="0" name="percentage" required/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><span
                                                        class="currency_symbol">%</span>
                                                    </div>
                                                </div>
                                            </div>`;
                $('#fixed-percentage-add').html(percentageDiscountDiv);
            }
        });

        $('.updateBtn').on('click', function () {
            var modal = $('#updateBtn');

            var resourse = $(this).data('resourse');
            modal.find($('select[name="type"]').val(resourse.type));
            modal.find($('select[name="restaurant_id"]').val(resourse.restaurant_id));

            var route = $(this).data('route');
            $('.min-limit').val(parseFloat(resourse.min_limit).toFixed(2));
            $('.v-code').val(resourse.code);

            if(resourse.status == 1){
                $('#edit-status').parent('div').removeClass('off');
                $('#edit-status').prop('checked', true);
            }else{
                $('#edit-status').parent('div').addClass('off');
                $('#edit-status').prop('checked', false);
            }

            $('.edit-route').attr('action',route);

            checkType(resourse);
        });

        function checkType(resourse) {
            $('#type-update').on('change',function(){
                var value = $(this).find('option:selected').val();

                if (value == 1) {

                var fixedDiscountDiv = `<label>@lang('Enter Discount Amount') <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control" placeholder="0" value="${parseFloat(resourse.fixed).toFixed(2)}" name="fixed" required/>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><span
                                                    class="currency_symbol">{{ $general->cur_text }}</span>
                                                </div>
                                            </div>
                                        </div>`;
                    $('#fixed-percentage-update').html(fixedDiscountDiv);
                }

                if (value == 2) {
                    var percentageDiscountDiv = `<label>@lang('Enter Discount Percentage') <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" class="form-control" placeholder="0" value="${parseFloat(resourse.percentage).toFixed(2)}" name="percentage" required/>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text"><span
                                                            class="currency_symbol">%</span>
                                                        </div>
                                                    </div>
                                                </div>`;
                    $('#fixed-percentage-update').html(percentageDiscountDiv);
                }

            }).change();
        }
    })(jQuery);
</script>
@endpush
