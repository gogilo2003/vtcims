@extends('admin::layout.main')

@section('title')
    E-School::Dashboard
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> E-School::Dashboard
@endsection

@section('breadcrumbs')
    @parent
    <li class="active"><span><i class="fa fa-list-alt"></i> E-School</span></li>
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    {{-- @include('admin::some-additional sidebar items') --}}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">person_outline</i>
                    </div>
                    <p class="card-category">Male Trainees</p>
                    <h3 class="card-title"><span id="male_trainees"></span>
                        <small>Students</small>
                    </h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons text-info">date_range</i>
                        Details
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">person</i>
                    </div>
                    <p class="card-category">Female Trainees</p>
                    <h3 class="card-title"><span id="female_trainees"></span>
                        <small>Students</small>
                    </h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons text-warning">date_range</i>
                        Details
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">group</i>
                    </div>
                    <p class="card-category">Total Trainees</p>
                    <h3 class="card-title"><span id="total_trainees"></span>
                        <small>Students</small>
                    </h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons text-success">date_range</i>
                        Details
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-4">
            <div class="card card-chart">
                <div class="card-header card-header-primary">
                    <!--<div class="ct-chart" id="dailySalesChart"></div>-->
                    <canvas class="Chart" id="currentEnrolment0"></canvas>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Current Enrolment</h4>
                    <p class="card-category">
                        General Current Enrolment
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-4">
            <div class="card card-chart">
                <div class="card-header card-header-primary">
                    <!--<div class="ct-chart" id="dailySalesChart"></div>-->
                    <canvas class="Chart" id="currentEnrolment1"></canvas>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Enrolment Status Statistics</h4>
                    <p class="card-category">
                        Overal Enrolment status.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-4">
            <div class="card card-chart">
                <div class="card-header card-header-primary">
                    <!--<div class="ct-chart" id="dailySalesChart"></div>-->
                    <canvas class="Chart" id="currentEnrolment2"></canvas>
                </div>
                <div class="card-body">
                    <h4 class="card-title">General Annual Enrolment</h4>
                    <p class="card-category">
                        General Annual Enrolment.
                    </p>
                </div>
                <div class="card-footer">
                    <a href="" class="btn btn-fab btn-round btn-primary"><i class="material-icons">bubble_chart</i></a>
                    <a href="{{ route('admin-eschool-students-enrolment') }}" class="btn btn-fab btn-round btn-success"><i
                            class="material-icons">cloud_download</i></a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style type="text/css">

    </style>
@endsection
@section('scripts_top')
    <script type="text/javascript">

    </script>
@endsection

@section('scripts_bottom')
    <script type="text/javascript"
        src="{{ url(config('admin.path_prefix') . '/vendor/admin/material-dashboard-master/assets/js/plugins/Chart.min.js') }}">
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            /**
             * Get the overal stats
             */
            $.post("{{ route('api-eschool-stats-totals') }}", {
                api_token: '{{ api_token() }}'
            }).then(function(response) {
                $('span#male_trainees').text(response.male)
                $('span#female_trainees').text(response.female)
                $('span#total_trainees').text(response.total)
                // console.log(response)
            }, function(error) {
                console.log(error)
            })

            $.post("{{ route('api-eschool-stats-enrolment') }}", {
                api_token: '{{ api_token() }}'
            }).then(function(response) {

                var ctx = document.getElementById('currentEnrolment0').getContext('2d');
                let data = {
                    labels: response.labels,
                    datasets: [{
                        backgroundColor: ['royalblue', 'green', 'orange', 'purple', 'yellow',
                            'beige', 'red'
                        ],
                        brderWidth: 0,
                        data: response.data,
                    }]
                }

                var chart = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'pie',

                    // The data for our dataset
                    data,

                    // Configuration options go here
                    options: {
                        cutoutPercentage: 0,
                        legend: {
                            position: 'left',
                            fullWidth: false,
                            labels: {
                                boxWidth: 16
                            }
                        },
                        // title: {
                        //     display: true,
                        //     position: 'top',
                        //     text: 'Overal browsers Statistics'
                        // }
                    }
                });
            })

            $.post("{{ route('api-eschool-stats-enrolment-status') }}", {
                api_token: '{{ api_token() }}'
            }).then(function(response) {

                var ctx = document.getElementById('currentEnrolment1').getContext('2d');
                let data = {
                    labels: response.labels,
                    datasets: [{
                        backgroundColor: ['royalblue', 'green', 'orange', 'purple', 'yellow',
                            'beige', 'red'
                        ],
                        brderWidth: 0,
                        data: response.data,
                    }]
                }

                var chart = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'pie',

                    // The data for our dataset
                    data,

                    // Configuration options go here
                    options: {
                        cutoutPercentage: 0,
                        legend: {
                            position: 'left',
                            fullWidth: false,
                            labels: {
                                boxWidth: 16
                            }
                        },
                        // title: {
                        //     display: true,
                        //     position: 'top',
                        //     text: 'Overal browsers Statistics'
                        // }
                    }
                });
            })

            $.post("{{ route('api-eschool-stats-enrolment-yearly') }}", {
                api_token: '{{ api_token() }}'
            }).then(function(response) {

                var ctx = document.getElementById('currentEnrolment2').getContext('2d');
                let data = {
                    labels: response.labels,
                    datasets: response.datasets,
                }

                var chart = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'bar',

                    // The data for our dataset
                    data,

                    // Configuration options go here
                    options: {
                        cutoutPercentage: 0,
                        legend: {
                            position: 'bottom',
                            fullWidth: false,
                            labels: {
                                boxWidth: 16
                            }
                        },
                        // title: {
                        //     display: true,
                        //     position: 'top',
                        //     text: 'Overal browsers Statistics'
                        // }
                    }
                });
            }, function(error) {
                console.log(error)
            })

        })
    </script>
@endsection
