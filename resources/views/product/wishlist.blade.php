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
	{{ Breadcrumbs::render('catalogs') }}

	<h1 class="page-header">@lang('wishlist.page_list')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('wishlist.list')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row m-b-15">
						<div class="col-lg-3">
							<select class="form-control selectpicker m-b-5" id="change_wishlist" data-size="10" data-live-search="true" data-style="btn-white">
								@foreach($wishlists as $wishlist)
									<option value="{{$wishlist->id}}" data-main="{{$wishlist->is_main}}" data-price="{{$wishlist->price_id}}" @if(session('current_catalog') == $wishlist->id) selected="selected" @endif>{{$wishlist->name}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-2 offset-lg-1">
							<a href="#modal-wishlist_new" id="new_wishlist_btn" class="btn btn-sm btn-primary btn-block m-b-5" data-toggle="modal">@lang('wishlist.btn_new')</a>
						</div>
						<div class="col-lg-2">
							<a href="#modal-wishlist_rename" class="btn btn-sm btn-primary btn-block m-b-5" data-toggle="modal">@lang('wishlist.btn_rename')</a>
						</div>
						<div class="col-lg-2">
							<a href="#modal-wishlist_price" class="btn btn-sm btn-primary btn-block m-b-5" data-toggle="modal">@lang('wishlist.btn_price')</a>
						</div>
						<div class="col-lg-2">
							<a href="#modal-wishlist_delete" id="delete_wishlist_btn" class="btn btn-sm btn-danger btn-block m-b-5" data-toggle="modal">@lang('wishlist.btn_delete')</a>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('wishlist.add_product')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<form id="wishlist_add_product_form" action="{{route('catalogs.store')}}" method="get">
						@csrf
					<div class="row m-b-15">
						<div class="col-lg-10">
							<select class="form-control m-b-5" id="product_select" name="product_id">
							</select>
						</div>
						<div class="col-lg-2">
							<button type="submit" class="btn btn-sm btn-primary btn-block m-b-5">@lang('wishlist.add_product_btn')</button>
						</div>
					</div>
					</form>
				</div>
			</div>
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
					<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle">
						<thead>
							<tr>
								<th></th>
								<th width="30"></th>
								<th width="1%" data-orderable="false"></th>
								<th class="text-nowrap">@lang('wishlist.table_header_name')</th>
								<th class="text-nowrap">@lang('wishlist.table_header_article')</th>
								<th class="text-nowrap">@lang('wishlist.table_header_holding_article')</th>
								<th class="text-nowrap">@lang('wishlist.table_header_price')</th>
								<th class="text-nowrap">@lang('wishlist.table_header_user_price')</th>
								<th class="text-nowrap">@lang('wishlist.table_header_storage')</th>
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

	@include('product.include.modal_wishlist_new')
	@include('product.include.modal_wishlist_rename')
	@include('product.include.modal_wishlist_price')
	@include('product.include.modal_wishlist_delete')
	@include('product.include.modal_order')
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
				var ajaxRouteBase = "{!! route('catalogs.all_ajax') !!}"
				var ajaxRoute = "{!! route('catalogs.all_ajax') !!}?group={{session('current_catalog')}}"

				window.table = $('#data-table-buttons').DataTable( {
					"language": {
						"url": "@lang('table.localization_link')",
					},
					"scrollX": true,
					"pageLength": 25,
					"autoWidth": true,
					"processing": true,
					"serverSide": true,
					"ajax": ajaxRoute,
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
							"visible": false,
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
							data: 'article_holding',
						},
						{
							"orderable":      false,
							data: 'user_price',
						},
						{
							"orderable":      false,
							data: 'catalog_price',
						},
						{
							data: 'storage_html',
							"orderable":      false,
						},
						{
							data: 'actions',
						},
					],
					"drawCallback": function( settings ) {
						$('.holding-article').change(function (e) {
							e.preventDefault();
							var product_id = $(this).data('product');
							var article = $(this).val();
							var route = '{{route('catalogs')}}/change-article/' + product_id;

							$.ajaxSetup({
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								}
							});
							$.ajax({
								method: "POST",
								url: route,
								data: {
									article: article
								},
								success: function (resp) {
									if (resp == "ok") {
										window.table.ajax.reload();
									}else{
										window.table.ajax.reload();
										$.gritter.add({
											title: resp,
										});
									}
								},
								error: function (xhr, str) {
									console.log(xhr);
								}
							});
						})

						$('.product-wishlist-remove').on('click',function (e) {
							e.preventDefault();

							var product_id = $(this).data('product');
							var route = '{{route('catalogs')}}/remove-to-catalog/' + $('#change_wishlist').val();

							$.ajaxSetup({
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								}
							});
							$.ajax({
								method: "POST",
								url: route,
								data: {
									product_id: product_id
								},
								success: function (resp) {
									if (resp == "ok") {
										window.table.ajax.reload();
									}
								},
								error: function (xhr, str) {
									console.log(xhr);
								}
							});
						});
					}
				} );


				$('#modal-order').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					modal.find('.product_id').val(button.data('product'));
					modal.find('.storage_id').val(button.data('storage'));
					var quantity = modal.find('input[name="quantity"');
					quantity.val(button.data('storage_min'));
					quantity.attr('min',button.data('storage_min'));
					quantity.attr('step',button.data('storage_min'));
					quantity.attr('max',button.data('storage_max'));
				})

				$('#form_add_order').submit(function (e) {
					e.preventDefault();

					$('#modal-order').modal('hide');

					var form = $(this);

					var order_id = $('#order_id').val();
					var route = '{{route('orders')}}/add-to-order/'+order_id;

					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					$.ajax({
						method: "POST",
						url: route,
						data: form.serialize(),
						success: function(resp)
						{
							if(resp == "ok"){
								$.gritter.add({
									title: '@lang('order.modal_success')',
								});
								if(order_id == 0){
									document.location.reload(true);
								}
								window.table.ajax.reload();
							}
						},
						error:  function(xhr, str){
							console.log(xhr);
						}
					});

					return false;
				})


				setList();
				$('#change_wishlist').change(function () {
					ajaxRoute = ajaxRouteBase+'?group='+$(this).val();
					window.table.ajax.url( ajaxRoute ).load();
					setList();
				});

				function setList() {
					var new_form = $('#wishlist_new_form');

					var list_name = $("#change_wishlist option:selected").text();
					var price = $("#change_wishlist option:selected").data('price');

					$('#wishlist_rename_form').attr('action',new_form.attr('action')+'/'+$('#change_wishlist').val());
					$('#wishlist_delete_form').attr('action',new_form.attr('action')+'/destroy/'+$('#change_wishlist').val());
					$('#wishlist_price_form').attr('action',new_form.attr('action')+'/set-price/'+$('#change_wishlist').val());
					$('#wishlist_add_product_form').attr('action',new_form.attr('action')+'/add-to-catalog/'+$('#change_wishlist').val());
					$('#wishlist_rename_form').find('input[name="rename"]').val(list_name);
					$('#wishlist_price_form').find('select').val(price);
					$('#wishlist_price_form').find('select').selectpicker('render');

					if($("#change_wishlist option:selected").data('main') == 1){
						$('#delete_wishlist_btn').hide(0);
						$('#new_wishlist_btn').parent().addClass('offset-lg-3');
						$('#new_wishlist_btn').parent().removeClass('offset-lg-1');
					}else{
						$('#delete_wishlist_btn').show(0);
						$('#new_wishlist_btn').parent().addClass('offset-lg-1');
						$('#new_wishlist_btn').parent().removeClass('offset-lg-3');
					}
				}


				$('#product_select').select2({
					placeholder: "@lang('wishlist.select_product')",
					minimumInputLength: 3,
					ajax: {
						url: function () {
							return '{{route('products.search')}}'
						},
						dataType: 'json',
						data: function (params) {
							return {
								name: params.term
							};
						},
						processResults: function (data) {
							return {
								results: data
							};
						},
						cache: true
					}
				});

				$('#wishlist_add_product_form').submit(function (e) {
					e.preventDefault();

					var form = $(this);
					var route = form.attr('action');

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
								window.table.ajax.reload();
							}
						},
						error:  function(xhr, str){
							console.log(xhr);
						}
					});

					return false;
				})

				@error('name')
					$('#modal-wishlist_new').modal('show');
				@enderror


				@error('rename')
					$('#modal-wishlist_rename').modal('show');
				@enderror
			});
		})(jQuery);
	</script>
@endpush