@extends('layouts.default')

@section('title', 'Dashboard V1')

@push('css')
	<link href="/assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
	<style>
		.nav-link {
			height: 55px;
		}
	</style>
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
		<div class="col-lg-6">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="panel-title">@lang('dashboard.chart_price_order')</h4>
				</div>
				<div class="panel-body pr-1">
					<div id="order-sum-chart" class="height-sm"></div>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="panel-title">@lang('dashboard.chart_weight_order')</h4>
				</div>
				<div class="panel-body pr-1">
					<div id="order-weight-chart" class="height-sm"></div>
				</div>
			</div>
		</div>
		<div class="col-xl-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="panel-title">@lang('dashboard.chart_status_order')</h4>
				</div>
				<div class="panel-body pr-1">
					<div id="order-status-chart" class="height-sm"></div>
				</div>
			</div>
			<!-- end panel -->
		</div>
        <div class="col-lg-6">
            <ul class="nav nav-tabs nav-tabs-inverse nav-tabs-primary nav-justified nav-justified-mobile">
                <li class="nav-item">
                    <a href="#latest-actions" data-toggle="tab"  class="nav-link active"><i class="fas fa-user-clock fa-lg m-r-5"></i> <span class="d-none d-md-inline">@lang('dashboard.tab_name_last_actions')</span></a></li>
                <li class="nav-item">
									<a href="#last_orders" data-toggle="tab"  class="nav-link"><i class="fas fa-shopping-cart fa-lg m-r-5"></i> <span class="d-none d-md-inline">@lang('dashboard.tab_name_last_orders')</span></a></li>
                <li class="nav-item">
									<a href="#last_messages" data-toggle="tab"  class="nav-link"><i class="fa fa-envelope fa-lg m-r-5"></i> <span class="d-none d-md-inline">@lang('dashboard.tab_name_last_messages')</span></a></li>
            </ul>
            <div class="tab-content" >
                <div class="tab-pane fade active show" id="latest-actions">
                    <table class="table table-striped table-bordered table-td-valign-middle m-b-15">
                        <tbody>
                            <tr>
                                <th class="text-nowrap">@lang('dashboard.tab_last_enter')</th>
                                <td>{{\Carbon\Carbon::createFromTimestamp(auth()->user()->last_login)->format('d.m.Y h:i')}}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">@lang('dashboard.tab_last_order')</th>
                                @if($last_orders->count() > 0)
                                    <td>{{\Carbon\Carbon::parse($last_orders->first()->date_add)->format('d.m.Y h:i')}}</td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                            <tr>
                                <th class="text-nowrap">@lang('dashboard.tab_last_payment')</th>
                                @if($last_payment)
                                    <td>{{\Carbon\Carbon::parse($last_payment->date_add)->format('d.m.Y h:i')}}</td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="last_orders">
                    <table class="table table-striped table-bordered table-td-valign-middle m-b-15">
                        <thead>
                            <th class="text-nowrap text-center">@lang('order.table_header_number')</th>
                            <th class="text-nowrap">@lang('order.table_header_date')</th>
                            <th class="text-nowrap">@lang('order.table_header_status')</th>
                            <th class="text-nowrap">@lang('order.table_header_status_payment')</th>
                            <th class="text-nowrap text-center">@lang('order.table_header_total')</th>
                        </thead>
                        <tbody>
                        @foreach($last_orders as $order)
                        <tr>
                            <td class="text-nowrap text-center"><a href="{{route('orders.show',[$order->id])}}">{{($order->public_number)?($order->id.' / '.$order->public_number):($order->id.' / -')}}</a></td>
                            <td class="text-nowrap">{{\Carbon\Carbon::parse($order->date_add)->format('d.m.Y h:i')}}</td>
                            <td class="text-nowrap">{{$order->getStatus->name}}</td>
                            @php
                                $status = '';
                                    if($order->payments->count() > 0){
                                        if($order->payments->sum('payed') < $order->total){
                                            $status = trans('order.payment_status_partial');
                                        }else{
                                            $status = trans('order.payment_status_success');
                                        }
                                    }else{
                                        $status = trans('order.payment_status_none');
                                    }
                            @endphp
                            <td class="text-nowrap">{{$status}}</td>
                            <td class="text-nowrap text-center">{{number_format($order->total,2,'.',' ')}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="last_messages">
                    <ul class="media-list media-list-with-divider">
                        @foreach($last_messages as $message)
                        <li class="media media-sm">
                            <a href="javascript:;" class="pull-left">
                                @if($message->user->photo)
                                    <!-- <img src="{{env('DINMARK_URL')}}images/profile/{{$message->user->photo}}"class="media-object rounded-corner" alt="{{$message->user->name}}" /> -->
																		<img src="https://dinmark.com.ua/images/profile/{{$message->user->photo}}"class="media-object rounded-corner" alt="{{$message->user->name}}" />
                                @else
                                    <!-- <img src="{{env('DINMARK_URL')}}images/empty-avatar.png" class="media-object rounded-corner" alt="{{$message->user->name}}" /> -->
																		<img src="https://dinmark.com.ua/images/empty-avatar.png" class="media-object rounded-corner" alt="{{$message->user->name}}" />
                                @endif
                            </a>
                            <div class="media-body">
                                <a href="javascript:;" class="text-inverse"><h5 class="media-heading">{{$message->user->name}}</h5></a>
                                <p class="m-b-5">
                                    {{$message->text}}
                                </p>
                                <span class="text-muted f-s-11 f-w-600">{{\Carbon\Carbon::parse($message->created_at)->format('d.m.Y h:i')}}</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <ul class="nav nav-tabs nav-tabs-inverse nav-tabs-primary nav-justified nav-justified-mobile">
                <li class="nav-item">
                    <a href="#top_price_products" data-toggle="tab"  class="nav-link active"><i class="fa fa-shopping-bag fa-lg m-r-5"></i> <span class="d-none d-md-inline">@lang('dashboard.tab_name_top_price_products')</span></a></li>
                <li class="nav-item"><a href="#top_popular_products" data-toggle="tab"  class="nav-link"><i class="fa fa-shopping-bag fa-lg m-r-5"></i> <span class="d-none d-md-inline">@lang('dashboard.tab_name_top_popular_products')</span></a></li>
                <li class="nav-item"><a href="#last_news" data-toggle="tab"  class="nav-link"><i class="far fa-newspaper fa-lg m-r-5"></i> <span class="d-none d-md-inline">@lang('dashboard.tab_name_last_news')</span></a></li>
            </ul>
            <div class="tab-content" >
                <div class="tab-pane fade active show" id="top_price_products">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="hidden-sm text-center">@lang('product.table_header_name')</th>
                            <th></th>
                            <th>@lang('product.table_header_article')</th>
                            <th>@lang('product.table_header_top_price')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($topOrderProducts as $product)
                        <tr>
                            <td class="hidden-sm text-center">
                                <a href="javascript:;">
                                    <img src="{{$product['image']}}" alt="{{$product['name']}}" width="32px">
                                </a>
                            </td>
                            <td class="text-nowrap">
                                <h6><a href="{{route('products.show',[$product['id']])}}" class="text-inverse">{{$product['name']}}</a></h6>
                            </td>
                            <td class="text-nowrap"><a href="javascript:;" class="text-inverse">{{$product['article']}}</a></td>
                            <td class="text-blue f-w-600" style="white-space: nowrap;">{{$product['price']}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="top_popular_products">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="hidden-sm text-center">@lang('product.table_header_name')</th>
                            <th></th>
                            <th>@lang('product.table_header_article')</th>
                            <th>@lang('product.table_header_times_in_orders')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($mostPopularOrderProducts as $product)
                            <tr>
                                <td class="hidden-sm text-center">
                                    <a href="javascript:;">
                                        <img src="{{$product['image']}}" alt="{{$product['name']}}" width="32px">
                                    </a>
                                </td>
                                <td class="text-nowrap">
                                    <h6><a href="{{route('products.show',[$product['id']])}}" class="text-inverse">{{$product['name']}}</a></h6>
                                </td>
                                <td class="text-nowrap"><a href="javascript:;" class="text-inverse">{{$product['article']}}</a></td>
                                <td class="text-blue f-w-600" style="white-space: nowrap;">{{$product['product_count']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="last_news">
                    <ul class="media-list media-list-with-divider">
                        @foreach($newsData as $news)
                        <li class="media media-sm">
                            <a href="{{route('news.show',[$news['id']])}}" class="pull-left">
                                <img src="{{$news['image']}}"class="media-object" alt="{{$news['name']}}" />
                            </a>
                            <div class="media-body">
                                <a href="{{route('news.show',[$news['id']])}}" class="text-inverse"><h5 class="media-heading">{{$news['name']}}</h5></a>
                                <p class="m-b-5">
                                    {!! mb_strimwidth($news['text'],0,100,'...') !!}
                                </p>
                                <span class="text-muted f-s-11 f-w-600">{{$news['date']}}</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
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
	<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('2806f28559ed06d3275e', {
      cluster: 'eu'
    });

    var channel = pusher.subscribe('newmessage-chanel');
    channel.bind('newmessage-event', function(data) {
			console.log(data);
			let current = '{{$current_user_id}}';
			let sendto = data.data.sendto;
			if(current == sendto){
				let bell = $('#notificationbell');
				bell.toggleClass('fa-bell fa-exclamation');
				$.gritter.add({
						title: '@lang("notification.new_message")',
				});
			}
    });
  </script>
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
			if ($('#order-sum-chart').length !== 0) {

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
				var xLabel = [
						@php
							$i = 1;
						@endphp
						@foreach($orders as $month =>$order)

						[{{$i++}}, '{{$month}}'],
						@endforeach
				];
				$.plot($("#order-sum-chart"), [{
					data: data1,
					label: "@lang('dashboard.data_price')",
					color: COLOR_BLUE,
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
				$("#order-sum-chart").bind("plothover", function (event, pos, item) {
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
		var handleWeightChart = function () {
			"use strict";
			function showTooltipWeight(x, y, contents) {
				$('<div id="tooltip" class="flot-tooltip">' + contents + '</div>').css( {
					top: y - 45,
					left: x - 55
				}).appendTo("body").fadeIn(200);
			}
			if ($('#order-weight-chart').length !== 0) {

				var data2 = [
						@php
							$i = 1;
						@endphp
						@foreach($orders as $order)
						@php
							$weight = 0;
							foreach ($order as $item){
								foreach ($item->products as $product){
                                    if($product->product){
                                        $weight += ($product->product->weight/100) * $product->quantity ;
                                    }
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
				$.plot($("#order-weight-chart"), [ {
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
				$("#order-weight-chart").bind("plothover", function (event, pos, item) {
					$("#x").text(pos.x.toFixed(2));
					$("#y").text(pos.y.toFixed(2));
					if (item) {
						if (previousPoint !== item.dataIndex) {
							previousPoint = item.dataIndex;
							$("#tooltip").remove();
							var y = item.datapoint[1].toFixed(2);

							var content = item.series.label + " " + y;
							showTooltipWeight(item.pageX, item.pageY, content);
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

					[{{$i++}}, {{$order->where('status','<>',8)->count()}}],
					@endforeach
				];
				var data2 = [
						@php
							$i = 1;
						@endphp
						@foreach($orders as $order)

					[{{$i++}}, {{$order->where('status','<>',8)->where('status','<>',1)->where('status','<>',7)->count()}}],
						@endforeach
				];
				var data3 = [
						@php
							$i = 1;
						@endphp
						@foreach($orders as $order)

					[{{$i++}}, {{$order->where('status',6)->count()}}],
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
					handleWeightChart();
				}
			};
		}();

		$(document).ready(function() {
			Dashboard.init();
		});
	</script>
@endpush
