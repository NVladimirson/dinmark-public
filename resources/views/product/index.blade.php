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
						<div class="col-md-6 text-center">
							<img src="{{$imagePath}}" alt="{{$productName}}">
						</div>
						<div class="col-md-6">
							<table class="table table-striped">
								<tr>
									<th>@lang('product.show_article')</th>
									<td>{{ $product->article_show }}</td>
								</tr>
								<tr>
									<th>@lang('product.show_price')</th>
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
@endsection

@push('scripts')

@endpush