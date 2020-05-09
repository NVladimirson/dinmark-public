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
	{{ Breadcrumbs::render('client.create') }}

	<h1 class="page-header">@lang('client.page_create')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-8">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('client.create_tab_name')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					@if(Session::has('error'))
						<div class="alert alert-danger m-b-15">
							{{Session::get('error')}}
						</div>
					@endif

					<form action="{{route('clients.store')}}" enctype="multipart/form-data" method="post">
						@csrf
					<div class="row m-b-15">
						<div class="col-md-12">
							<label for="name">@lang('client.table_header_name')</label>
							<input class="form-control m-b-5" type="text" id="name" name="name" required value="{{old('name')}}">
						</div>
						<div class="col-md-12">
							<label for="phone">@lang('client.table_header_phone')</label>
							<input class="form-control m-b-5" type="tel" id="phone" name="phone" required value="{{old('phone')}}">
						</div>
						<div class="col-md-12">
							<label for="email">@lang('client.table_header_email')</label>
							<input class="form-control m-b-5" type="email" id="email" name="email" required value="{{old('email')}}">
						</div>
						<div class="col-md-12">
							<label for="company">@lang('client.table_header_company')</label>
							<input class="form-control m-b-5" type="text" id="company" name="company" value="{{old('company')}}">
						</div>
						<div class="col-md-12">
							<label for="edrpo">@lang('client.table_header_edrpo')</label>
							<input class="form-control m-b-5" type="text" id="edrpo" name="edrpo" value="{{old('edrpo')}}">
						</div>
						<div class="col-md-12">
							<label for="address">@lang('client.table_header_address')</label>
							<textarea class="form-control m-b-5" name="address" id="address" cols="30" rows="10" required>{{old('address')}}</textarea>
						</div>
					</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="pull-right">
									<button type="submit" class="btn btn-sm btn-primary m-b-5 m-r-5" data-toggle="modal">@lang('client.btn_store')</button>
								</div>
							</div>
						</div>

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
	<script src="/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="/assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
	<script src="/assets/plugins/pdfmake/build/vfs_fonts.js"></script>
	<script src="/assets/plugins/jszip/dist/jszip.min.js"></script>
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
	<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>

	<script>
		(function ($) {
			"use strict";



		})(jQuery);
	</script>
@endpush