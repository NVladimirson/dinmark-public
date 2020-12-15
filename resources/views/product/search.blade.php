@extends('layouts.default')

@push('css')

@endpush

@section('content')
	{{ Breadcrumbs::render('product.search') }}
	<h1 class="page-header">@lang('product.search_page_name')</h1>
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
					@forelse($extendedSearchResult as $formatted_datum)
					@php
					$product = \App\Models\Product\Product::find($formatted_datum->product_id);
					if($product == null){
						continue;
					}
					@endphp
						<div class="col-3 m-b-15">
							<div class="row">
							<div class="col-12 m-b-15">
								<img src="{{\App\Services\Product\Product::getImagePath($product)}}" width="100%">
							</div>
            	<div class="col-12 m-b-15">
								<div class="row">
									<div class="col-4">
										<b>@lang('product.extended_search.name')</b>
									</div>
									<div class="col-8">
										<a href="{{route('products.show',[$formatted_datum->id])}}">
											{{\App\Services\Product\Product::getName($product)}}</a>
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<b>@lang('product.extended_search.standart')</b>
									</div>
									<div class="col-8">
										{{\App\Services\Miscellenous\ExtendedSearchService::translateProductFilter($formatted_datum->standart)}}
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<b>@lang('product.extended_search.diametr')</b>
									</div>
									<div class="col-8">
											{{$formatted_datum->diametr}}
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<b>@lang('product.extended_search.dovzhyna')</b>
									</div>
									<div class="col-8">
										{{$formatted_datum->dovzhyna}}
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<b>@lang('product.extended_search.material')</b>
									</div>
									<div class="col-8">
										{{\App\Services\Miscellenous\ExtendedSearchService::translateProductFilter($formatted_datum->material)}}
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<b>@lang('product.extended_search.klas_micnosti')</b>
									</div>
									<div class="col-8">
										{{$formatted_datum->klas_micnosti}}
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<b>@lang('product.extended_search.pokryttja')</b>
									</div>
									<div class="col-8">
										{{\App\Services\Miscellenous\ExtendedSearchService::translateProductFilter($formatted_datum->pokryttja)}}
									</div>
								</div>
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
					@forelse($product_search as $formatted_datum)
						<div class="col-3 m-b-15">
							<div class="row">
							<div class="col-12 m-b-15">
								<img src="{{\App\Services\Product\Product::getImagePath($formatted_datum)}}" width="100%">
							</div>
            	<div class="col-12 m-b-15">
								<div class="row">
									<div class="col-4">
										<b>@lang('product.global_search.name')</b>
									</div>
									<div class="col-8">
										<a href="{{route('products.show',[$formatted_datum->id])}}">
											{{\App\Services\Product\Product::getName($formatted_datum)}}</a>
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<b>Аналог</b>
									</div>
									<div class="col-8">
										<a href="{{route('products.show',[$formatted_datum->id])}}">
											{{\App\Services\Product\Product::getProductOptionBy($formatted_datum->id,23)}}</a>
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<b>Народна Назва</b>
									</div>
									<div class="col-8">
										<a href="{{route('products.show',[$formatted_datum->id])}}">
											{{\App\Services\Product\Product::getProductOptionBy($formatted_datum->id,30)}}</a>
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
					@forelse($order_search as $formatted_datum)
					<div class="col-3 m-b-15">
						<div class="row">
						<div class="col-12 m-b-15">
							<img src="{{\App\Services\Product\Product::getImagePath($formatted_datum)}}" width="100%">
						</div>
						<div class="col-12 m-b-15">
							<div class="row">
								<div class="col-4">
									<b>@lang('product.global_search.name')</b>
								</div>
								<div class="col-8">
									<a href="{{route('products.show',[$formatted_datum->id])}}">
										{{\App\Services\Product\Product::getName($formatted_datum)}}</a>
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
					@forelse($implementation_search as $formatted_datum)
					<div class="col-3 m-b-15">
						<div class="row">
						<div class="col-12 m-b-15">
							<img src="{{\App\Services\Product\Product::getImagePath($formatted_datum)}}" width="100%">
						</div>
						<div class="col-12 m-b-15">
							<div class="row">
								<div class="col-4">
									<b>@lang('product.global_search.name')</b>
								</div>
								<div class="col-8">
									<a href="{{route('products.show',[$formatted_datum->id])}}">
										{{\App\Services\Product\Product::getName($formatted_datum)}}</a>
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
					@forelse($reclamation_search as $formatted_datum)
					<div class="col-3 m-b-15">
						<div class="row">
						<div class="col-12 m-b-15">
							<img src="{{\App\Services\Product\Product::getImagePath($formatted_datum)}}" width="100%">
						</div>
						<div class="col-12 m-b-15">
							<div class="row">
								<div class="col-4">
									<b>@lang('product.global_search.name')</b>
								</div>
								<div class="col-8">
									<a href="{{route('products.show',[$formatted_datum->id])}}">
										{{\App\Services\Product\Product::getName($formatted_datum)}}</a>
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
