@extends('layouts.default')

@section('title', 'Managed Tables - Buttons')

@push('css')
	<link href="/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
	<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
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
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle">
						<thead>
							<tr>
								<th class="text-nowrap text-center"></th>
								<th class="text-nowrap text-center"></th>
								<th class="text-nowrap text-center">@lang('implementation.table_header_number')</th>
								<th class="text-nowrap">@lang('implementation.table_header_sender')</th>
								<th class="text-nowrap">@lang('implementation.table_header_customer')</th>
								<th class="text-nowrap">@lang('implementation.table_header_ttn')</th>
								<th class="text-nowrap">@lang('implementation.table_header_total')</th>
								<th class="text-nowrap" width="10"></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
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
	{{--<script src="/assets/js/demo/table-manage-buttons.demo.js"></script>--}}

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {

				window.table = $('#data-table-buttons').DataTable( {
					"language": {
						"url": "@lang('table.localization_link')",
					},
					"pageLength": 25,
					"autoWidth": true,
					"processing": true,
					"serverSide": true,
					"ajax": "{!! route('implementations.ajax') !!}",
					"order": [[ 0, "desc" ]],
					"ordering": false,
					"searching": false,
					"columns": [
						{
							data: 'id',
							"visible": false,
							"searchable": false
						},
						{
							"className":      'details-control text-center',
							"orderable":      false,
							"data":           null,
							"defaultContent": '',
							render: function ( data, type, row ) {
								return '<i class="fa fa-plus" aria-hidden="true"></i>';
							},
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
							data: 'ttn',
						},
						{
							data: 'total',
						},
						{
							data: 'btn_pdf',
						},
					],
				} );
				function format_implementation_products(data) {
					return data.products;
				}

				$('#data-table-buttons tbody').on('click', 'td.details-control', function () {
					var tr = $(this).closest('tr');
					var row = table.row( tr );

					if ( row.child.isShown() ) {
						// This row is already open - close it
						row.child.hide();
						tr.removeClass('shown');
						$(this).find('i').removeClass('fa-minus');
						$(this).find('i').addClass('fa-plus');
					}
					else {
						// Open this row
						row.child( format_implementation_products(row.data()) ).show();
						tr.addClass('shown');
						$(this).find('i').removeClass('fa-plus');
						$(this).find('i').addClass('fa-minus');
					}
				} );

			});
		})(jQuery);
	</script>
@endpush