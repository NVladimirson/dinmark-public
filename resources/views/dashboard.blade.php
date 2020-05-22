@extends('layouts.default')

@section('title', 'Dashboard V1')

@push('css')
	<link href="/assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
@endpush

@section('content')
	{{ Breadcrumbs::render('home') }}
	<!-- begin page-header -->
	<h1 class="page-header">@lang('dashboard.page_name')</h1>
	<!-- end page-header -->

	<div class="row">

		<div class="col-xl-3 col-md-6">
			<div class="widget widget-stats bg-blue">
				<div class="stats-icon"><i class="fas fa-shopping-cart"></i></div>
				<div class="stats-info">
					<h4>@lang('dashboard.order_count')</h4>
					<p>{{$order_counts}}</p>
				</div>
				<div class="stats-link">
					<a href="{{route('orders')}}">@lang('global.detail') <i class="fa fa-arrow-alt-circle-right"></i></a>
				</div>
			</div>
		</div>


		<div class="col-xl-3 col-md-6">
			<div class="widget widget-stats bg-info">
				<div class="stats-icon"><i class="fas fa-percent"></i></div>
				<div class="stats-info">
					<h4>@lang('dashboard.success_procent')</h4>
					<p>{{number_format($success_procent,2,'.','')}}%</p>
				</div>
				<div class="stats-link">
					<a href="{{route('orders')}}">@lang('global.detail') <i class="fa fa-arrow-alt-circle-right"></i></a>
				</div>
			</div>
		</div>


		<div class="col-xl-3 col-md-6">
			<div class="widget widget-stats bg-orange">
				<div class="stats-icon"><i class="fas fa-hryvnia"></i></div>
				<div class="stats-info">
					<h4>@lang('dashboard.success_total')</h4>
					<p>{{$success_total}} @lang('global.grn')</p>
				</div>
				<div class="stats-link">
					<a href="{{route('orders')}}">@lang('global.detail') <i class="fa fa-arrow-alt-circle-right"></i></a>
				</div>
			</div>
		</div>


		<div class="col-xl-3 col-md-6">
			<div class="widget widget-stats bg-red">
				<div class="stats-icon"><i class="fas fa-weight-hanging"></i></div>
				<div class="stats-info">
					<h4>@lang('dashboard.success_weight')</h4>
					<p>{{$success_weight}} @lang('global.kg')</p>
				</div>
				<div class="stats-link">
					<a href="{{route('orders')}}">@lang('global.detail') <i class="fa fa-arrow-alt-circle-right"></i></a>
				</div>
			</div>
		</div>

	</div>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-8 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary" data-sortable-id="index-1">
				<div class="panel-heading">
					<h4 class="panel-title">@lang('dashboard.chart_price_order')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<div class="panel-body pr-1">
					<div id="order-sum-weight-chart" class="height-sm"></div>
				</div>
			</div>

			<div class="panel panel-primary" data-sortable-id="index-2">
				<div class="panel-heading">
					<h4 class="panel-title">@lang('dashboard.chart_status_order')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<div class="panel-body pr-1">
					<div id="order-status-chart" class="height-sm"></div>
				</div>
			</div>
			<!-- end panel -->
		</div>
	</div>
	<!-- end row -->
@endsection

@push('scripts')
	<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="/assets/plugins/flot/jquery.flot.js"></script>
	<script src="/assets/plugins/flot/jquery.flot.time.js"></script>
	<script src="/assets/plugins/flot/jquery.flot.resize.js"></script>
	<script src="/assets/plugins/flot/jquery.flot.pie.js"></script>
	<script src="/assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
	<script src="/assets/plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
	<script src="/assets/plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
	<script src="/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>

	<script>
		var ordersData = {!! $orders->toJson() !!};
		var handlePriceChart = function () {
			"use strict";
			function showTooltip(x, y, contents) {
				$('<div id="tooltip" class="flot-tooltip">' + contents + '</div>').css( {
					top: y - 45,
					left: x - 55
				}).appendTo("body").fadeIn(200);
			}
			if ($('#order-sum-weight-chart').length !== 0) {

				var data1 = [
					@php
						$i = 1;
					@endphp
					@foreach($orders as $order)
						@php
							$sum = 0;
							foreach ($order as $item){
								$sum += $item->total;
							}

						@endphp
					[{{$i++}}, {{$sum}}],
					@endforeach
				];
				var data2 = [
						@php
							$i = 1;
						@endphp
						@foreach($orders as $order)
						@php
							$weight = 0;
							foreach ($order as $item){
								foreach ($item->products as $product){
									$weight += ($product->product->weight/100) * $product->quantity ;
								}
							}

						@endphp
					[{{$i++}}, {{$weight}}],
						@endforeach
				];
				var xLabel = [
						@php
							$i = 1;
						@endphp
						@foreach($orders as $month =>$order)

						[{{$i++}}, '{{$month}}'],
						@endforeach
				];
				$.plot($("#order-sum-weight-chart"), [{
					data: data1,
					label: "@lang('dashboard.data_price')",
					color: COLOR_BLUE,
					lines: { show: true, fill:false, lineWidth: 2 },
					points: { show: true, radius: 3, fillColor: COLOR_WHITE },
					shadowSize: 0
				}, {
					data: data2,
					label: "@lang('dashboard.data_weight')",
					color: COLOR_GREEN,
					lines: { show: true, fill:false, lineWidth: 2 },
					points: { show: true, radius: 3, fillColor: COLOR_WHITE },
					shadowSize: 0
				}], {
					xaxis: {  ticks:xLabel, tickDecimals: 0, tickColor: COLOR_DARK_TRANSPARENT_2 },
					yaxis: {  /*ticks: 10,*/ tickColor: COLOR_DARK_TRANSPARENT_2, min: 0/*, max: 20000000*/ },
					grid: {
						hoverable: true,
						clickable: true,
						tickColor: COLOR_DARK_TRANSPARENT_2,
						borderWidth: 1,
						backgroundColor: 'transparent',
						borderColor: COLOR_DARK_TRANSPARENT_2
					},
					legend: {
						labelBoxBorderColor: COLOR_DARK_TRANSPARENT_2,
						margin: 10,
						noColumns: 1,
						show: true
					}
				});
				var previousPoint = null;
				$("#order-sum-weight-chart").bind("plothover", function (event, pos, item) {
					$("#x").text(pos.x.toFixed(2));
					$("#y").text(pos.y.toFixed(2));
					if (item) {
						if (previousPoint !== item.dataIndex) {
							previousPoint = item.dataIndex;
							$("#tooltip").remove();
							var y = item.datapoint[1].toFixed(2);

							var content = item.series.label + " " + y;
							showTooltip(item.pageX, item.pageY, content);
						}
					} else {
						$("#tooltip").remove();
						previousPoint = null;
					}
					event.preventDefault();
				});
			}
		};
		var handleStatusChart = function () {
			"use strict";
			function showTooltipStatus(x, y, contents) {
				$('<div id="tooltip-status" class="flot-tooltip">' + contents + '</div>').css( {
					top: y - 45,
					left: x - 55
				}).appendTo("body").fadeIn(200);
			}
			if ($('#order-status-chart').length !== 0) {

				var data1 = [
					@php
						$i = 1;
					@endphp
					@foreach($orders as $order)

					[{{$i++}}, {{$order->count()}}],
					@endforeach
				];
				var data2 = [
						@php
							$i = 1;
						@endphp
						@foreach($orders as $order)

					[{{$i++}}, {{$order->where('status',2)->count()}}],
						@endforeach
				];
				var data3 = [
						@php
							$i = 1;
						@endphp
						@foreach($orders as $order)

					[{{$i++}}, {{$order->where('status',7)->count()}}],
						@endforeach
				];
				var xLabel = [
						@php
							$i = 1;
						@endphp
						@foreach($orders as $month =>$order)

						[{{$i++}}, '{{$month}}'],
						@endforeach
				];
				$.plot($("#order-status-chart"), [{
					data: data1,
					label: "@lang('dashboard.data_status_oll')",
					color: COLOR_BLUE_DARKER,
					lines: { show: true, fill:false, lineWidth: 2 },
					points: { show: true, radius: 3, fillColor: COLOR_WHITE },
					shadowSize: 0
				},  {
					data: data2,
					label: "@lang('dashboard.data_status_success')",
					color: COLOR_YELLOW_DARKER,
					lines: { show: true, fill:false, lineWidth: 2 },
					points: { show: true, radius: 3, fillColor: COLOR_WHITE },
					shadowSize: 0
				}, {
					data: data3,
					label: "@lang('dashboard.data_status_complete')",
					color: COLOR_GREEN_DARKER,
					lines: { show: true, fill:false, lineWidth: 2 },
					points: { show: true, radius: 3, fillColor: COLOR_WHITE },
					shadowSize: 0
				}], {
					xaxis: {  ticks:xLabel, tickDecimals: 0, tickColor: COLOR_DARK_TRANSPARENT_2 },
					yaxis: {  /*ticks: 10,*/ tickColor: COLOR_DARK_TRANSPARENT_2, min: 0/*, max: 20000000*/ },
					grid: {
						hoverable: true,
						clickable: true,
						tickColor: COLOR_DARK_TRANSPARENT_2,
						borderWidth: 1,
						backgroundColor: 'transparent',
						borderColor: COLOR_DARK_TRANSPARENT_2
					},
					legend: {
						labelBoxBorderColor: COLOR_DARK_TRANSPARENT_2,
						margin: 10,
						noColumns: 1,
						show: true
					}
				});
				var previousPoint = null;
				$("#order-status-chart").bind("plothover", function (event, pos, item) {
					$("#x").text(pos.x.toFixed(2));
					$("#y").text(pos.y.toFixed(2));
					if (item) {
						if (previousPoint !== item.dataIndex) {
							previousPoint = item.dataIndex;
							$("#tooltip-status").remove();
							var y = item.datapoint[1].toFixed(2);

							var content = item.series.label + " " + y;
							showTooltipStatus(item.pageX, item.pageY, content);
						}
					} else {
						$("#tooltip-status").remove();
						previousPoint = null;
					}
					event.preventDefault();
				});
			}
		};



		var Dashboard = function () {
			"use strict";
			return {
				//main function
				init: function () {
					handlePriceChart();
					handleStatusChart();
				}
			};
		}();

		$(document).ready(function() {
			Dashboard.init();
		});
	</script>
@endpush
