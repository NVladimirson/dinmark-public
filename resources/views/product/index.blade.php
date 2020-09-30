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
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
						<div class="col-md-5 text-center">
							<img src="{{$imagePath}}" alt="{{$productName}}" width="100%" class="m-b-15">
                            <div class="row m-b-10">
                                @foreach($productPhotos as $photo)
                                    <div class="col-sm-4">
                                        <img src="{{$photo}}" alt="{{$productName}}" width="100%" class="m-b-5">
                                    </div>
                                @endforeach
                            </div>
                            @if($productPDF)
                                <a href="{{$productPDF}}" target="_blank" class="btn btn-white btn-block btn-lg">
                                    @lang('product.btn_pdf')
                                </a>
                            @endif
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
									<td>{{ $limit2 }}</td>
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
                    <ul id="product_tab" class="nav nav-tabs nav-tabs-panel panel-title">
                        <li class="nav-item">
                            <a href="#storage-tab" data-toggle="tab" class="nav-link active">
                                <span>@lang('product.header_storage')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#detail-tab" data-toggle="tab" class="nav-link">
                                <span>@lang('product.header_detail')</span>
                            </a>
                        </li>
                    </ul>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="storage-tab">
					        <div class="row">
                                <div class="table-scroll-container">
							<table class="table table-striped">
								<tr>
									<th>@lang('product.storage_name')</th>
									<th>@lang('product.storage_amount')</th>
									<th>@lang('product.storage_package')</th>
									<th>@lang('product.storage_price')</th>
									<th>@lang('product.storage_limit_1')</th>
									<th>@lang('product.storage_limit_2')</th>
									<th>@lang('product.storage_term')</th>
									<th>@lang('product.storage_quantity')</th>
									<th width="150">@lang('product.storage_total')</th>
									<th></th>
								</tr>
								@php function getAmount($amount){
									switch ($amount){
										case ($amount>10000):
											echo '>10000';
											break;
										case ($amount>5000):
											echo '>5000';
											break;
										case ($amount>1500):
											echo '>1500';
											break;
										case ($amount>500):
											echo '>500';
											break;
										case ($amount>150):
											echo '>150';
											break;
										case ($amount>50):
										  echo '>50';
											break;
										case ($amount>10):
											echo '>10';
											break;
										case ($amount<10):
											echo '<10';
									}
								} @endphp
								@forelse($product->storages as $storage)
								<tr>
									<td>@lang('product.storage_name') {{ $storage->storage->term }} @lang('product.storage_term_measure_shortly')</td>
									<td>@php getAmount($storage->amount) @endphp</td>
									<td>{{ $storage->package }}</td>
									<td>{{ $storage_prices[$storage->id] }}</td>
									<td>{{ $storage->limit_1 }}</td>
									<td>{{ $storage->limit_2 }}</td>
									<td>{{ $storage->storage->term }}</td>
									<td>
                                        @if($storage->amount > 0)
										<input type="number" name="quantity" class="form-control m-b-5" placeholder="@lang('product.quantity_order')"
                                               value="{{$storage->package}}"
                                               min="{{$storage->package}}"
                                               step="{{$storage->package}}"
                                               data-max="{{$storage->amount-($storage->amount%$storage->package)}}"
                                               data-price="{{$storage_raw_prices[$storage->id]}}"
                                               data-limit_1="{{$storage->limit_1}}"
                                               data-limit_2="{{$storage->limit_2}}"
                                        />
                                        @endif
									</td>
                                    <td>
                                        @if($storage->amount > 0)
                                        <div class="p-r-5 p-l-5 m-b-5">
                                            <div class="product-total product-total-price">
                                                0
                                            </div>
                                        </div>
                                        <div class="d-inline-block float-left w-40 p-5">
                                            <div class="product-total product-total-procent">
                                                0%
                                            </div>
                                        </div>
                                        <div class="d-inline-block float-right w-60 p-5">
                                            <div class="product-total product-total-discount">
                                                0
                                            </div>
                                        </div>
                                        @endif
                                    </td>
									<td>
                                        @if($storage->amount > 0)
										<a href="#modal-order" class="btn btn-sm btn-primary" data-toggle="modal" data-product="{{$product->id}}" data-product_name="{{$productName}}" data-storage="{{$storage->storage_id}}" data-storage_min="{{$storage->package}}" data-storage_max="{{$storage->amount-($storage->amount%$storage->package)}}"><i class="fas fa-cart-plus"></i></a>
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
                        <div class="tab-pane fade show" id="detail-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! str_replace('<p>&nbsp;</p>','',html_entity_decode($productText,ENT_QUOTES)) !!}
                                    @if($productVideo)
                                    <p>
                                        <iframe width="737" height="415" src="https://www.youtube.com/embed/{{$productVideo}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($productPDF)
                                        <a href="{{$productPDF}}" target="_blank" class="preview">
                                            <img src="https://dinmark.com.ua/images/file_preview.jpg" width="100%">
                                        </a>
                                    @endif
                                </div>
                            </div>
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
					modal.find('.product-name').text(button.data('product_name'));
					modal.find('.product_id').val(button.data('product'));
					modal.find('.storage_id').val(button.data('storage'));

					var modalQuantity = modal.find('input[name="quantity"');
					var quantity = +quntity_el.val();
					var request = 0;

					if(quantity > quntity_el.data('max')){
						quntity_el.val(quntity_el.data('max'));
						request = quantity - (+quntity_el.data('max'));
						quantity = +quntity_el.data('max');
					}else if(quantity < quntity_el.attr('min')){
						quntity_el.val(quntity_el.attr('min'));
						quantity = +quntity_el.data('min');
					}

					if((quantity % quntity_el.attr('step')) != 0){
                        quantity = quantity - quantity % (+quntity_el.attr('step')) + (+quntity_el.attr('step'));
                        quntity_el.val(quantity);
					}

					modalQuantity.val(quntity_el.val());
					modalQuantity.attr('min',quntity_el.attr('min'));
					modalQuantity.attr('step',quntity_el.attr('step'));
					modalQuantity.data('max',quntity_el.data('max'));
					modalQuantity.parent().hide(0);

					var quantity_request = modal.find('input[name="quantity_request"]');
					quantity_request.val(request);
					quantity_request.attr('min',0);
					quantity_request.attr('step',button.data('storage_min'));
					quantity_request.attr('max',button.data('storage_max'));

					$('.order-storage-amount').text(button.data('storage_max'));
                    if(request > 0){
                    	$('.storage-limit-info').show();
                    	$('.storage-limit-request').show();
                    }else{
						$('.storage-limit-info').hide();
						$('.storage-limit-request').hide();
                    }
					$('input[name="quantity_request"]').change();
				})

				$('input[name="quantity_request"]').change(function (e) {
					e.preventDefault();
					if($(this).val() > 0){
						$('.btn-add-order').text($('.btn-add-order').data('btn_order_request'));
					}else{
						$('.btn-add-order').text($('.btn-add-order').data('btn_order'));
					}
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
				});

				function numberStringFormat(nStr)
				{
					nStr = nStr.toFixed(2);
					nStr += '';
					var x = nStr.split('.');
					var x1 = x[0];
					var x2 = x.length > 1 ? '.' + x[1] : '';
					var rgx = /(\d+)(\d{3})/;
					while (rgx.test(x1)) {
						x1 = x1.replace(rgx, '$1' + ' ' + '$2');
					}
					return x1 + x2;
				}

				$('input[name="quantity"]').change(calcTotal);
				$('input[name="quantity"]').each(calcTotal);

				function calcTotal(){

					var quantity = +$(this).val();
					var price = +$(this).data('price');
					var limit_1 = +$(this).data('limit_1');
					var limit_2 = +$(this).data('limit_2');

					var total_price_el = $(this).parent().parent().find('.product-total-price');
					var total_els = $(this).parent().parent().find('.product-total');
					var total_price_procent = $(this).parent().parent().find('.product-total-procent');
					var total_price_discount = $(this).parent().parent().find('.product-total-discount');

					total_els.removeClass('product-total-limit_1');
					total_els.removeClass('product-total-limit_2');

					var total = price/100 * quantity;
					var discount = 0;
					var procent = '0%';

					if(quantity >= limit_2 && limit_2 > 0){
						total_els.addClass('product-total-limit_2');
						discount = total - total * 0.93;
						total = total * 0.93;
						procent = '7%';
					}else if(quantity >= limit_1 && limit_2 > 0){
						total_els.addClass('product-total-limit_1');
						discount = total - total * 0.97;
						total = total * 0.97;
						procent = '3%';
					}

					total_price_el.text(numberStringFormat(total));
					total_price_procent.text(procent);
					total_price_discount.text(numberStringFormat(discount));
                }
			});
		})(jQuery);
	</script>
@endpush
