@extends('layouts.app')

@push('page-css')
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/material-vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/weather-icons/climacons.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/meteocons/style.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/charts/morris.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/charts/chartist.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/charts/chartist-plugin-tooltip.css')}}">
    <!-- END: Vendor CSS-->
	<link rel="stylesheet" href="{{asset('app-assets/css/pages/dashboard-ecommerce.min.css')}}">
@endpush

@section('content')
<div class="row">
	<div class="col-xl-3 col-lg-6 col-12">
		<div class="card pull-up">
			<div class="card-content">
				<div class="card-body">
					<div class="media d-flex">
						<div class="media-body text-left">
							<h3 class="success">{{$totalUsers}}</h3>
							<h6>Total Customers</h6>
						</div>
						<div>
							<i class="icon-users success font-large-2 float-right"></i>
						</div>
					</div>
					<div class="progress progress-sm mt-1 mb-0 box-shadow-2">
						<div class="progress-bar bg-gradient-x-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-lg-6 col-12">
		<div class="card pull-up">
			<div class="card-content">
				<div class="card-body">
					<div class="media d-flex">
						<div class="media-body text-left">
							<h3 class="info">{{$completedOrders}}</h3>
							<h6>Completed Orders</h6>
						</div>
						<div>
							<i class="icon-check info font-large-2 float-right"></i>
						</div>
					</div>
					<div class="progress progress-sm mt-1 mb-0 box-shadow-2">
						<div class="progress-bar bg-gradient-x-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-lg-6 col-12">
		<div class="card pull-up">
			<div class="card-content">
				<div class="card-body">
					<div class="media d-flex">
						<div class="media-body text-left">
							<h3 class="warning">{{$activeOrders}}</h3>
							<h6>Active Orders</h6>
						</div>
						<div>
							<i class="icon-basket warning font-large-2 float-right"></i>
						</div>
					</div>
					<div class="progress progress-sm mt-1 mb-0 box-shadow-2">
						<div class="progress-bar bg-gradient-x-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-lg-6 col-12">
		<div class="card pull-up">
			<div class="card-content">
				<div class="card-body">
					<div class="media d-flex">
						<div class="media-body text-left">
							<h3 class="danger">{{$retentionCount}}</h3>
							<h6>Order Retention</h6>
						</div>
						<div>
							<i class="icon-link danger font-large-2 float-right"></i>
						</div>
					</div>
					<div class="progress progress-sm mt-1 mb-0 box-shadow-2">
						<div class="progress-bar bg-gradient-x-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Pie Chart -->
<div class="row match-height">
	<div class="col-md-12 col-sm-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Statistics Overview</h4>
				<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
				<div class="heading-elements">
					<ul class="list-inline mb-0">
						<li><a data-action="collapse"><i class="ft-minus"></i></a></li>
						<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
						<li><a data-action="expand"><i class="ft-maximize"></i></a></li>
						<li><a data-action="close"><i class="ft-x"></i></a></li>
					</ul>
				</div>
			</div>
			<div class="card-content collapse show">
				<div class="card-body" style="height: 400px;">
					{!! $barChart->render() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('page-js')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset('app-assets/vendors/js/charts/chartist.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/charts/chartist-plugin-tooltip.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/charts/raphael-min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/charts/morris.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/timeline/horizontal-timeline.js')}}"></script>
    <!-- END: Page Vendor JS-->
	<script src="{{asset('app-assets/js/scripts/pages/dashboard-ecommerce.min.js')}}"></script>
	<script src="{{asset('app-assets/vendors/js/charts/chart.min.js')}}"></script>
@endpush
