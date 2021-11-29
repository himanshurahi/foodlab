@extends('restaurant.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--19 b-radius--10 box-shadow" >
                <div class="icon">
                    <i class="las la-wallet"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{showAmount($totalBalance)}} {{$general->cur_text}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Balance')</span>
                    </div>
                    <a href="{{route('restaurant.transactions')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--primary b-radius--10 box-shadow">
                <div class="icon">
                    <i class="las la-list-ul"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$totalCategory}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Category')</span>
                    </div>
                    <a href="{{route('restaurant.category')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--cyan b-radius--10 box-shadow">
                <div class="icon">
                    <i class="las la-utensils"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$totalFood}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Food')</span>
                    </div>
                    <a href="{{route('restaurant.category')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--pink b-radius--10 box-shadow ">
                {{--  --}}
                <div class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$totalPendingOrders}}</span>
                    </div>
                    <div class="desciption">
                        <span class="text--small">@lang('Total Pending Orders')</span>
                    </div>

                    <a href="{{route('restaurant.orders.pending')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-50 mb-none-30">
        <div class="col-xl-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Monthly Sell')</h5>
                    <div id="apex-bar-chart"> </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script src="{{asset('assets/admin/js/vendor/apexcharts.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/vendor/chart.js.2.8.0.js')}}"></script>

    <script>
        "use strict";
        // apex-bar-chart js
        var options = {
            series: [{
                name: 'Total Sell',
                data: [
                  @foreach($months as $month)
                    {{ getAmount(@$ordersMonth->where('months',$month)->first()->orderAmount) }},
                  @endforeach
                ]
            }],
            chart: {
                type: 'bar',
                height: 450,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: @json($months),
            },
            yaxis: {
                title: {
                    text: "{{__($general->cur_sym)}}",
                    style: {
                        color: '#7c97bb'
                    }
                }
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                },
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "{{__($general->cur_sym)}}" + val + " "
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#apex-bar-chart"), options);
        chart.render();
    </script>
@endpush
