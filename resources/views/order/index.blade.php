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

    <style>
        .table-scroll-container-xl{
            overflow-x: scroll;
        }
        .badge-status{
            width: 100%;
            text-align: center;
            font-size: 12px;
            padding: 5px 7px;
        }
    </style>

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
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row m-b-15">
                        <div class="col-lg-1">
                            <a href="{{route('orders.create')}}" class="btn btn-sm btn-green m-t-5 m-b-5 btn-block" title="@lang('order.btn_add_order')"><i class="fas fa-plus-circle"></i></a>
                        </div>
							<div class="col-lg-3">
							</div>
						<div class="col-lg-6 offset-lg-2">
							<form action="{{ route('orders.act_pdf') }}" enctype="multipart/form-data" method="get">
								@csrf
								<div class="row">
									<div class="col-lg-8">
										<div style="min-width: 100px;" class="row row-space-10">
											<div class="col-xs-6 mb-2 mb-sm-0">
												<input type="hidden" name="date_from" class="date_picker form-control" id="datetimepicker3" placeholder="@lang('order.act_date_from')" required>
											</div>
											<div class="col-xs-6">
												<input type="hidden" name="date_to" class="date_picker form-control" id="datetimepicker4" placeholder="@lang('order.act_date_to')" required>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<button type="submit" class="btn btn-sm btn-primary m-b-5 btn-block" title="@lang('order.btn_act_pdf')"><i class="fas fa-columns"></i></button>
									</div>
								</div>
							</form>
						</div>
					</div>
                    <div class="table-scroll-container table-scroll-container-xl">
					<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle">
						<thead>
							<tr>
								<th class="text-nowrap text-center">@lang('order.table_header_number')</th>
								<th class="text-nowrap text-center">@lang('order.table_header_number')</th>
								<th class="text-nowrap" width="120">
                                    <div class="row row-space-10">
                                        <div class="col-xs-12 ">
                                            <input type="text" name="act_date_from" class="date_picker form-control" id="datetimepicker5" placeholder="@lang('order.table_header_date')" required>
                                        </div>
                                        <div class="col-xs-12" >
                                            <input type="text" name="act_date_to" class="date_picker form-control m-е-5" id="datetimepicker6" placeholder="@lang('order.act_date_to')" required style="display: none">
                                        </div>
                                    </div></th>
								<th class="text-nowrap">
                                    <div><select class="form-control selectpicker" id="status" data-size="10" data-live-search="true" data-style="btn-white">
                                        <option value="" selected>@lang('order.table_header_status')</option>
                                        @foreach($statuses as $status)
                                            <option value="{{$status->id}}" class="order-status order-status-tab-{{(($status->id<=5)?'order order-status-tab_active':(($status->id<=7)?'archive':'request'))}}">{{$status->name}}</option>
                                        @endforeach
                                    </select></div></th>
								<th class="text-nowrap">
                                    <div><select class="form-control selectpicker" id="payment" data-size="10" data-live-search="true" data-style="btn-white">
                                            <option value="" selected>@lang('order.table_header_status_payment')</option>
                                            <option value="none">@lang('order.payment_status_none')</option>
                                            <option value="partial">@lang('order.payment_status_partial')</option>
                                            <option value="success">@lang('order.payment_status_success')</option>
                                        </select></div></th>
								<th class="text-nowrap" style="width: 80px; min-width: 80px">@lang('order.table_header_total')</th>
								<th class="text-nowrap">
                                    <div><select class="form-control selectpicker" id="sender" data-size="10" data-live-search="true" data-style="btn-white">
                                            <option value="" selected>@lang('order.table_header_customer')</option>
                                            @foreach($senders as $name => $id)
                                                <option value="{{$id}}" >{{$name}}</option>
                                            @endforeach
                                        </select></div>
                                </th>
								<th class="text-nowrap">
                                    <div><select class="form-control selectpicker" id="customer" data-size="10" data-live-search="true" data-style="btn-white">
                                            <option value="" selected>@lang('order.table_header_user')</option>
                                            @foreach($customers as $name => $id)
                                                <option value="{{$id}}" >{{$name}}</option>
                                            @endforeach
                                        </select></div></th>
								<th style="width: 150px; min-width: 150px"></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
                        <tfoot>
                            <td colspan="9" class="text-nowrap text-left">
                                <span class="m-r-15">
                                    <strong>@lang('order.table_footer_pc')</strong> <span id="footer_pc">1</span>
                                </span>
                                <span class="m-r-15">
                                    <strong>@lang('order.table_footer_total')</strong> <span id="footer_total">1</span> @lang('global.grn')
                                </span>
                                <span class="m-r-15">
                                    <strong>@lang('order.table_footer_discount')</strong> <span id="footer_discount">1</span> @lang('global.grn')
                                </span>
                                <span class="m-r-15">
                                    <strong>@lang('order.table_footer_payed')</strong> <span id="footer_payed">1</span> @lang('global.grn')
                                </span>
                                <span class="m-r-15">
                                    <strong>@lang('order.table_footer_not_payed')</strong> <span id="footer_not_payed">1</span> @lang('global.grn')
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



	{{--<script src="/assets/js/demo/table-manage-buttons.demo.js"></script>--}}

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {
				var ajaxRouteBase = "{!! route('orders.all_ajax') !!}";
				var tableDataRoute = "{!! route('orders.total_data_ajax') !!}";
				var ajaxRouteTab = "?tab=order";
				var status = '';
				var payment = '';
				var sender = '';
				var customer = '';
				var date = '';

				$('#status').change(function () {
					if($(this).val() !== ''){
						status = '&status_id='+$(this).val();
                    }else{
						status = '';
                    }
					updateAjax();
				});

				$('#payment').change(function () {
					if($(this).val() !== ''){
						payment = '&payment='+$(this).val();
                    }else{
						payment = '';
                    }
					updateAjax();
				});

				$('#sender').change(function () {
					if($(this).val() !== ''){
						sender = '&sender_id='+$(this).val();
                    }else{
						sender = '';
                    }
					updateAjax();
				});

				$('#customer').change(function () {
					if($(this).val() !== ''){
						customer = '&customer_id='+$(this).val();
                    }else{
						customer = '';
                    }
					updateAjax();
				});

				function updateAjax(){
					var ajaxRoute = ajaxRouteBase + ajaxRouteTab + status + payment + sender + customer + date;
					if(status == "" && payment == "" && sender == "" && customer == "" && date == ""){
						$('#clear_filter').hide();
                    }else{
						$('#clear_filter').show();
                    }
					window.table.ajax.url( ajaxRoute ).load();
					updateTableData();
                }

                function clearFilter(){
					$("#status").val($("#status option:first").val());
					$("#status").change();
					$("#payment").val($("#payment option:first").val());
					$("#payment").change();
					$("#sender").val($("#sender option:first").val());
					$("#sender").change();
					$("#customer").val($("#customer option:first").val());
					$("#customer").change();
					$("#datetimepicker5").val('');
					$("#datetimepicker6").val('');
					$('#datetimepicker6').hide();
					changeDate();
                }

                function updateTableData(){
					var route = tableDataRoute + ajaxRouteTab + status + payment + sender + customer + date;

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
										$("#footer_"+el[0]).text(el[1]);
                                    }
								});

							}
						},
						error: function (xhr, str) {
							console.log(xhr);
						}
					});
                }

				$('#order_tab a').click(function (e) {
                    e.preventDefault();
                    var tab = this.id.replace('tab_','');

					ajaxRouteTab = "?tab="+tab;

                    $('#status option').removeClass('order-status-tab_active');
                    $('#status option.order-status-tab-'+tab).addClass('order-status-tab_active');
                    $('#status option:first-child').prop('selected', 'selected');
                    $('#status').change();
					$('#status').selectpicker('refresh');
				});
				window.table = $('#data-table-buttons').DataTable( {
					"language": {
						"url": "@lang('table.localization_link')",
					},
					"pageLength": 25,
					"autoWidth": false,
					"processing": true,
					"serverSide": true,
					"ajax": ajaxRouteBase+ajaxRouteTab,
					"order": [[ 0, "desc" ]],
					//"ordering": false,
					//"searching": true,
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
					fixedHeader: {
						header: true,
						footer: true
					},
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
							"orderable":      false,
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
                            width: '150px',
						},
					],
				} );

				updateTableData();

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

				$('#modal_explanation').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					modal.find('input[name="explanation_subject"]').val(button.data('subject'));
					modal.find('.modal-title').text(button.data('subject'));
				})

                $('#form_explantion').submit(function (e) {
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
						success: function (resp) {
							if (resp.status == "success") {
								$('#explanation_message').val('');
								$.gritter.add({
									title: '@lang('order.explanation_success')',
								});
							}
						},
						error: function (xhr, str) {
							console.log(xhr);
						}
					});

                    return false;
				});
			});
		})(jQuery);
	</script>
@endpush
