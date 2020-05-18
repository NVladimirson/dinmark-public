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
					<h4 class="panel-title">@lang('product.search_tab_name')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
					@forelse($formatted_data as $formatted_datum)
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