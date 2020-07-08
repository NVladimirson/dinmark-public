@extends('layouts.default')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
@endpush

@section('content')
	{{ Breadcrumbs::render('product.show',$product, $productName) }}
	<h1 class="page-header">{{$productName}}</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('product.show_tab_name')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
						<div class="col-md-5 text-center">
							<img src="{{$imagePath}}" alt="{{$productName}}" width="100%">
						</div>
						<div class="col-md-7">
							<h3>@lang('product.header_main_info')</h3>
							<table class="table table-striped">
								<tr>
									<th>@lang('product.show_article')</th>
									<td>{{ $product->article_show }}</td>
								</tr>
								<tr>
									<th>@lang('product.show_price')</th>
									<td>{{ $basePrice }}</td>
								</tr>
								<tr>
									<th>@lang('product.show_your_price')</th>
									<td>{{ $price }}</td>
								</tr>
								@if($product->limit_1 > 0)
								<tr>
									<th>@lang('product.show_price_porog_1')</th>
									<td>{{ $limit1 }}</td>
								</tr>
								@endif
								@if($product->limit_2 > 0)
								<tr>
									<th>@lang('product.show_price_porog_2')</th>
									<td>{{ $limit2  }}</td>
								</tr>
								@endif
								<tr>
									<th>@lang('product.show_weight')</th>
									<td>{{ $product->weight  }} @lang('product.weight_kg')</td>
								</tr>
							</table>
							<a href="#modal-wishlist" class="btn btn-sm btn-primary m-r-5 m-b-15" data-toggle="modal" data-product="{{$product->id}}"><i class="fas fa-star"></i> @lang('wishlist.button_add_to_catalog')</a>
							<h3>@lang('product.header_params')</h3>
							<table class="table table-striped">
								@php
									$lang = LaravelLocalization::getCurrentLocale() == 'ua'?'uk':LaravelLocalization::getCurrentLocale();
								@endphp
								@foreach($product->options as $option)
									@if(isset($option->option_val))
										@if( $option->option_val->active > 0)
										<tr>
											@php
												$optionName = $option->translates->firstWhere('language',$lang);
											@endphp
											<th>{{$optionName?$optionName->name:''}}</th>
											<td>{{ $option->val->name }} {{$optionName?$optionName->sufix:''}}</td>
										</tr>
										@endif
									@endif
								@endforeach
							</table>
						</div>
					</div>
				</div>
				<!-- end panel-body -->
			</div>
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('product.header_storage')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
                        <div class="table-scroll-container">
							<table class="table table-striped">
								<tr>
									<th>@lang('product.storage_name')</th>
									<th>@lang('product.storage_amount')</th>
									<th>@lang('product.storage_package')</th>
									<th>@lang('product.storage_limit_1')</th>
									<th>@lang('product.storage_limit_2')</th>
									<th>@lang('product.storage_term')</th>
									<th>@lang('product.storage_quantity')</th>
									<th></th>
								</tr>
								@forelse($product->storages as $storage)
								<tr>
									<td>@lang('product.storage_name') {{ $storage->storage->id }}</td>
									<td>{{ $storage->amount }}</td>
									<td>{{ $storage->package }}</td>
									<td>{{ $storage->limit_1 }}</td>
									<td>{{ $storage->limit_2 }}</td>
									<td>{{ $storage->storage->term }}</td>
									<td>
										<input type="number" name="quantity" class="form-control m-b-5" placeholder="@lang('product.quantity_order')" value="{{$storage->package}}" min="{{$storage->package}}" step="{{$storage->package}}" max="{{$storage->amount}}"/>
									</td>
									<td>
                                        @if($storage->amount > 0)
										<a href="#modal-order" class="btn btn-sm btn-primary" data-toggle="modal" data-product="{{$product->id}}" data-storage="{{$storage->storage_id}}" data-storage_min="{{$storage->package}}" data-storage_max="{{$storage->amount}}" ><i class="fas fa-cart-plus"></i></a>
									    @else
                                            <a href="#modal-get_price" class="btn btn-sm btn-primary btn-get-price" data-toggle="modal" data-product_id="{{$product->id}}" ><i class="fas fa-question-circle"></i></a>
                                        @endif
                                    </td>
								</tr>
								@empty
								<tr>
									<th colspan="6" class="text-center">@lang('product.storage_empty')</th>
								</tr>
								@endforelse
							</table>
                        </div>
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
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
	<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function () {
				$('#modal-wishlist').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					modal.find('.product_id').val(button.data('product'));
				})

				$('#modal-get_price').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					modal.find('.product_id').val(button.data('product_id'));
				})

				$('#modal-order').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					var quntity_el = button.parent().prev().find('input[name="quantity"');
					modal.find('.product_id').val(button.data('product'));
					modal.find('.storage_id').val(button.data('storage'));

					var modalQuantity = modal.find('input[name="quantity"');
					var quantity = +quntity_el.val();
					if((quantity % quntity_el.attr('step')) != 0){
						if(quantity > quntity_el.attr('max')){
							quntity_el.val(quntity_el.attr('max'));
						}else if(quantity < quntity_el.attr('min')){
							quntity_el.val(quntity_el.attr('min'));
						}else{
							quantity = quantity - quantity % (+quntity_el.attr('step')) + (+quntity_el.attr('step'));
							quntity_el.val(quantity);
						}
					}
					modalQuantity.val(quntity_el.val());
					modalQuantity.attr('min',quntity_el.attr('min'));
					modalQuantity.attr('step',quntity_el.attr('step'));
					modalQuantity.attr('max',quntity_el.attr('max'));
					modalQuantity.parent().hide(0);
				})

				$('#form_add_catalog').submit(function (e) {
					e.preventDefault();

					$('#modal-wishlist').modal('hide');

					var form = $(this);
					let list_id = $('#wishlist').val();
					var route = '{{route('catalogs')}}/add-to-catalog/' + list_id;

					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					$.ajax({
						method: "GET",
						url: route,
						data: form.serialize(),
						success: function (resp) {
							if (resp == "ok") {
								$.gritter.add({
									title: '@lang('wishlist.modal_success')',
								});
							}
						},
						error: function (xhr, str) {
							console.log(xhr);
						}
					});

					return false;
				});

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
@endpush
