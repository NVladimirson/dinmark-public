@extends('layouts.default')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
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
									<td>{{ $userPrice }}</td>
								</tr>
								<tr>
									<th>@lang('product.show_your_price')</th>
									<td>{{ $price }}</td>
								</tr>
								<tr>
									<th>@lang('product.show_price_porog_1')</th>
									<td>{{ $product->limit_1 }}</td>
								</tr>
								<tr>
									<th>@lang('product.show_price_porog_2')</th>
									<td>{{ $product->limit_2  }}</td>
								</tr>
								<tr>
									<th>@lang('product.show_weight')</th>
									<td>{{ $product->weight  }} @lang('product.weight_kg')</td>
								</tr>
							</table>
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
							<table class="table table-striped">
								<tr>
									<th>@lang('product.storage_name')</th>
									<th>@lang('product.storage_amount')</th>
									<th>@lang('product.storage_package')</th>
									<th>@lang('product.storage_limit_1')</th>
									<th>@lang('product.storage_limit_2')</th>
									<th>@lang('product.storage_term')</th>
								</tr>
								@forelse($product->storages as $storage)
								<tr>
									<td>@lang('product.storage_name') {{ $storage->storage->id }}</td>
									<td>{{ $storage->amount }}</td>
									<td>{{ $storage->package }}</td>
									<td>{{ $storage->limit_1 }}</td>
									<td>{{ $storage->limit_2 }}</td>
									<td>{{ $storage->storage->term }}</td>
								</tr>
								@empty
								<tr>
									<th colspan="6" class="text-center">@lang('product.storage_empty')</th>
								</tr>
								@endforelse
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
@endsection

@push('scripts')

@endpush