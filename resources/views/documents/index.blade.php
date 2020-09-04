@extends('layouts.default')

@push('css')
	<link href="/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
	<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
@endpush

@section('content')
	{{ Breadcrumbs::render('documents') }}

	<h1 class="page-header">@lang('documents.page_name')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
                    <ul id="order_tab" class="nav nav-tabs nav-tabs-panel panel-title">
                        <li class="nav-item">
                            <a href="#document_company" data-toggle="tab" class="nav-link active">
                                <span>@lang('documents.tab_name_document_company')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#document_operations" data-toggle="tab" class="nav-link">
                                <span>@lang('documents.tab_name_document_operations')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#file_imports" data-toggle="tab" class="nav-link">
                                <span>@lang('documents.tab_name_file_imports')</span>
                            </a>
                        </li>
                    </ul>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade active show" id="document_company">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-primary" data-sortable-id="form-stuff-5">
                                <!-- begin panel-heading -->
                                <div class="panel-heading">
                                    <h4 class="panel-title">@lang('company.documents_load_block')</h4>
                                    <div class="panel-heading-btn">
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                    </div>
                                </div>
                                <!-- end panel-heading -->
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <form action="{{route('company.add_document')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-3">@lang('company.document_name')</label>
                                            <div class="col-md-9">
                                                <input type="text" name="document_name" class="form-control m-b-5 @error('document_name') is-invalid @enderror" placeholder="@lang('company.document_name')" value="{{old('document_name')}}"/>
                                                @error('document_name')
                                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-3">@lang('company.document_type')</label>
                                            <div class="col-md-9">
                                                <select name="document_type" class="form-control selectpicker" data-size="10" data-live-search="false" data-style="btn-white">
                                                    <option value="certificates" selected>@lang('company.document_certificates')</option>
                                                    <option value="contracts">@lang('company.document_contracts')</option>
                                                    <option value="founding_documents">@lang('company.document_founding_documents')</option>
                                                    <option value="different">@lang('company.document_different')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-3">@lang('company.document_info')</label>
                                            <div class="col-md-9">
                                                <textarea name="document_info" class="form-control m-b-5 @error('document_info') is-invalid @enderror" placeholder="@lang('company.document_info')" cols="30" rows="5">{{old('document_info')}}</textarea>
                                                @error('document_info')
                                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="input-group mb-3 @error('document') is-invalid @enderror">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="uploadDocumentAddon">@lang('company.document')</span>
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" name="document" class="custom-file-input @error('document') is-invalid @enderror" id="uploadDocument" aria-describedby="uploadDocumentAddon">
                                                    <label class="custom-file-label" for="uploadDocument">@lang('company.document_select')</label>
                                                </div>
                                            </div>
                                            @error('document')
                                            <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-sm btn-primary m-r-5">@lang('company.document_add')</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-primary" data-sortable-id="form-stuff-4">
                                <!-- begin panel-heading -->
                                <div class="panel-heading">
                                    <h4 class="panel-title">@lang('company.documents_block')</h4>
                                    <div class="panel-heading-btn">
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                    </div>
                                </div>
                                <!-- end panel-heading -->
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>@lang('company.document_name')</th>
                                            <th>@lang('company.document_type')</th>
                                            <th>@lang('company.document_info')</th>
                                            <th width="100"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($company->documents as $document)
                                            <tr>
                                                <td>{{$document->name}}</td>
                                                <td>@lang('company.document_'.$document->folder)</td>
                                                <td>{{$document->info}}</td>
                                                <td>
                                                    <a href="{{env('DINMARK_URL')}}documents/{{$company->id}}/{{$document->folder}}/{{$document->document}}" class="btn btn-sm btn-primary m-r-10" target="_blank"><i class="fas fa-download"></i></a>
                                                    @if($document->manager_add == auth()->user()->id)
                                                        <form action="{{route('company.destroy_document',[$document->id])}}" method="post" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">@lang('company.document_empty')</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel panel-primary" data-sortable-id="form-stuff-6">
                                <!-- begin panel-heading -->
                                <div class="panel-heading">
                                    <h4 class="panel-title">@lang('company.documents_request_block')</h4>
                                    <div class="panel-heading-btn">
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                    </div>
                                </div>
                                <!-- end panel-heading -->
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <form action="{{route('company.request_document')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-3">@lang('company.document_request_question')</label>
                                            <div class="col-md-9">
                                                <textarea name="request_text" class="form-control m-b-5 @error('request_text') is-invalid @enderror" placeholder="@lang('company.document_request_question')" cols="30" rows="5">{{old('request_text')}}</textarea>
                                                <small class="f-s-12 text-grey-darker">@lang('company.document_request_note')</small>
                                                @error('request_text')
                                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-sm btn-primary m-r-5">@lang('company.document_send')</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade " id="document_operations">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h4 class="panel-title">@lang('documents.tab_name_bill')</h4>
                                    <div class="panel-heading-btn">
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                    </div>
                                </div>
                                <!-- end panel-heading -->
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <form action="" enctype="multipart/form-data" method="get">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12  m-b-15">
                                                <label for="order_id">@lang('documents.select_order')</label>
                                                <select class="form-control m-b-5" id="order_id">
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" id="bill_submit" class="btn btn-sm btn-primary m-r-5" disabled="disabled">@lang('documents.download_btn')</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h4 class="panel-title">@lang('documents.tab_name_implementation')</h4>
                                    <div class="panel-heading-btn">
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                    </div>
                                </div>
                                <!-- end panel-heading -->
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <form action="" enctype="multipart/form-data" method="get">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12  m-b-15">
                                                <label for="implementation_id">@lang('reclamation.select_implementation')</label>
                                                <select class="form-control m-b-5" id="implementation_id">
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" id="implementation_submit" class="btn btn-sm btn-primary m-r-5" disabled="disabled">@lang('documents.download_btn')</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade " id="file_imports">
                </div>
            </div>
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
	<script src="/assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons/js/buttons.flash.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons/js/buttons.html5.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons/js/buttons.print.min.js"></script>
	<script src="/assets/plugins/pdfmake/build/pdfmake.min.js"></script>
	<script src="/assets/plugins/pdfmake/build/vfs_fonts.js"></script>
	<script src="/assets/plugins/jszip/dist/jszip.min.js"></script>
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
	<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
	{{--<script src="/assets/js/demo/table-manage-buttons.demo.js"></script>--}}

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {
				$('#implementation_id').select2({
					placeholder: "@lang('reclamation.select_implementation')",
					minimumInputLength: 0,
					ajax: {
						url: function () {
							return '{{route('implementations.find')}}'
						},
						dataType: 'json',
						data: function (params) {
							return {
								name: params.term
							};
						},
						processResults: function (data) {
							return {
								results: data
							};
						},
						cache: true
					},
				});
				$('#implementation_id').change(function (e) {
					e.preventDefault();
                    $('#implementation_submit').removeAttr('disabled');
                    var form = $(this).parents('form');
                    $(form).attr('action','{{route('implementations')}}/pdf/'+$(this).val());
				});

				$('#order_id').select2({
					placeholder: "@lang('documents.select_order')",
					minimumInputLength: 0,
					ajax: {
						url: function () {
							return '{{route('orders.find')}}'
						},
						dataType: 'json',
						data: function (params) {
							return {
								name: params.term
							};
						},
						processResults: function (data) {
							return {
								results: data
							};
						},
						cache: true
					},
				});
				$('#order_id').change(function (e) {
					e.preventDefault();
                    $('#bill_submit').removeAttr('disabled');
                    var form = $(this).parents('form');
                    $(form).attr('action','{{route('orders')}}/'+$(this).val()+'/bill');
				});
			});
		})(jQuery);
	</script>
@endpush
