@extends('layouts.default')

@push('css')
    <link href="/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
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
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
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
                                <th class="text-nowrap text-center">@lang('ticket.table_header_status')</th>
                                <th class="text-nowrap">@lang('ticket.table_header_user')</th>
                                <th class="text-nowrap">@lang('ticket.table_header_manager')</th>
                                <th class="text-nowrap text-center">@lang('ticket.table_header_message_count')</th>
                                <th class="text-nowrap text-center">@lang('ticket.table_header_new_message_count')</th>
                                <th class="text-nowrap">@lang('ticket.table_header_time')</th>
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

    <script>
		(function ($) {
			"use strict";
			$(document).ready(function() {

				var changeStatusRoute = "{{route('ticket')}}";

				window.table = $('#data-table-buttons').DataTable( {
					"language": {
						"url": "@lang('table.localization_link')",
					},
					"pageLength": 25,
					"autoWidth": true,
					"processing": true,
					"serverSide": true,
					"ajax": "{!! route('ticket.ajax') !!}",
					"order": [[ 6, "desc" ]],
					"ordering": true,
					"searching": true,
					"columns": [
						{
							data: 'subject_html',
							"orderable":      false,
						},
						{
							className: 'text-center',
							data: 'status',
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

			});
		})(jQuery);
    </script>
@endpush
