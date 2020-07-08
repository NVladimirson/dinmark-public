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
	{{ Breadcrumbs::render('client.all') }}

	<h1 class="page-header">@lang('client.page_list')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('client.all_tab_name')</h4>
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
                            <a href="{{route('clients.create')}}" class="btn btn-sm btn-green m-t-5 m-b-5 btn-block" title="@lang('client.btn_new_client')"><i class="fas fa-plus-circle"></i></a>
                        </div>
                    </div>
                    <div class="table-scroll-container">
					<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle">
						<thead>
							<tr>
								<th class="text-nowrap text-center">@lang('client.table_header_id')</th>
								<th class="text-nowrap">@lang('client.table_header_name')</th>
								<th class="text-nowrap">@lang('client.table_header_phone')</th>
								<th class="text-nowrap">@lang('client.table_header_email')</th>
								<th class="text-nowrap">@lang('client.table_header_company')</th>
								<th class="text-nowrap">@lang('client.table_header_edrpo')</th>
								<th class="text-nowrap">@lang('client.table_header_address')</th>
								<th class="text-nowrap text-center" width="80"></th>
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

	<div class="modal fade" id="modal-client_delete" style="display: none;" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">@lang('client.confirm_delete_header')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" id="client_delete_form" method="post">
					@csrf
					<div class="modal-body">
						<div class="alert alert-danger m-b-0">
							<h5><i class="fa fa-info-circle"></i>@lang('client.confirm_delete_header')</h5>
							<p>@lang('client.confirm_delete_text')</p>
						</div>
					</div>
					<div class="modal-footer">
						<a href="javascript:;" class="btn btn-white" data-dismiss="modal">@lang('global.cancel')</a>
						<button type="submit" class="btn btn-danger">@lang('global.confirm')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="/assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
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
					"ajax": "{!! route('clients.ajax') !!}",
					"order": [[ 0, "desc" ]],
					"columns": [
						{
							className: 'text-center',
							data: 'id',
							"visible": false,
						},
						{
							data: 'name',
						},
						{
							data: 'phone',
						},
						{
							data: 'email',
						},
						{
							data: 'company_name',
						},
						{
							className: 'text-center',
							data: 'company_edrpo',
						},
						{
							data: 'address',
						},
						{
							"orderable":      false,
							data: 'actions',
						},
					],
				} );

				$('#modal-client_delete').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					modal.find('#client_delete_form').attr('action',button.data('action'));
				})

				$('#client_delete_form').submit(function (e) {
					e.preventDefault();
					$('#modal-client_delete').modal('hide');
					var route = $(this).attr('action');
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					$.ajax({
						method: "POST",
						url: route,
						success: function(resp)
						{
							if(resp == "ok"){
								$.gritter.add({
									title: '@lang('client.modal_destroy_success')',
								});
								window.table.ajax.reload();
							}
						},
						error:  function(xhr, str){
							console.log(xhr);
						}
					});

					return false;
				})

			});

			@if (session('status'))
			$.gritter.add({
				title: '{{ session('status') }}',
			});
			@endif
		})(jQuery);
	</script>
@endpush
