@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="pcoded-content">

    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="icon feather icon-home bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Dashboard</h5>
                        <span>All statistics</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0)"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0)">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                  
                    
                  

                    {{-- <div class="row">
                        <div class="col-xl-3 col-md-6">
                        <a href="{{route('list.vehicle')}}">
                            <div class="card prod-p-card card-red">
                                <div class="card-body">
                                    <div class="row align-items-center m-b-30">
                                        <div class="col">
                                            <h6 class="m-b-5 text-white">Total Vehicle</h6>
                                            <h3 class="m-b-0 f-w-700 text-white">{{$total_vehicle}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>
                       
                        <div class="col-xl-3 col-md-6">
                        <a href="{{route('list.driver')}}">
                            <div class="card prod-p-card card-blue">
                                <div class="card-body">
                                    <div class="row align-items-center m-b-30">
                                        <div class="col">
                                            <h6 class="m-b-5 text-white">Total Driver</h6>
                                            <h3 class="m-b-0 f-w-700 text-white">{{$total_driver}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card prod-p-card card-success">
                                <div class="card-body">
                                    <div class="row align-items-center m-b-30">
                                        <div class="col">
                                            <h6 class="m-b-5 text-white">Driver Due Documents</h6>
                                            <h3 class="m-b-0 f-w-700 text-white">0</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card prod-p-card card-warning">
                                <div class="card-body">
                                    <div class="row align-items-center m-b-30">
                                        <div class="col">
                                            <h6 class="m-b-5 text-white">Vehicle Due Documents</h6>
                                            <h3 class="m-b-0 f-w-700 text-white">0</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <div class="col-xl-3 col-md-6">
                        <a href="{{route('transport.trip.list')}}">
                            <div class="card prod-p-card card-blue">
                                <div class="card-body">
                                    <div class="row align-items-center m-b-30">
                                        <div class="col">
                                            <h6 class="m-b-5 text-white">Total Trip</h6>
                                            <h3 class="m-b-0 f-w-700 text-white">{{$total_trip}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>

                        <div class="col-xl-3 col-md-6">
                        <a href="{{route('transport.trip.list',['is_market_lr'=>1])}}">
                            <div class="card prod-p-card card-info">
                                <div class="card-body">
                                    <div class="row align-items-center m-b-30">
                                        <div class="col">
                                            <h6 class="m-b-5 text-white">Market Trip</h6>
                                            <h3 class="m-b-0 f-w-700 text-white">{{$market_trip}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>

                        <div class="col-xl-3 col-md-6">
                        <a href="{{route('transport.trip.list',['bill_id'=>0])}}">
                            <div class="card prod-p-card card-red">
                                <div class="card-body">
                                    <div class="row align-items-center m-b-30">
                                        <div class="col">
                                            <h6 class="m-b-5 text-white">Pending Bill Trip</h6>
                                            <h3 class="m-b-0 f-w-700 text-white">{{$bill_pending_trip}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>

                    </div>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                            <a href="{{route('bill.list')}}">
                                <div class="card prod-p-card card-info">
                                    <div class="card-body">
                                        <div class="row align-items-center m-b-30">
                                            <div class="col">
                                                <h6 class="m-b-5 text-white">Pending Bill</h6>
                                                <h3 class="m-b-0 f-w-700 text-white">{{$bill_pending}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            </div>
                        </div>    --}} 

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        
     
        
        /*Bar chart*/
    // var data1 = {
    //     labels: ['July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb','Mar','Apr','May','Jun'],
    //     datasets: [{
    //         label: "Professional Avenue",
    //         backgroundColor: [
    //             'rgba(95, 190, 170, 0.99)',
    //             'rgba(95, 190, 170, 0.99)',
    //             'rgba(95, 190, 170, 0.99)',
    //             'rgba(95, 190, 170, 0.99)',
    //             'rgba(95, 190, 170, 0.99)',
    //             'rgba(95, 190, 170, 0.99)',
    //             'rgba(95, 190, 170, 0.99)',
    //             'rgba(95, 190, 170, 0.99)',
    //             'rgba(95, 190, 170, 0.99)',
    //             'rgba(95, 190, 170, 0.99)',
    //             'rgba(95, 190, 170, 0.99)',
    //             'rgba(95, 190, 170, 0.99)'
    //         ],
    //         hoverBackgroundColor: [
    //             'rgba(26, 188, 156, 0.88)',
    //             'rgba(26, 188, 156, 0.88)',
    //             'rgba(26, 188, 156, 0.88)',
    //             'rgba(26, 188, 156, 0.88)',
    //             'rgba(26, 188, 156, 0.88)',
    //             'rgba(26, 188, 156, 0.88)',
    //             'rgba(26, 188, 156, 0.88)',
    //             'rgba(26, 188, 156, 0.88)',
    //             'rgba(26, 188, 156, 0.88)',
    //             'rgba(26, 188, 156, 0.88)',
    //             'rgba(26, 188, 156, 0.88)',
    //             'rgba(26, 188, 156, 0.88)',
    //             'rgba(26, 188, 156, 0.88)'
    //         ],
    //         data: [2, 3, 5, 1, 6, 1, 8,3,1,7,3,7],
    //     }, {
    //         label: "International Avenue",
    //         backgroundColor: [
    //             'rgba(93, 156, 236, 0.93)',
    //             'rgba(93, 156, 236, 0.93)',
    //             'rgba(93, 156, 236, 0.93)',
    //             'rgba(93, 156, 236, 0.93)',
    //             'rgba(93, 156, 236, 0.93)',
    //             'rgba(93, 156, 236, 0.93)',
    //             'rgba(93, 156, 236, 0.93)',
    //             'rgba(93, 156, 236, 0.93)',
    //             'rgba(93, 156, 236, 0.93)',
    //             'rgba(93, 156, 236, 0.93)',
    //             'rgba(93, 156, 236, 0.93)',
    //             'rgba(93, 156, 236, 0.93)'
    //         ],
    //         hoverBackgroundColor: [
    //             'rgba(103, 162, 237, 0.82)',
    //             'rgba(103, 162, 237, 0.82)',
    //             'rgba(103, 162, 237, 0.82)',
    //             'rgba(103, 162, 237, 0.82)',
    //             'rgba(103, 162, 237, 0.82)',
    //             'rgba(103, 162, 237, 0.82)',
    //             'rgba(103, 162, 237, 0.82)',
    //             'rgba(103, 162, 237, 0.82)',
    //             'rgba(103, 162, 237, 0.82)',
    //             'rgba(103, 162, 237, 0.82)',
    //             'rgba(103, 162, 237, 0.82)',
    //             'rgba(103, 162, 237, 0.82)'
    //         ],
    //         data: [2, 4, 7, 3, 2, 9, 2,5,3,1,8,2],
    //     },
    //     {
    //         label: "Club Avenue",
    //         backgroundColor: [
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(93, 156, 236, 0.93)'
    //         ],
    //         hoverBackgroundColor: [
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)',
    //             'rgba(255, 83, 112, 0.93)'
    //         ],
    //         data: [2, 4, 7, 3, 2, 9, 2,5,3,1,8,2],
    //     },
    //     {
    //         label: "Community Avenue",
    //         backgroundColor: [
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)'
    //         ],
    //         hoverBackgroundColor: [
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.93)',
    //             'rgba(255, 182, 77, 0.82)'
    //         ],
    //         data: [2, 4, 7, 3, 2, 9, 2,5,3,1,8,2],
    //     }]
    // };


    //     var bar = document.getElementById("barChart").getContext('2d');
    //     var myBarChart = new Chart(bar, {
    //         type: 'bar',
    //         data: data1,
    //         options: {
    //             barValueSpacing: 4
    //         }
    //     });
    });
</script>
@endsection