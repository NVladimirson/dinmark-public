@extends('layouts.default')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
	{{ Breadcrumbs::render('chat.create') }}
	<h1 class="page-header">@lang('chat.create_page_name')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('chat.tab_create')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<form action="{{route('chat.store')}}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2">@lang('chat.subject')</label>
							<div class="col-md-10">
								<input type="text" name="subject" class="form-control m-b-5 @error('subject') is-invalid @enderror" placeholder="@lang('chat.subject')" value="{{old('subject')}}"/>
								@error('subject')
								<span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
								@enderror
							</div>
						</div>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2">@lang('chat.message')</label>
							<div class="col-md-10">
								<textarea name="message" rows="10" class="form-control m-b-5 @error('subject') is-invalid @enderror" placeholder="@lang('chat.message')">{{old('message')}}</textarea>

								@error('message')
								<span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
								@enderror
							</div>
						</div>


						<button type="submit" class="btn btn-sm btn-primary m-r-5 ">@lang('chat.button_send')</button>
					</form>
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
	<script src="/assets/plugins/pdfmake/build/pdfmake.min.js"></script>
	<script src="/assets/plugins/pdfmake/build/vfs_fonts.js"></script>
	<script src="/assets/plugins/jszip/dist/jszip.min.js"></script>
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
	{{--<script src="/assets/js/demo/table-manage-buttons.demo.js"></script>--}}

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {
				window.table = $('#data-table-buttons').DataTable( {
					"language": {
						"url": "@lang('table.localization_link')",
						searchPlaceholder: "Поиск"
					},
					//"scrollX": true,
					"pageLength": 25,
					"autoWidth": true,
					"processing": true,
					"serverSide": true,
					"ajax": "{!! route('products.all_ajax') !!}",
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
							data: 'name',
						},
						{
							data: 'article_show',
						},
						{
							data: 'user_price',
						},
						{
							data: 'limit_1',
						},
						{
							data: 'limit_2',
						},
						{
							data: 'storage_html',
						},
						{
							data: 'actions',
						},
					],
				} );
			});
		})(jQuery);
	</script>
@endpush