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
	{{ Breadcrumbs::render('ticket') }}
	<h1 class="page-header">@lang('ticket.page_name')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('ticket.tab_list')</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row m-b-15">
						<div class="col-md-4">
							<a href="{{route('ticket.create')}}" class="btn btn-primary">@lang('ticket.button_new')</a>
						</div>
						<div class="col-md-8">
						</div>
					</div>
                    <div class="table-scroll-container">
                        <table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle">
                            <thead>
                            <tr>
                                <th class="text-nowrap">@lang('ticket.table_header_subject')</th>
                                <th class="text-nowrap text-center"><div><select class="form-control selectpicker" id="status" data-size="10" data-live-search="false" data-style="btn-white">
                                            <option value="" selected>@lang('ticket.table_header_status')</option>
                                                <option value="open">@lang('ticket.filter_open')</option>
                                                <option value="close">@lang('ticket.filter_close')</option>
                                        </select></div></th>
                                <th class="text-nowrap"><div><select class="form-control selectpicker" id="user" data-size="10" data-live-search="true" data-style="btn-white">
                                            <option value="" selected>@lang('ticket.table_header_user')</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select></div></th>
                                <th class="text-nowrap"><div><select class="form-control selectpicker" id="manager" data-size="10" data-live-search="true" data-style="btn-white">
                                            <option value="" selected>@lang('ticket.table_header_manager')</option>
                                            @foreach($managers as $manager)
                                                <option value="{{$manager->id}}">{{$manager->name}}</option>
                                            @endforeach
                                        </select></div></th>
                                <th class="text-nowrap text-center">@lang('ticket.table_header_message_count')</th>
                                <th class="text-nowrap text-center"><div><select class="form-control selectpicker" id="is_new_message" data-size="10" data-live-search="false" data-style="btn-white">
                                            <option value="" selected>@lang('ticket.table_header_new_message_count')</option>
                                            <option value="new">@lang("ticket.filter_new")</option>
                                            <option value="old">@lang("ticket.filter_old")</option>
                                        </select></div></th>
                                <th class="text-nowrap" style="min-width: 200px"><div class="row row-space-10">
                                        <div class="col-xs-12 mb-2 m-b-5">
                                            <input type="text" name="act_date_from" class="form-control" id="datetimepicker5" placeholder="@lang('ticket.table_header_time')" required>
                                        </div>
                                        <div class="col-xs-12" >
                                            <input type="text" name="act_date_to" class="form-control" id="datetimepicker6" placeholder="@lang('order.act_date_to')" required style="display: none">
                                        </div>
                                    </div></th>
                                <th></th>
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

    <script>
		(function ($) {
			"use strict";
			$(document).ready(function() {

				var changeStatusRoute = "{{route('ticket')}}";

				var ajaxRouteBase = "{!! route('ticket.ajax') !!}";
				var status = '';
				var user = '';
				var manager = '';
				var new_message = '';
				var date = '';


				window.table = $('#data-table-buttons').DataTable( {
					"language": {
						"url": "@lang('table.localization_link')",
					},
					"pageLength": 25,
					"autoWidth": false,
					"processing": true,
					"serverSide": true,
					"ajax": "{!! route('ticket.ajax') !!}",
					"order": [[ 6, "desc" ]],
					"ordering": true,
					"searching": true,
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
							data: 'subject_html',
							"orderable":      false,
						},
						{
							className: 'text-center',
							data: 'status_html',
							"orderable":      false,
						},
						{
							data: 'user_html',
							"orderable":      false,
						},
						{
							data: 'manager_html',
							"orderable":      false,
						},
						{
							className: 'text-center',
							data: 'message_count_html',
							"orderable":      false,
						},
						{
							className: 'text-center',
							data: 'new_messages_count_html',
							"orderable":      false,
						},
						{
							data: 'created_at_html',
						},
						{
							data: 'action_buttons',
							"orderable":      false,
						},
					],
					"drawCallback": function( settings ) {
						$('.btn-change-status').click(function (e) {
							e.preventDefault();
							var route = changeStatusRoute + '/' + $(this).data('id') + '/change-status'


							$.ajaxSetup({
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								}
							});
							$.ajax({
								method: "POST",
								url: route,
								success: function (resp) {
									if (resp.status == "success") {
										window.table.ajax.reload();
									}
								},
								error: function (xhr, str) {
									console.log(xhr);
								}
							});

							return false;
						});
					}
				} );

				$('#status').change(function () {
					if($(this).val() !== ''){
						status = '&status='+$(this).val();
					}else{
						status = '';
					}
					updateAjax();
				});

				$('#user').change(function () {
					if($(this).val() !== ''){
						user = '&user_id='+$(this).val();
					}else{
						user = '';
					}
					updateAjax();
				});

				$('#manager').change(function () {
					if($(this).val() !== ''){
						manager = '&manager_id='+$(this).val();
					}else{
						manager = '';
					}
					updateAjax();
				});

				$('#is_new_message').change(function () {
					if($(this).val() !== ''){
						new_message = '&is_new_message='+$(this).val();
					}else{
						new_message = '';
					}
					updateAjax();
				});

				function clearFilter(){
					$("#status").val($("#status option:first").val());
					$("#status").change();
					$("#user").val($("#user option:first").val());
					$("#user").change();
					$("#manager").val($("#manager option:first").val());
					$("#manager").change();
					$("#is_new_message").val($("#is_new_message option:first").val());
					$("#is_new_message").change();
					$("#datetimepicker5").val('');
					$("#datetimepicker6").val('');
					$('#datetimepicker6').hide();
					changeDate();
				}

				function updateAjax(){
					var ajaxRoute = ajaxRouteBase + '?f=1' + status + user + manager + new_message + date;

					if(status == "" && user == "" && manager == "" && new_message == "" && date == ""){
						$('#clear_filter').hide();
					}else{
						$('#clear_filter').show();
					}
					window.table.ajax.url( ajaxRoute ).load();
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
