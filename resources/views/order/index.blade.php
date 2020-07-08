@extends('layouts.default')

@section('title', 'Managed Tables - Buttons')

@push('css')
	<link href="/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
	<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" />
	<link href="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />

@endpush

@section('content')
	{{ Breadcrumbs::render('order.all') }}

	<h1 class="page-header">@lang('order.page_list')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
                    <ul id="order_tab" class="nav nav-tabs nav-tabs-panel panel-title">
                        <li class="nav-item">
                            <a href="#" data-toggle="tab" id="tab_order" class="nav-link active">
                                <span>@lang('order.tab_name_order')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" data-toggle="tab" id="tab_request" class="nav-link">
                                <span>@lang('order.tab_name_request')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" data-toggle="tab" id="tab_archive" class="nav-link">
                                <span>@lang('order.tab_name_archive')</span>
                            </a>
                        </li>
                    </ul>
					{{--<h4 class="panel-title">@lang('order.all_tab_name')</h4>--}}
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row m-b-15">
                        <div class="col-lg-1">
                            <a href="{{route('orders.create')}}" class="btn btn-sm btn-green m-t-5 m-b-5 btn-block" title="@lang('order.btn_add_order')"><i class="fas fa-plus-circle"></i></a>
                        </div>
							<div class="col-lg-3">
								<select class="form-control selectpicker" id="status" data-size="10" data-live-search="true" data-style="btn-white">
									<option value="" selected>@lang('order.select_status')</option>
									@foreach($statuses as $status)
										<option value="{{$status->id}}" class="order-status order-status-tab-{{(($status->id<=5)?'order order-status-tab_active':(($status->id<=7)?'archive':'request'))}}">{{$status->name}}</option>
									@endforeach
								</select>
							</div>
						<div class="col-lg-6 offset-lg-2">
							<form action="{{ route('orders.act_pdf') }}" enctype="multipart/form-data" method="get">
								@csrf
								<div class="row">
									<div class="col-lg-9">
										<div class="row row-space-10">
											<div class="col-xs-6 mb-2 mb-sm-0">
												<input type="text" name="act_date_from" class="form-control" id="datetimepicker3" placeholder="@lang('order.act_date_from')" required>
											</div>
											<div class="col-xs-6">
												<input type="text" name="act_date_to" class="form-control" id="datetimepicker4" placeholder="@lang('order.act_date_to')" required>
											</div>
										</div>
									</div>
									<div class="col-lg-3">
										<button type="submit" class="btn btn-sm btn-primary m-b-5 btn-block">@lang('order.btn_act_pdf')</button>
									</div>
								</div>
							</form>
						</div>
					</div>
                    <div class="table-scroll-container">
					<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle">
						<thead>
							<tr>
								<th class="text-nowrap text-center">@lang('order.table_header_number')</th>
								<th class="text-nowrap text-center">@lang('order.table_header_number')</th>
								<th class="text-nowrap">@lang('order.table_header_date')</th>
								<th class="text-nowrap">@lang('order.table_header_status')</th>
								<th class="text-nowrap">@lang('order.table_header_status_payment')</th>
								<th class="text-nowrap">@lang('order.table_header_total')</th>
								<th class="text-nowrap">@lang('order.table_header_customer')</th>
								<th class="text-nowrap">@lang('order.table_header_user')</th>
								<th width="100"></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
                    </div>
				</div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
		</div>
		<!-- end col-10 -->
	</div>
	<!-- end row -->


@endsection

@push('scripts')
	<script src="/assets/plugins/moment/moment.js"></script>

	<script src="/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="/assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons/js/buttons.flash.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons/js/buttons.html5.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons/js/buttons.print.min.js"></script>
	<script src="/assets/plugins/pdfmake/build/pdfmake.min.js"></script>
	<script src="/assets/plugins/pdfmake/build/vfs_fonts.js"></script>
	<script src="/assets/plugins/jszip/dist/jszip.min.js"></script>
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
	<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script src="/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
	<script src="/assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
	<script src="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>



	{{--<script src="/assets/js/demo/table-manage-buttons.demo.js"></script>--}}

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {
				var ajaxRouteBase = "{!! route('orders.all_ajax') !!}";
				var ajaxRouteTab = "{!! route('orders.all_ajax') !!}?tab=order";

				$('#status').change(function () {
					var ajaxRoute = ajaxRouteTab;
					if($(this).val() !== ''){
						 ajaxRoute += '&status_id='+$(this).val();
                    }

					window.table.ajax.url( ajaxRoute ).load();
				});

				$('#order_tab a').click(function (e) {
                    e.preventDefault();
                    var tab = this.id.replace('tab_','');

					ajaxRouteTab = ajaxRouteBase+"?tab="+tab;

                    $('#status option').removeClass('order-status-tab_active');
                    $('#status option.order-status-tab-'+tab).addClass('order-status-tab_active');
                    $('#status option:first-child').prop('selected', 'selected');
                    $('#status').change();
					$('#status').selectpicker('refresh');
				})

				window.table = $('#data-table-buttons').DataTable( {
					"language": {
						"url": "@lang('table.localization_link')",
					},
					"pageLength": 25,
					"autoWidth": true,
					"processing": true,
					"serverSide": true,
					"ajax": ajaxRouteTab,
					"order": [[ 0, "desc" ]],
					//"ordering": false,
					//"searching": true,
					"columns": [
						{
							data: 'id',
							"visible": false,
						},
						{
							className: 'text-center',
							data: 'number_html',
							"orderable":      false,
						},
						{
							data: 'date_html',
							"orderable":      false,
						},
						{
							data: 'status_html',
							"orderable":      false,
						},
						{
							data: 'payment_html',
							"visible": false,
						},
						{
							data: 'total_html',
							"orderable":      false,
						},
						{
							data: 'sender',
							"orderable":      false,
						},
						{
							data: 'customer',
							"orderable":      false,
						},
						{
							data: 'actions',
							"orderable":      false,
						},
					],
				} );

				$('#datetimepicker3').datetimepicker({
					format: 'DD.MM.YYYY'
				});
				$('#datetimepicker4').datetimepicker({
					format: 'DD.MM.YYYY'
				});
				$("#datetimepicker3").on("dp.change", function (e) {
					$('#datetimepicker4').data("DateTimePicker").minDate(e.date);
				});
				$("#datetimepicker4").on("dp.change", function (e) {
					$('#datetimepicker3').data("DateTimePicker").maxDate(e.date);
				});
			});
		})(jQuery);
	</script>
@endpush
