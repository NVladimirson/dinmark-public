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
	{{ Breadcrumbs::render('balance') }}

	<h1 class="page-header">@lang('finance.page_balance')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('finance.balance_tab_name')</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
                    <div class="row m-b-10">
                        <div class="col-md-5">
                            <input type="text" name="act_date_from" class="form-control" id="datetimepicker5" placeholder="@lang('order.act_date_from')" required>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="act_date_to" class="form-control" id="datetimepicker6" placeholder="@lang('order.act_date_to')" required>
                        </div>
                        <div class="col-md-2">
                            <form action="{{ route('orders.act_pdf') }}" enctype="multipart/form-data" method="get">
                                @csrf
                                <input type="hidden" name="date_from" class="form-control" id="datetimepicker3" placeholder="@lang('order.act_date_from')" required>
                                <input type="hidden" name="date_to" class="form-control" id="datetimepicker4" placeholder="@lang('order.act_date_to')" required>

                                <button type="submit" class="btn btn-sm btn-primary m-b-5 btn-block" title="@lang('order.btn_act_pdf')"><i class="fas fa-columns"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="table-scroll-container">
                        <table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle">
                            <thead>
                            <tr>
                                <th class="text-nowrap">@lang('finance.payment_table_header_date')</th>
                                <th class="text-nowrap">@lang('finance.balance_table_header_document')</th>
                                <th class="text-nowrap text-right">@lang('finance.balance_table_header_debit')</th>
                                <th class="text-nowrap text-right">@lang('finance.balance_table_header_credit')</th>
                                <th class="text-nowrap text-center">@lang('finance.balance_table_header_currency')</th>
                                <th style="min-width: 30px; width: 30px"></th>
                            </tr>
                            <tr>
                                <th colspan="2">
                                    @lang('finance.saldo_start')
                                </th>
                                <th class="text-nowrap text-right" colspan="2" id="saldo_start">
                                    0
                                </th>
                                <td class="text-nowrap text-center">
                                    &nbsp;UAH
                                </td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>

                            <tr>
                                <th colspan="2">
                                    @lang('finance.total_debit_credit')
                                </th>
                                <th class="text-nowrap text-right" id="total_debit">
                                    0
                                </th>
                                <th class="text-nowrap text-right" id="total_credit">
                                    0
                                </th>
                                <td class="text-nowrap text-center">
                                    &nbsp;UAH
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th colspan="2">
                                    @lang('finance.saldo_end')
                                </th>
                                <th class="text-nowrap text-right" colspan="2" id="saldo_end">
                                    0
                                </th>
                                <td class="text-nowrap text-center">
                                    &nbsp;UAH
                                </td>
                                <td></td>
                            </tr>
                            </tfoot>
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

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {

				var ajaxRouteBase = "{!! route('balance.ajax') !!}";
				var tableDataRoute = "{!! route('balance.ajax_total') !!}";
				var date = '';

				function updateTableData(){
					var route = tableDataRoute + '?filter=1' + date;

					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					$.ajax({
						method: "GET",
						url: route,
						success: function (resp) {
							if (resp.status == "success") {
								Object.entries(resp).forEach(function (el) {
									if(el[0] != "status"){
										$("#"+el[0]).text(el[1]);
									}
								});

							}
						},
						error: function (xhr, str) {
							console.log(xhr);
						}
					});
				}

				window.table = $('#data-table-buttons').DataTable( {
					"language": {
						"url": "@lang('table.localization_link')",
					},
					"pageLength": 25,
					"autoWidth": false,
					"processing": true,
					"serverSide": true,
					"ajax": ajaxRouteBase,
					"order": [[ 0, "desc" ]],
					"ordering": false,
					"searching": false,
					"columns": [
						{
							data: 'date_html',
						},
						{
							data: 'document_html',
						},
						{
							className: 'text-right',
							data: 'debet_html',
						},
						{
							className: 'text-right',
							data: 'credit_html',
						},
						{
							className: 'text-center',
							data: 'currency_html',
						},
						{
							data: 'action_buttons',
						},
					],
				} );
				updateTableData();

				function updateAjax(){
					var ajaxRoute = ajaxRouteBase + '?filter=1' + date;
					window.table.ajax.url( ajaxRoute ).load();
					updateTableData();
				}

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
				$('#datetimepicker5').datetimepicker({
					format: 'DD.MM.YYYY'
				});
				$('#datetimepicker6').datetimepicker({
					format: 'DD.MM.YYYY'
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
