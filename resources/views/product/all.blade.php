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
	@if(isset($breadcrumbs))
		{{ Breadcrumbs::render('product.categories',$breadcrumbs) }}
		@else
		{{ Breadcrumbs::render('product.all') }}
	@endif

	<h1 class="page-header">@if(isset($page_name)) {{$page_name}} @else @lang('product.all_page_name') @endif</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('product.all_tab_name')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row m-b-15">
						@if($categories->count() > 0)
						<div class="col-lg-4">
							<select class="form-control selectpicker" id="category" data-size="10" data-live-search="true" data-style="btn-white">
								<option value="" selected>@lang('product.select_category')</option>
								@foreach($categories as $category)
									<option value="{{-$category->content}}">{{$category->name}}</option>
								@endforeach
							</select>
						</div>
						@endif
					</div>
					<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle">
						<thead>
							<tr>
								<th></th>
								<th width="30"></th>
								<th width="1%" data-orderable="false"></th>
								<th class="text-nowrap">@lang('product.table_header_name')</th>
								<th class="text-nowrap">@lang('product.table_header_article')</th>
								<th class="text-nowrap">@lang('product.table_header_price')</th>
								<th class="text-nowrap">@lang('product.table_header_price_porog_1')</th>
								<th class="text-nowrap">@lang('product.table_header_price_porog_2')</th>
								<th class="text-nowrap">@lang('product.table_header_storage')</th>
								<th width="100"></th>
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

	@include('product.include.modal_wishlist')
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
				$('#category').change(function () {
					if($(this).val() != ''){
						window.location.href = '{{route('products')}}/category/'+$(this).val();
					}
				});

				window.table = $('#data-table-buttons').DataTable( {
					"language": {
						"url": "@lang('table.localization_link')",
					},
					//"scrollX": true,
					"pageLength": 25,
					"autoWidth": true,
					"processing": true,
					"serverSide": true,
					"ajax": "{!! route('products.all_ajax') !!}{!!  (Request::route()->getName() != 'products')?'?category_id='.$id:'' !!}",
					"order": [[ 0, "desc" ]],
					"columns": [
						{
							className: 'text-center',
							data: 'id',
							"visible": false,
							"searchable": false
						},
						{
							"orderable":      false,
							data: 'check_html',
						},
						{
							"orderable":      false,
							data: 'image_html',
						},
						{
							"orderable":      false,
							data: 'name_html',
						},
						{
							data: 'article_show_html',
						},
						{
							data: 'user_price',
						},
						{
							data: 'limit_1',
						},
						{
							data: 'limit_2',
						},
						{
							data: 'storage_html',
							"orderable":      false,
						},
						{
							data: 'actions',
						},
					],
				} );

				$('#modal-wishlist').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					modal.find('.product_id').val(button.data('product'));
				})

				$('#form_add_catalog').submit(function (e) {
					e.preventDefault();

					$('#modal-wishlist').modal('hide');

					var form = $(this);
					let list_id = $('#wishlist').val();
					var route = '{{route('catalogs')}}/add-to-catalog/'+list_id;

					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					$.ajax({
						method: "GET",
						url: route,
						data: form.serialize(),
						success: function(resp)
						{
							if(resp == "ok"){
								$.gritter.add({
									title: '@lang('wishlist.modal_success')',
								});
							}
						},
						error:  function(xhr, str){
							console.log(xhr);
						}
					});

					return false;
				})
			});
		})(jQuery);
	</script>
@endpush