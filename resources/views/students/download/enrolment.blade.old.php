@extends('layout.pdf')

@section('title')
Students' Register
@endsection

@section('content')
<canvas id="currentEnrolment0" style="width:80%; height:600px; background-color: #ccc"></canvas>
@endsection

@section('scripts_bottom')
<script type="text/javascript">
    @php
    print(file_get_contents(public_path('vendor/admin/material-dashboard-master/assets/js/core/jquery.min.js')));
    @endphp
</script>
<script type="text/javascript">
    @php
    print(file_get_contents(public_path('vendor/admin/material-dashboard-master/assets/js/core/popper.min.js')));
    @endphp
</script>
<script type="text/javascript">
    @php
    print(file_get_contents(public_path('vendor/admin/material-dashboard-master/assets/js/plugins/Chart.min.js')));
    @endphp
</script>
<script type="text/javascript">

    $(document).ready(function () {

        // $.post({url:"{{ route('api-eschool-stats-enrolment') }}",dataType:"JSON"}).then(function (response) {

        var ctx = document.getElementById('currentEnrolment0').getContext('2d');
        let data = {
            labels: {!! json_encode($labels) !!
    },
        datasets: [{
            backgroundColor: ['royalblue', 'green', 'orange', 'purple', 'yellow',
                'beige', 'red'
            ],
            brderWidth: 0,
            data: {!! json_encode($data) !!},
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
        // })
    })
</script>
@endsection