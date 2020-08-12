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

	<h1 class="page-header">@if(isset($page_name)) {{$page_name}} @else @lang('product.all_page_name')@endif</h1>
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
                    <div class="table-scroll-container">
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
				</div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
		</div>
		<!-- end col-10 -->
	</div>
	<!-- end row -->

	@include('product.include.modal_wishlist')
	@include('product.include.modal_order')
	@include('product.include.modal_get_price')
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
					dom: 'lBfrtip',
					@if(Request::get('instock'))
					buttons: [
            {
                text: "@lang('product.all_products_button_name')",
								className:'databtn btn btn-success',
                action: function ( e, dt, node, config ) {
								window.location.href = $(location).attr('href').substr(0,($(location).attr('href')).indexOf('?instock=on'));
                }
            }
        	],
					@else
					buttons: [
						{
								text: "@lang('product.in_stock_button_name')",
								className:'databtn btn btn-warning',
								action: function ( e, dt, node, config ) {
									window.location.href = $(location).attr('href')+'?instock=on';
								}
						}
					],
					@endif
					"pageLength": 10,
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
							data: 'html_limit_1',
						},
						{
							data: 'html_limit_2',
						},
						{
							data: 'storage_html',
							"orderable":      false,
						},
						{
							data: 'actions',
						},

					],
				});

 			//y$("div.toolbar").a('<button class="btn btn-secondary" tabindex="0" aria-controls="data-table-buttons" type="button"><span>My button</span></button>');
					@if(Request::get('instock'))
					$( ".dropdown bootstrap-select form-control" ).append( `<div class="custom-control custom-switch">
						<input type="checkbox" class="custom-control-input" id="customSwitch1" checked>
						  <label class="custom-control-label" for="customSwitch1">	@if(isset($page_name)) {{$page_name}} @else @lang('product.in_stock_page_name') @endif</label>
						</div>` );
					@else
					$( ".dropdown bootstrap-select form-control" ).append( `<div class="custom-control custom-switch">
						<input type="checkbox" class="custom-control-input" id="customSwitch1">
						  <label class="custom-control-label" for="customSwitch1">	@if(isset($page_name)) {{$page_name}} @else @lang('product.all_page_name') @endif</label>
						</div>` );
					@endif

				$('#customSwitch1').click(function() {
						@if(Request::get('instock') == 'on')
						window.location.href = $(location).attr('href').substr(0,($(location).attr('href')).indexOf('?instock=on'));
						@else
						window.location.href = $(location).attr('href')+'?instock='+$(this).val();
						@endif
				});

				$('#modal-get_price').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					modal.find('.product_id').val(button.data('product_id'));
				})

				$('#modal-get_price').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					modal.find('.product_id').val(button.data('product_id'));
				})

				$('#modal-wishlist').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					modal.find('.product_id').val(button.data('product'));
				})

				$('#modal-order').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					modal.find('.product-name').text(button.data('product_name'));
					modal.find('.product_id').val(button.data('product'));
					modal.find('.storage_id').val(button.data('storage'));
					modal.find('.order-storage-amount').text(button.data('storage_max'));
					var quantity = modal.find('input[name="quantity"]');
					quantity.val(button.data('storage_min'));
					quantity.attr('min',button.data('storage_min'));
					quantity.attr('step',button.data('storage_min'));
					quantity.attr('data-max',button.data('storage_max'));

					var quantity_request = modal.find('input[name="quantity_request"]');
					quantity_request.val(0);
					quantity_request.attr('min',0);
					quantity_request.attr('step',button.data('storage_min'));

					$('.storage-limit-info').hide();
					$('.storage-limit-request').hide();
					$('input[name="quantity_request"]').change();
				});

				$('input[name="quantity"]').change(function (e) {
					e.preventDefault();
					if($(this).val() > $(this).data('max')){
						var request =  $(this).val() - $(this).data('max');
						$(this).val($(this).data('max'));
						$('input[name="quantity_request"]').val(request);
						$('.storage-limit-info').show();
						$('.storage-limit-request').show();
						$('input[name="quantity_request"]').change();
					}
				});

                $('input[name="quantity_request"]').change(function (e) {
                    e.preventDefault();
                    if($(this).val() > 0){
						$('.btn-add-order').text($('.btn-add-order').data('btn_order_request'));
                    }else{
						$('.btn-add-order').text($('.btn-add-order').data('btn_order'));
                    }
				});

				console.log(" {!! Request::get('instock') !!} ");

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
				});
				$('#form_get_price').submit(function (e) {
					e.preventDefault();
					$('#modal-get_price').modal('hide');
					var product_id = $('#get_price_product_id').val();
					var form = $(this);

					var route = '{{route('products')}}/'+product_id+'/get-price/';

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
									title: '@lang('product.get_price_success')',
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
	<style>
	.databtn {
	  margin:0px;
	}
	</style>


@endpush
