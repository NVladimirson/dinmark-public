@extends('layouts.default')

@push('css')

@endpush

@section('content')
	{{ Breadcrumbs::render('product.search') }}
	<h1 class="page-header">@lang('product.search_page_name')@if(isset($search)) : {{$search}}@endif @if(isset($extended_search_display)) : {{$extended_search_display}}@endif</h1>
	<!-- begin row -->

    @if($extendedSearch)
    <div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12" id="product">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('product.extended_search.result_header')</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
					@forelse($extendedSearchResult as $product)

						<div class="col-xl-3 col-lg-4 col-md-6 m-b-15">
							<div class="row">
							<div class="col-12 m-b-15">
								<img src="{{\App\Services\Product\Product::getImagePath($product)}}" style="max-height: 250px;" width="100%">
							</div>
            	<div class="col-12 m-b-16 ml-3">
								<div class="row">
									<div class="col-4">
										<b>@lang('product.extended_search.name')</b>
									</div>
									<div class="col-6 ml-3">
										<a href="{{route('products.show',[$product->id])}}">
											{{$product->name}}</a>
									</div>
								</div>
								@foreach($product->options as $option => $data)
								<div class="row">
									<div class="col-4">
										<b>{{$option}}</b>
									</div>
									<div class="col-6 ml-3">
										{{$data}}
									</div>
								</div>
								@endforeach

							</div>
							</div>
						</div>
					@empty
						<div class="alert alert-light fade show">@lang('product.empty')</div>
					@endforelse
                </div>

                </div>
                {{ $extendedSearchResult->links() }}
			</div>
	@endif
   @if($globalSearch)
    <div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12" id="product">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('product.global_search.product_search.header')</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
						@if($product_search)
					@forelse($product_search as $product)
						<div class="col-3 m-b-15">
							<div class="row">
							<div class="col-12 m-b-15">
								<img src="{{\App\Services\Product\Product::getImagePath($product)}}" width="100%">
							</div>
            	<div class="col-12 m-b-15">
								<div class="row">
									<div class="col-4">
										<b>@lang('product.global_search.name')</b>
									</div>
									<div class="col-8">
										<a href="{{route('products.show',[$product->id])}}">
											{{$product->name}}</a>
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<b>Артикул</b>
									</div>
									<div class="col-8">
										{{$product->article}}
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<b>Аналог</b>
									</div>
									<div class="col-8">
										{{$product->analogue}}
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<b>Народна Назва</b>
									</div>
									<div class="col-8">
										{{$product->narodna}}
									</div>
								</div>
							</div>
							</div>
						</div>
					@empty
						<div class="alert alert-light fade show">@lang('product.empty')</div>
					@endforelse
				{{  $product_search->links() }}
					@endif
                </div>

                </div>
			</div>
			<!-- end panel -->
        </div>
        <div class="col-xl-12" id="order">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('product.global_search.order_search.header')</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
					@forelse($order_search as $order_product)
					<div class="col-3 m-b-15">
						<div class="row">
						<div class="col-12 m-b-15">
							<img src="{{\App\Services\Product\Product::getImagePath($product)}}" width="100%">
						</div>
						<div class="col-12 m-b-15">
							<div class="row">
								<div class="col-4">
									<b>@lang('product.global_search.name')</b>
								</div>
								<div class="col-8">
									<a href="{{route('products.show',[$product->id])}}">
										{{$order_product->name}}</a>
								</div>
							</div>
							<div class="row">
								<div class="col-4">
									<b>@lang('product.global_search.orders')</b>
								</div>
								<div class="col-8">
									@php $product_orders = \App\Services\Product\Product::getOrder($product);@endphp
									@if($product_orders)
									<a href="{{route('orders.show',[array_key_first($product_orders)])}}">
										{{$product_orders[array_key_first($product_orders)]}}</a>
										@else
										-
									@endif
								</div>
							</div>
						</div>

						</div>
					</div>
					@empty
						<div class="alert alert-light fade show">@lang('product.empty')</div>

                    @endforelse
									{{$order_search->links()}}
                </div>
                {{-- {{ $order_search>links() }} --}}
                </div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
        </div>
        <div class="col-xl-12" id="implementation">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('product.global_search.implementation_search.header')</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
					@forelse($implementation_search as $implementation_product)
					<div class="col-3 m-b-15">
						<div class="row">
						<div class="col-12 m-b-15">
							<img src="{{\App\Services\Product\Product::getImagePath($product)}}" width="100%">
						</div>
						<div class="col-12 m-b-15">
							<div class="row">
								<div class="col-4">
									<b>@lang('product.global_search.name')</b>
								</div>
								<div class="col-8">
									<a href="{{route('products.show',[$product->id])}}">
										{{$implementation_product->name}}</a>
								</div>
							</div>
							<div class="row">
								<div class="col-4">
									<b>@lang('product.global_search.implementations')</b>
								</div>
								<div class="col-8">
									@php $implementation_orders = \App\Services\Product\Product::getImplementations($product);@endphp
									@if($implementation_orders)
									<a href="{{route('orders.show',[array_key_first($implementation_orders)])}}">
											{{$implementation_orders[array_key_first($implementation_orders)]}}<</a>
										@else
										-
									@endif
								</div>
							</div>
						</div>
						</div>
					</div>
					@empty
						<div class="alert alert-light fade show">@lang('product.empty')</div>

					@endforelse
					{{$implementation_search->links()}}
                </div>

                </div>
                <!-- end panel-body -->

            </div>

			<!-- end panel -->
        </div>
        <div class="col-xl-12" id="reclamation">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('product.global_search.reclamation_search.header')</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
					@forelse($reclamation_search as $reclamation_product)
					<div class="col-3 m-b-15">
						<div class="row">
						<div class="col-12 m-b-15">
							<img src="{{\App\Services\Product\Product::getImagePath($product)}}" width="100%">
						</div>
						<div class="col-12 m-b-15">
							<div class="row">
								<div class="col-4">
									<b>@lang('product.global_search.name')</b>
								</div>
								<div class="col-8">
									<a href="{{route('products.show',[$product->id])}}">
										{{$reclamation_product->name}}</a>
								</div>
							</div>
							<div class="row">
								<div class="col-4">
									<b>@lang('product.global_search.reclamations')</b>
								</div>
								<div class="col-8">
									<a href="{{route('products.show',[$product->id])}}">
										{{$reclamation_product->name}}</a>
								</div>
							</div>
						</div>
						</div>
					</div>
					@empty
						<div class="alert alert-light fade show">@lang('product.empty')</div>

					@endforelse
						{{$reclamation_search->links()}}
                </div>

                </div>

				<!-- end panel-body -->
            </div>
			<!-- end panel -->
        </div>

		<!-- end col-10 -->
    </div>
    @endif
	<!-- end row -->
@endsection

@push('scripts')

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {
			});
		})(jQuery);
	</script>
@endpush
