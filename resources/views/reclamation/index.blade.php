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
{{ Breadcrumbs::render('reclamation.all') }}

<h1 class="page-header">@lang('reclamation.page_list')</h1>
<!-- begin row -->
<div class="row">
	<!-- begin col-10 -->
	<div class="col-xl-12">
		<!-- begin panel -->
		<div class="panel panel-primary">
			<!-- begin panel-heading -->
			<div class="panel-heading">
				<h4 class="panel-title">@lang('reclamation.all_tab_name')</h4>
			</div>
			<!-- end panel-heading -->
			<!-- begin panel-body -->
			<div class="panel-body">
				<div class="row m-b-15">
					<div class="col-lg-1">
						<a href="{{route('reclamations.create')}}" class="btn btn-sm btn-green m-t-5 m-b-5 btn-block" title="@lang('reclamation.btn_new_reclamation')"><i class="fas fa-plus-circle"></i></a>
					</div>
				</div>
				<div class="table-scroll-container">
					<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle">
						<thead>
							<tr>
								<th class="text-nowrap">@lang('reclamation.table_header_product')</th>
								<th class="text-nowrap text-center">@lang('reclamation.table_header_file')</th>
								<th class="text-nowrap text-center">@lang('reclamation.table_header_realisation_number')</th>
								<th class="text-nowrap">@lang('reclamation.table_header_ttn')</th>
								<th class="text-nowrap text-center">@lang('reclamation.table_header_status')</th>
								<th class="text-nowrap">@lang('reclamation.table_header_author')</th>
								<th class="text-nowrap" style="width: 80px"></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<td colspan="7" class="text-nowrap text-left">
								<span class="m-r-15">
									<strong>@lang('reclamation.table_footer_pc')</strong> <span id="footer_pc">1</span>
								</span>
								<span class="m-r-15">
									<strong>@lang('reclamation.table_footer_total')</strong> <span id="footer_total">1</span> @lang('global.grn')
								</span>
								<span class="m-r-15">
									<strong>@lang('reclamation.table_footer_weight')</strong> <span id="footer_weight">1</span> @lang('global.kg')
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
	(function($) {
		"use strict";
		$(document).ready(function() {
			var tableDataRoute = "{!! route('reclamations.total_data_ajax') !!}";

			function updateTableData() {
				var route = tableDataRoute;

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
				"ajax": "{!! route('reclamations.ajax') !!}",
				"order": [
					[0, "desc"]
				],
				"ordering": false,
				"searching": false,
				"columns": [

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
						className: 'text-center file_reclamation',
						data: 'file_html',
					},
					{
						className: 'text-center',
						data: 'implementation',
					},
					{
						data: 'ttn',
					},
					{
						className: 'text-center',
						data: 'status_html',
					},
					{
						data: 'user',
					},
					{
						data: 'action_buttons',
					},
				],
				"columnDefs": [{
					"title": "БЛАБЛАБЛА"
				}, ],
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

			$('#modal_explanation').on('show.bs.modal', function(event) {
				var button = $(event.relatedTarget);
				var modal = $(this);
				modal.find('input[name="explanation_subject"]').val(button.data('subject'));
				modal.find('.modal-title').text(button.data('subject'));
			})

			$('#data-table-buttons').on('draw.dt', function() {
				Array.from(document.getElementsByClassName('file_reclamation')).map(value => value.setAttribute('title', 'Завантажити'))
				Array.from(document.getElementsByClassName('fa-plus')).map(value => value.setAttribute('title', "@lang('reclamation.deploy')"))
				Array.from(document.getElementsByClassName('fa-minus')).map(value => value.setAttribute('title', "@lang('reclamation.collapse')"))
			});

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
