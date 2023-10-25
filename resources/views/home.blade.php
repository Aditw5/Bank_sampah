@extends('layouts.admin')
@section('header', 'Home')

@section('css')
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
@endsection

@section('content')

<div class="row">
    <!-- Box 1: Total Books -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{$total_product}}</h3>
                <p>Total Rubbish</p>
            </div>
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
            <a href="{{url('products')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- Box 2: Total Members -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{$total_member}}</h3>
                <p>Total Members</p>
            </div>
            <div class="icon">
                <i class="fas fa-user"></i>
            </div>
            <a href="{{url('members')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>



    <!-- Box 4: Total Transactions -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{$total_purchase}}</h3>
                <p>Total Purchase</p>
            </div>
            <div class="icon">
                <i class="fas fa-download"></i>
            </div>
            <a href="{{url('purchases')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title">Rubbish Trend</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="rubbishChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('js')
<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('assets/dist/js/adminlte.min.js?v=3.2.0')}}"></script>
<script src="{{asset('assets/dist/js/demo.js')}}"></script>
<script>
    var data_rubbish = {!! json_encode($data_rubbish) !!};
    var label_rubbish = {!! json_encode($labels) !!};
    
    console.log(data_rubbish);

    $(function () {
        // Author Chart Data
        var authorData = {
            labels: label_rubbish,
            datasets: [
                {
                    data: data_rubbish,
                    backgroundColor: [
                        '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de',
                        '#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#fd7e14', '#20c997',
                        '#17a2b8', '#ffc107', '#343a40', '#6c757d', '#343a40', '#6c757d',
                    ],
                },
            ],
        };

        var rubbishChartCanvas = $('#rubbishChart').get(0).getContext('2d');

        var authorOptions = {
            maintainAspectRatio: false,
            responsive: true,
        };

        new Chart(rubbishChartCanvas, {
            type: 'doughnut',
            data: authorData,
            options: authorOptions,
        });
    });
</script>
@endsection
