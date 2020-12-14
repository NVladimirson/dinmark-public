@extends('layouts.default')

@push('css')

@endpush

@section('content')
	{{ Breadcrumbs::render('product.search') }}
	<h1 class="page-header">@lang('product.search_page_name')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">product_search</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
					@forelse($product_search as $formatted_datum)
						<div class="col-12 m-b-15">
							<a href="{{route('products.show',[$formatted_datum['id']])}}">{{$formatted_datum['text']}}</a>
						</div>
					@empty
						<div class="alert alert-light fade show">@lang('product.empty')</div>
					@endforelse
				</div>
				</div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
        </div>
        <div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">oder_search</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
					@forelse($implementation_search as $formatted_datum)
						<div class="col-12 m-b-15">
							<a href="{{route('products.show',[$formatted_datum['id']])}}">{{$formatted_datum['text']}}</a>
						</div>
					@empty
						<div class="alert alert-light fade show">@lang('product.empty')</div>
					@endforelse
				</div>
				</div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
        </div>
        <div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">implementation_search </h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
					@forelse($implementation_search as $formatted_datum)
						<div class="col-12 m-b-15">
							<a href="{{route('products.show',[$formatted_datum['id']])}}">{{$formatted_datum['text']}}</a>
						</div>
					@empty
						<div class="alert alert-light fade show">@lang('product.empty')</div>
					@endforelse
				</div>
				</div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
        </div>
        <div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">reclamation_search</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
					@forelse($reclamation_search as $formatted_datum)
						<div class="col-12 m-b-15">
							<a href="{{route('products.show',[$formatted_datum['id']])}}">{{$formatted_datum['text']}}</a>
						</div>
					@empty
						<div class="alert alert-light fade show">@lang('product.empty')</div>
					@endforelse
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

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {
			});
		})(jQuery);
	</script>
@endpush
