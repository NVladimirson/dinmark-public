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
	{{ Breadcrumbs::render('client.edit',$client) }}

	<h1 class="page-header">{{$client->name}}</h1>
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

					<form action="{{route('clients.update',[$client->id])}}" enctype="multipart/form-data" method="post">
						@csrf
					<div class="row m-b-15">
						<div class="col-md-12">
							<label for="name">@lang('client.table_header_name')</label>
							<input class="form-control m-b-5 @error('name') is-invalid @enderror" type="text" id="name" name="name" required value="{{$client->name}}">
                            @error('name')
                            <span class="invalid-feedback " role="alert">
                                         <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
						<div class="col-md-12">
							<label for="phone">@lang('client.table_header_phone')</label>
							<input class="form-control m-b-5 @error('phone') is-invalid @enderror" type="tel" id="phone" name="phone" required value="{{$client->phone}}">
                            @error('phone')
                            <span class="invalid-feedback " role="alert">
                                         <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
						<div class="col-md-12">
							<label for="email">@lang('client.table_header_email')</label>
							<input class="form-control m-b-5 @error('email') is-invalid @enderror" type="email" id="email" name="email" required value="{{$client->email}}">
                            @error('email')
                            <span class="invalid-feedback " role="alert">
                                         <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
						<div class="col-md-12">
							<label for="company">@lang('client.table_header_company')</label>
							<input class="form-control m-b-5 @error('company') is-invalid @enderror" type="text" id="company" name="company" value="{{$client->company_name}}">
                            @error('company')
                            <span class="invalid-feedback " role="alert">
                                         <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
						<div class="col-md-12">
							<label for="edrpo">@lang('client.table_header_edrpo')</label>
							<input class="form-control m-b-5 @error('edrpo') is-invalid @enderror" type="text" id="edrpo" name="edrpo" value="{{$client->company_edrpo}}">
                            @error('edrpo')
                            <span class="invalid-feedback " role="alert">
                                         <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
						<div class="col-md-12">
							<label for="address">@lang('client.table_header_address')</label>
							<textarea class="form-control m-b-5 @error('address') is-invalid @enderror" name="address" id="address" cols="30" rows="10" required>{{$client->address}}</textarea>
                            @error('address')
                            <span class="invalid-feedback " role="alert">
                                         <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
						</div>
                        <div class="col-md-12">
                            <label for="address">@lang('client.table_header_comment')</label>
                            <textarea class="form-control m-b-5 @error('comment') is-invalid @enderror" name="comment" id="comment" cols="30" rows="10" required>{{$client->comment}}</textarea>
                            @error('comment')
                            <span class="invalid-feedback " role="alert">
                                         <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
					</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="pull-right">
									<button type="submit" class="btn btn-sm btn-primary m-b-5 m-r-5" data-toggle="modal">@lang('client.btn_update')</button>
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

			@if (session('status'))
			$.gritter.add({
				title: '{{ session('status') }}',
			});
			@endif

		})(jQuery);
	</script>
@endpush
