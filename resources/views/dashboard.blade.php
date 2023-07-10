@extends('layouts.master')
@section('title')
    {{ trans('general.dashboard') }}
@endsection

@section('content')
    @if(Sentinel::inRole('client'))
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text"> {{ trans_choice('general.loan',2) }} {{ trans_choice('general.disbursed',1) }}</span>
                        <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::client_total_disbursed_loans_amount(Sentinel::getUser()->id),2)}}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ trans_choice('general.total',2) }} {{ trans_choice('general.outstanding',2) }}</span>
                        <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::client_total_loans_outstanding_amount(Sentinel::getUser()->id),2)}}</span>
                    </div>
                </div>
            </div>
            <div class="clearfix visible-sm-block"></div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-minus"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">{{ trans_choice('general.in',2) }} {{ trans_choice('general.arrears',2) }}</span>
                        <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::client_total_loans_overdue_amount(Sentinel::getUser()->id),2)}}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text"> {{ trans_choice('general.savings',2) }} {{ trans_choice('general.balance',1) }}</span>
                        <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::total_client_savings_account_balance(Sentinel::getUser()->id),2)}}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(!Sentinel::inRole('client'))
        <div class="row">
            @if(Sentinel::hasAccess('dashboard.loans_disbursed'))
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"> {{ trans_choice('general.loan',2) }} {{ trans_choice('general.disbursed',1) }}</span>
                            <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::total_disbursed_loans_amount(),2)}}</span>
                        </div>
                    </div>
                </div>
            @endif


            @if(Sentinel::hasAccess('dashboard.my_disbursed'))
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"> {{ trans_choice('general.my',1) }}  {{ trans_choice('general.disbursed',1) }} {{ trans_choice('general.loan',2) }}</span>
                            <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::officer_total_disbursed_loans_amount(),2)}}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if(Sentinel::hasAccess('dashboard.my_branch_disbursed'))
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"> {{ trans_choice('general.branch',1) }}  {{ trans_choice('general.disbursed',1) }} {{ trans_choice('general.loan',2) }}</span>
                            <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::branch_total_disbursed_loans_amount(),2)}}</span>
                        </div>
                    </div>
                </div>
            @endif


            @if(Sentinel::hasAccess('dashboard.total_repayments'))
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ trans_choice('general.total',2) }} {{ trans_choice('general.repayment',2) }}</span>
                            <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::total_loans_repayments_amount(),2)}}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if(Sentinel::hasAccess('dashboard.my_repayments_loans'))
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ trans_choice('general.my',2) }} {{ trans_choice('general.loan',2) }} {{ trans_choice('general.repayment',2) }}</span>
                            <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::officer_total_loans_repayments_amount(),2)}}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if(Sentinel::hasAccess('dashboard.my_branch_repayments'))
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ trans_choice('general.branch',1) }} {{ trans_choice('general.loan',2) }} {{ trans_choice('general.repayment',2) }}</span>
                            <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::branch_total_loans_repayments_amount(),2)}}</span>
                        </div>
                    </div>
                </div>
            @endif
      
            @if(Sentinel::hasAccess('dashboard.total_outstanding'))
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ trans_choice('general.total',2) }} {{ trans_choice('general.outstanding',2) }}</span>
                            <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::total_loans_outstanding_amount(),2)}}</span>
                        </div>
                    </div>
                </div>
            @endif
          



            @if(Sentinel::hasAccess('dashboard.my_outstanding_loans'))
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ trans_choice('general.my',1) }} {{ trans_choice('general.total',2) }} {{ trans_choice('general.outstanding',2) }}</span>
                            <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::officer_total_loans_outstanding_amount(),2)}}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if(Sentinel::hasAccess('dashboard.my_branch_outstanding'))
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ trans_choice('general.branch',1) }} {{ trans_choice('general.total',2) }} {{ trans_choice('general.outstanding',2) }}</span>
                            <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::branch_total_loans_outstanding_amount(),2)}}</span>
                        </div>
                    </div>
                </div>
            @endif




            @if(Sentinel::hasAccess('dashboard.amount_in_arrears'))
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-minus"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">{{ trans_choice('general.in',2) }} {{ trans_choice('general.arrears',2) }}</span>
                            <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::total_loans_overdue_amount(),2)}}</span>
                        </div>
                    </div>
                </div>
            @endif
            @if(Sentinel::hasAccess('dashboard.my_loan_arrears'))
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-minus"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">{{ trans_choice('general.my',2) }} {{ trans_choice('general.loan',2) }} {{ trans_choice('general.arrears',2) }}</span>
                            <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::officer_total_loans_overdue_amount(),2)}}</span>
                        </div>
                    </div>
                </div>
            @endif
     

            @if(Sentinel::hasAccess('dashboard.my_branch_arrears'))
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-minus"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">{{ trans_choice('general.branch',1) }} {{ trans_choice('general.loan',2) }} {{ trans_choice('general.arrears',2) }}</span>
                            <span class="info-box-number">{{number_format(\App\Helpers\GeneralHelper::branch_total_loans_overdue_amount(),2)}}</span>
                        </div>
                    </div>
                </div>
            @endif
    
        </div>
        <div class="row">
            @if(Sentinel::hasAccess('dashboard.loans_status_overview'))
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans_choice('general.loan',2) }} {{ trans_choice('general.status',1) }} {{ trans_choice('general.overview',2) }}</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" id="">
                            <div id="loans_status_graph" style="height: 300px;"></div>

                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            @endif
            @if(Sentinel::hasAccess('dashboard.clients_overview'))
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans_choice('general.client',2) }} {{ trans_choice('general.overview',2) }}</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" id="">
                            <div id="registered_clients_graph" style="height: 300px;"></div>

                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            @endif
            @if(Sentinel::hasAccess('dashboard.savings_balances_overview'))
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans_choice('general.savings',2) }} {{ trans_choice('general.balance',2) }} {{ trans_choice('general.overview',2) }}</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" id="">
                            <div id="savings_balance_graph" style="height: 300px;"></div>

                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            @if(Sentinel::hasAccess('dashboard.collection_statistics'))
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans_choice('general.collection',1) }} {{ trans_choice('general.statistic',2) }}</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" id="">
                            <div class="row text-center">
                                <?php
                                $target = 0;
                                foreach (\App\Models\LoanRepaymentSchedule::where('year', date("Y"))->where('month',
                                    date("m"))->get() as $key) {
                                    $target = $target + $key->principal - $key->principal_waived - $key->principal_written_off + $key->interest - $key->interest_waived - $key->interest_written_off + $key->fees - $key->fees_waived - $key->fees_written_off + $key->penalty - $key->penalty_waived - $key->penalty_written_off;
                                }
                                $paid_this_month = \App\Models\LoanTransaction::where('transaction_type',
                                    'repayment')->where('reversed', 0)->where('year', date("Y"))->where('month',
                                    date("m"))->sum('credit');
                                if ($target > 0) {
                                    $percent = round(($paid_this_month / $target) * 100);
                                } else {
                                    $percent = 0;
                                }
                                ?>
                                <div class="col-md-4">
                                    <div class="content-group">

                                        <h5 class="text-semibold no-margin">
                                            {{ number_format(\App\Models\LoanTransaction::where('transaction_type','repayment')->where('reversed', 0)->where('date',date("Y-m-d"))->sum('credit'),2) }}
                                        </h5>
                                        <span class="text-muted text-size-small">{{ trans_choice('general.today',1) }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="content-group">

                                        <h5 class="text-semibold no-margin">
                                            {{ number_format(\App\Models\LoanTransaction::where('transaction_type',
                                'repayment')->where('reversed', 0)->whereBetween('date',array('date_sub(now(),INTERVAL 1 WEEK)','now()'))->sum('credit'),2) }}
                                        </h5>
                                        <span class="text-muted text-size-small">{{ trans_choice('general.last',1) }} {{ trans_choice('general.week',1) }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="content-group">

                                        <h5 class="text-semibold no-margin">{{ number_format($paid_this_month,2) }}</h5>
                                        <span class="text-muted text-size-small">{{ trans_choice('general.this',1) }} {{ trans_choice('general.month',1) }}</span>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h5 class=" text-semibold">{{ trans_choice('general.monthly',1) }} {{ trans_choice('general.target',1) }}</h5>
                                    </div>
                                    <div class="progress" data-toggle="tooltip"
                                         title="{{ trans_choice('general.target',1) }} : {{number_format($target,2)}}">

                                        <div class="progress-bar progress-bar-success progress-bar-striped active"
                                             style="width: {{$percent}}%">
                                            <span>{{$percent}}% {{ trans_choice('general.complete',1) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-center">{{ trans_choice('general.collection',1) }} {{ trans_choice('general.overview',2) }}</h3>
                                    <div id="collection_statistics_graph" style="height: 300px;"></div>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->
                    </div>

                </div>
            @endif
            <div class="col-md-4">
                <div class="row">
                    <?php $fees_penalty = \App\Helpers\GeneralHelper::fees_penalty_earned_paid(); ?>
                    @if(Sentinel::hasAccess('dashboard.fees_earned'))
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-thumbs-up"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"> {{ trans_choice('general.fee',2) }} {{ trans_choice('general.earned',1) }}</span>
                                    <span class="info-box-number">{{number_format($fees_penalty["fees"],2)}}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Sentinel::hasAccess('dashboard.fees_paid'))
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-thumbs-up"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"> {{ trans_choice('general.fee',2) }} {{ trans_choice('general.paid',1) }}</span>
                                    <span class="info-box-number">{{number_format($fees_penalty["fees_paid"],2)}}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Sentinel::hasAccess('dashboard.penalties_earned'))
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-thumbs-up"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"> {{ trans_choice('general.penalty',2) }} {{ trans_choice('general.earned',1) }}</span>
                                    <span class="info-box-number">{{number_format($fees_penalty["penalty"],2)}}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Sentinel::hasAccess('dashboard.penalties_paid'))
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-thumbs-up"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"> {{ trans_choice('general.penalty',2) }} {{ trans_choice('general.paid',1) }}</span>
                                    <span class="info-box-number">{{number_format($fees_penalty["penalty_paid"],2)}}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection
@section('footer-scripts')
    <script src="{{ asset('assets/plugins/amcharts/amcharts.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/serial.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/pie.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/funnel.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/themes/light.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/plugins/export/export.min.js') }}"
            type="text/javascript"></script>
    @if(!Sentinel::inRole('client'))
        <script>
            var chart = AmCharts.makeChart("registered_clients_graph", {
                "type": "funnel",
                "theme": "light",
                "dataProvider": {!! \App\Helpers\GeneralHelper::client_numbers_graph() !!},
                "balloon": {
                    "fixedPosition": false
                },
                "valueField": "value",
                "titleField": "title",
                "marginRight": 130,
                "marginLeft": 0,
                "startX": 0,
                "rotate": true,
                "labelPosition": "right",
                "balloonText": "[[title]]: [[value]] [[description]]",
                "export": {
                    "enabled": true,
                    "libs": {
                        "path": "{{asset('assets/plugins/amcharts/plugins/export/libs')}}/"
                    }
                }
            });
            var chart = AmCharts.makeChart("loans_status_graph", {
                "type": "pie",
                "theme": "light",
                "dataProvider": {!! \App\Helpers\GeneralHelper::loans_status_graph() !!},
                "balloon": {
                    "fixedPosition": false
                },
                "valueField": "value",
                "titleField": "title",
                "marginRight": 20,
                "marginLeft": 20,
                "radius": 60,
                "startX": 0,
                "fontSize": 10,
                "rotate": true,
                "labelPosition": "right",
                "balloonText": "[[title]]: [[value]] [[description]]",
                "export": {
                    "enabled": true,
                    "libs": {
                        "path": "{{asset('assets/plugins/amcharts/plugins/export/libs')}}/"
                    }
                },
                legend: {
                    display: true,
                    labels: {
                        fontColor: 'rgb(255, 99, 132)'
                    }
                }
            });
            var chart = AmCharts.makeChart("savings_balance_graph", {
                "type": "serial",
                "theme": "light",
                "dataProvider": {!! \App\Helpers\GeneralHelper::savings_balance_graph() !!},
                "balloon": {
                    "fixedPosition": false
                },
                "startDuration": 1,
                "graphs": [{
                    "balloonText": "[[category]]: <b>[[value]]</b>",
                    "fillAlphas": 0.8,
                    "lineAlpha": 0.2,
                    "type": "column",
                    "valueField": "value"
                }],
                "chartCursor": {
                    "categoryBalloonEnabled": false,
                    "cursorAlpha": 0,
                    "zoomable": false
                },
                "categoryField": "title",
                "categoryAxis": {
                    "gridPosition": "start",
                    "gridAlpha": 0,
                    "tickPosition": "start",
                    "tickLength": 20
                },
                "labelPosition": "right",
                "balloonText": "[[title]]: [[value]] [[description]]",
                "export": {
                    "enabled": true,
                    "libs": {
                        "path": "{{asset('assets/plugins/amcharts/plugins/export/libs')}}/"
                    }
                }
            });
            AmCharts.makeChart("collection_statistics_graph", {
                "type": "serial",
                "theme": "light",
                "autoMargins": true,
                "marginLeft": 30,
                "marginRight": 8,
                "marginTop": 10,
                "marginBottom": 26,
                "fontFamily": 'Open Sans',
                "color": '#888',

                "dataProvider": {!! \App\Helpers\GeneralHelper::collection_overview_graph() !!},
                "valueAxes": [{
                    "axisAlpha": 0,

                }],
                "startDuration": 1,
                "graphs": [{
                    "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b> [[value]]</b> [[additional]]</span>",
                    "bullet": "round",
                    "bulletSize": 8,
                    "lineColor": "#370fc6",
                    "lineThickness": 4,
                    "negativeLineColor": "#0dd102",
                    "title": "{{trans_choice('general.actual',1)}}",
                    "type": "smoothedLine",
                    "valueField": "actual"
                }, {
                    "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b> [[value]]</b> [[additional]]</span>",
                    "bullet": "round",
                    "bulletSize": 8,
                    "lineColor": "#d1655d",
                    "lineThickness": 4,
                    "negativeLineColor": "#d1cf0d",
                    "title": "{{trans_choice('general.expected',2)}}",
                    "type": "smoothedLine",
                    "valueField": "expected"
                }],
                "categoryField": "month",
                "categoryAxis": {
                    "gridPosition": "start",
                    "axisAlpha": 0,
                    "tickLength": 0,
                    "labelRotation": 30,

                }, "export": {
                    "enabled": true,
                    "libs": {
                        "path": "{{asset('assets/plugins/amcharts/plugins/export/libs')}}/"
                    }
                }, "legend": {
                    "position": "bottom",
                    "marginRight": 100,
                    "autoMargins": false
                },


            });
        </script>
    @endif
@endsection