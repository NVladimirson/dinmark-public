@extends('layouts.default')

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
	{{ Breadcrumbs::render('payments') }}

	<h1 class="page-header">@lang('finance.page_payment')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('finance.payment_tab_name')</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
                    <div class="table-scroll-container">
                        <table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle">
                            <thead>
                            <tr>
                                <th class="text-nowrap" width="25%">
                                    <div class="row row-space-10">
                                        <div class="col-xs-12 mb-2 m-b-5">
                                            <input type="text" class="form-control" id="datetimepicker5" placeholder="@lang('finance.payment_table_header_date')" required>
                                        </div>
                                        <div class="col-xs-12" >
                                            <input type="text" class="form-control" id="datetimepicker6" placeholder="@lang('order.act_date_to')" required style="display: none">
                                        </div>
                                    </div></th>
                                <th class="text-nowrap" width="25%">@lang('finance.payment_table_header_number')</th>
                                <th class="text-nowrap" width="25%">@lang('finance.payment_table_header_order')</th>
                                <th class="text-nowrap text-center" width="25%">@lang('finance.payment_table_header_sum')</th>
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
		<script src="/assets/plugins/moment/locale/ru.js"></script>
		<script src="/assets/plugins/moment/locale/uk.js"></script>

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

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {

				var ajaxRouteBase = "{!! route('payments.ajax') !!}";
				var date = '';

				window.table = $('#data-table-buttons').DataTable( {
					"language": {
						"url": "@lang('table.localization_link')",
					},
					"pageLength": 25,
					"autoWidth": true,
					"processing": true,
					"serverSide": true,
					"ajax": ajaxRouteBase,
					"order": [[ 0, "desc" ]],
					"ordering": false,
					"searching": false,
					dom: 'lBfrtip',
					buttons: [
						{
							text: "<i class='fas fa-times'></i> @lang('global.btn_clear_filter')",
							className:'databtn btn btn-danger',
							attr: {
								id: 'clear_filter',
								style: 'display: none'
							},
							action: function ( e, dt, node, config ) {
								clearFilter();
							}
						}
					],
					"columns": [
						{
							data: 'date_html',
						},
						{
							data: 'public_number',
						},
						{
							data: 'order_html',
						},
						{
							className: 'text-center',
							data: 'sum_html',
						},
					],
				} );

				function updateAjax(){
					var ajaxRoute = ajaxRouteBase + '?filter=1' + date;
					if(date == ""){
						$('#clear_filter').hide();
					}else{
						$('#clear_filter').show();
					}
					window.table.ajax.url( ajaxRoute ).load();
				}
				function clearFilter(){
					$("#datetimepicker5").val('');
					$("#datetimepicker6").val('');
					$('#datetimepicker6').hide();
					changeDate();
				}
				$('#datetimepicker3').datetimepicker({
					format: 'DD.MM.YYYY',
					locale: '{{$locale}}'
				});
				$('#datetimepicker4').datetimepicker({
					format: 'DD.MM.YYYY',
					locale: '{{$locale}}'
				});
				$("#datetimepicker3").on("dp.change", function (e) {
					$('#datetimepicker4').data("DateTimePicker").minDate(e.date);
				});
				$("#datetimepicker4").on("dp.change", function (e) {
					$('#datetimepicker3').data("DateTimePicker").maxDate(e.date);
				});
				$('#datetimepicker5').datetimepicker({
					format: 'DD.MM.YYYY',
					locale: '{{$locale}}'
				});
				$('#datetimepicker6').datetimepicker({
					format: 'DD.MM.YYYY',
					locale: '{{$locale}}'
				});

				function changeDate(){
					if($("#datetimepicker5").val() !== ''){
						date = '&date_from='+$("#datetimepicker5").data("DateTimePicker").date()/1000;
					}else{
						date = '';
					}
					$('#datetimepicker3').val($("#datetimepicker5").val());
					if($("#datetimepicker6").val() !== ''){
						date += '&date_to='+$("#datetimepicker6").data("DateTimePicker").date()/1000;
					}else {
						date += '';
					}
					$('#datetimepicker4').val($("#datetimepicker6").val());
					updateAjax();
				}

				$("#datetimepicker5").on("dp.change", function (e) {
					$('#datetimepicker6').show();
					$('#datetimepicker6').data("DateTimePicker").minDate(e.date);
					changeDate();

				});
				$("#datetimepicker6").on("dp.change", function (e) {
					$('#datetimepicker5').data("DateTimePicker").maxDate(e.date);
					changeDate();
				});
			});
		})(jQuery);
	</script>
@endpush
