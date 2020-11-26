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
{{ Breadcrumbs::render('implementations') }}

<h1 class="page-header">@lang('implementation.page_list')</h1>
<!-- begin row -->
<div class="row">
	<!-- begin col-10 -->
	<div class="col-xl-12">
		<!-- begin panel -->
		<div class="panel panel-primary">
			<!-- begin panel-heading -->
			<div class="panel-heading">
				<h4 class="panel-title">@lang('implementation.all_tab_name')</h4>
			</div>
			<!-- end panel-heading -->
			<!-- begin panel-body -->
			<div class="panel-body">
				<div class="table-scroll-container">
					<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle">
						<thead>
							<tr>
								<th class="text-nowrap text-center"></th>
								<th class="text-nowrap text-center"></th>
								<th class="text-nowrap text-center">
									<div class="row row-space-10">
										<div class="col-xs-12 mb-2 m-b-5">
											<input type="text" name="act_date_from" class="form-control" id="datetimepicker5" placeholder="@lang('implementation.table_header_data')" required>
										</div>
										<div class="col-xs-12">
											<input type="text" name="act_date_to" class="form-control" id="datetimepicker6" placeholder="@lang('order.act_date_to')" required style="display: none">
										</div>
									</div>
								</th>
								<th class="text-nowrap text-center">@lang('implementation.table_header_number')</th>
								<th class="text-nowrap">
									<div><select class="form-control selectpicker" id="sender" data-size="10" data-live-search="true" data-style="btn-white">
											<option value="" selected>@lang('implementation.table_header_sender')</option>
											@foreach($senders as $name => $id)
											<option value="{{$id}}">{{$name}}</option>
											@endforeach
										</select></div>
								</th>
								<th class="text-nowrap">
									<div><select class="form-control selectpicker" id="customer" data-size="10" data-live-search="true" data-style="btn-white">
											<option value="" selected>@lang('implementation.table_header_customer')</option>
											@foreach($customers as $name => $id)
											<option value="{{$id}}">{{$name}}</option>
											@endforeach
										</select></div>
								</th>
								<th class="text-nowrap">@lang('implementation.table_header_status')</th>
								<th class="text-nowrap">@lang('implementation.table_header_ttn')</th>
								<th class="text-nowrap">@lang('implementation.table_header_weight')</th>
								<th class="text-nowrap" style="width: 80px; min-width: 80px">@lang('implementation.table_header_total')</th>
								<th class="text-nowrap" style="width: 120px; min-width: 120px"></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<td colspan="11" class="text-nowrap text-left">
								<span class="m-r-15">
									<strong>@lang('implementation.table_footer_pc')</strong> <span id="footer_pc">1</span>
								</span>
								<span class="m-r-15">
									<strong>@lang('implementation.table_footer_total')</strong> <span id="footer_total">1</span> @lang('global.grn')
								</span>
								<span class="m-r-15">
									<strong>@lang('implementation.table_footer_weight')</strong> <span id="footer_weight">1</span> @lang('global.kg')
								</span>
							</td>
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


@include('order.include.explanation')
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
	(function($) {
			"use strict";
			$(document).ready(function() {
					var tableDataRoute = "{!! route('implementations.total_data_ajax') !!}";

					var ajaxRouteBase = "{!! route('implementations.ajax') !!}";
					var sender = '';
					var customer = '';
					var date = '';

					function updateTableData() {
						var route = tableDataRoute + '?x=1' + sender + customer + date;

						$.ajaxSetup({
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							}
						});
						$.ajax({
							method: "GET",
							url: route,
							success: function(resp) {
								if (resp.status == "success") {
									Object.entries(resp).forEach(function(el) {
										if (el[0] != "status") {
											$("#footer_" + el[0]).text(el[1]);
										}
									});

								}
							},
							error: function(xhr, str) {
								console.log(xhr);
							}
						});
					}

					window.table = $('#data-table-buttons').DataTable({
						"language": {
							"url": "@lang('table.localization_link')",
						},
						"pageLength": 25,
						"autoWidth": false,
						"processing": true,
						"serverSide": true,
						"ajax": ajaxRouteBase,
						"order": [
							[0, "desc"]
						],
						"ordering": false,
						"searching": false,
						dom: 'lBfrtip',
						buttons: [{
							text: "<i class='fas fa-times'></i> @lang('global.btn_clear_filter')",
							className: 'databtn btn btn-danger',
							attr: {
								id: 'clear_filter',
								style: 'display: none'
							},
							action: function(e, dt, node, config) {
								clearFilter();
							}
						}],
						"columns": [{
								data: 'id',
								"visible": false,
								"searchable": false
							},
							{
								"className": 'details-control text-center',
								"orderable": false,
								"data": null,
								"defaultContent": '',
								render: function(data, type, row) {
									return '<i class="fa fa-plus" aria-hidden="true"></i>';
								},
							},
							{
								className: 'text-center',
								data: 'date',
							},
							{
								className: 'text-center',
								data: 'public_number',
							},
							{
								data: 'sender',
							},
							{
								data: 'customer',
							},
							{
								data: 'status',
							},
							{
								data: 'ttn_html',
							},
							{
								data: 'weight_html',
							},
							{
								data: 'total',
							},
							{
								data: 'actions_btn',
							},
						],
					});

					updateTableData();

					function format_implementation_products(data) {
						return data.products;
					}

					$('#data-table-buttons tbody').on('click', 'td.details-control', function() {
						var tr = $(this).closest('tr');
						var row = table.row(tr);

						if (row.child.isShown()) {
							// This row is already open - close it
							row.child.hide();
							tr.removeClass('shown');
							$(this).find('i').removeClass('fa-minus');
							$(this).find('i').addClass('fa-plus');
						} else {
							// Open this row
							row.child(format_implementation_products(row.data())).show();
							tr.addClass('shown');
							$(this).find('i').removeClass('fa-plus');
							$(this).find('i').addClass('fa-minus');
						}
					});

					$('#data-table-buttons').on('draw.dt', function() {
							Array.from(document.getElementsByClassName('fa-plus')).map(value => value.setAttribute('title', "@lang('reclamation.deploy')"));
							Array.from(document.getElementsByClassName('fa-minus')).map(value => value.setAttribute('title', "@lang('reclamation.collapse')"));
						});
						// filters

						function updateAjax() {
							var ajaxRoute = ajaxRouteBase + '?x=1' + sender + customer + date;
							if (sender == "" && customer == "" && date == "") {
								$('#clear_filter').hide();
							} else {
								$('#clear_filter').show();
							}
							window.table.ajax.url(ajaxRoute).load();
							updateTableData();
						}

						function clearFilter() {
							$("#sender").val($("#sender option:first").val());
							$("#sender").change();
							$("#customer").val($("#customer option:first").val());
							$("#customer").change();
							$("#datetimepicker5").val('');
							$("#datetimepicker6").val('');
							$('#datetimepicker6').hide();
							changeDate();
						}
						$('#sender').change(function() {
							if ($(this).val() !== '') {
								sender = '&sender_id=' + $(this).val();
							} else {
								sender = '';
							}
							updateAjax();
						});

						$('#customer').change(function() {
							if ($(this).val() !== '') {
								customer = '&customer_id=' + $(this).val();
							} else {
								customer = '';
							}
							updateAjax();
						});

						$('#datetimepicker3').datetimepicker({
							format: 'DD.MM.YYYY'
						}); $('#datetimepicker4').datetimepicker({
							format: 'DD.MM.YYYY'
						}); $("#datetimepicker3").on("dp.change", function(e) {
							$('#datetimepicker4').data("DateTimePicker").minDate(e.date);
						}); $("#datetimepicker4").on("dp.change", function(e) {
							$('#datetimepicker3').data("DateTimePicker").maxDate(e.date);
						}); $('#datetimepicker5').datetimepicker({
							format: 'DD.MM.YYYY'
						}); $('#datetimepicker6').datetimepicker({
							format: 'DD.MM.YYYY'
						});

						function changeDate() {
							if ($("#datetimepicker5").val() !== '') {
								date = '&date_from=' + $("#datetimepicker5").data("DateTimePicker").date() / 1000;
							} else {
								date = '';
							}
							$('#datetimepicker3').val($("#datetimepicker5").val());
							if ($("#datetimepicker6").val() !== '') {
								date += '&date_to=' + $("#datetimepicker6").data("DateTimePicker").date() / 1000;
							} else {
								date += '';
							}
							$('#datetimepicker4').val($("#datetimepicker6").val());
							updateAjax();
						}

						$("#datetimepicker5").on("dp.change", function(e) {
							$('#datetimepicker6').show();
							$('#datetimepicker6').data("DateTimePicker").minDate(e.date);
							changeDate();

						}); $("#datetimepicker6").on("dp.change", function(e) {
							$('#datetimepicker5').data("DateTimePicker").maxDate(e.date);
							changeDate();
						});

						$('#modal_explanation').on('show.bs.modal', function(event) {
							var button = $(event.relatedTarget);
							var modal = $(this);
							modal.find('input[name="explanation_subject"]').val(button.data('subject'));
							modal.find('.modal-title').text(button.data('subject'));
						})

						$('#form_explantion').submit(function(e) {
							e.preventDefault();
							var form = $(this);
							$('#modal_explanation').modal('hide');

							$.ajaxSetup({
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								}
							});
							$.ajax({
								method: "POST",
								url: "{{route('ticket.explanation')}}",
								data: form.serialize(),
								success: function(resp) {
									if (resp.status == "success") {
										$('#explanation_message').val('');
										$.gritter.add({
											title: '@lang('order.explanation_success')',
										});
									}
								},
								error: function(xhr, str) {
									console.log(xhr);
								}
							});

							return false;
						});
					});
			})(jQuery);
</script>
@endpush
