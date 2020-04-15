@extends('layouts.default')

@section('title', 'Form Elements')
@push('css')
    <link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
@endpush

@section('content')
    {{ Breadcrumbs::render('company') }}

    <h1 class="page-header">@lang('company.edit_page_name')</h1>

    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-xl-6">
            <!-- begin panel -->

            <div class="panel panel-primary" data-sortable-id="form-stuff-1">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('company.edit_personal_data')</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <form action="{{route('company.update_data')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('company.edit_name')</label>
                            <div class="col-md-9">
                                <input type="text" name="name" class="form-control m-b-5 @error('name') is-invalid @enderror" placeholder="@lang('company.edit_name')" value="{{$company->name}}"/>
                                @error('name')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('company.edit_prefix')</label>
                            <div class="col-md-9">
                                <input type="text" name="prefix" class="form-control m-b-5 @error('prefix') is-invalid @enderror" placeholder="@lang('company.edit_prefix')" value="{{$company->prefix}}"/>
                                <small class="f-s-12 text-grey-darker">@lang('company.edit_prefix_note')</small>
                                @error('prefix')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="input-group mb-3 @error('logo') is-invalid @enderror">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="uploadPhotoAddon">@lang('company.edit_logo')</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="logo" class="custom-file-input @error('photo') is-invalid @enderror" id="uploadPhoto" aria-describedby="uploadPhotoAddon">
                                    <label class="custom-file-label" for="uploadPhoto">@lang('company.edit_select_logo')</label>
                                </div>
                            </div>
                            @error('logo')
                            <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary m-r-5">@lang('company.edit_save')</button>
                    </form>
                </div>
            </div>

            <div class="panel panel-primary" data-sortable-id="form-stuff-2">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('company.prices_block')</h4>
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
                            <th>@lang('company.price_name')</th>
                            <th class="text-center">@lang('company.price_koef')</th>
                            <th class="text-center" width="20"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($company->type_prices as $type_price)
                            <tr>
                                <td>{{$type_price->name}}</td>
                                <td class="text-center">{{$type_price->koef}}</td>
                                <td class="text-center">
                                    <form action="{{route('company.destroy_price',[$type_price->id])}}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-block btn-danger"><i class="fas fa-times"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">@lang('company.price_empty')</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-primary" data-sortable-id="form-stuff-3">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('company.prices_form_block')</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">

                    <form action="{{route('company.add_price')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('company.price_name')</label>
                            <div class="col-md-9">
                                <input type="text" name="price_name" class="form-control m-b-5 @error('price_name') is-invalid @enderror" placeholder="@lang('company.price_name')" value="{{old('price_name')}}" />
                                @error('price_name')
                                <span class="invalid-feedback " role="alert">
                                 <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('company.price_koef')</label>
                            <div class="col-md-9">
                                <input type="text" name="price_koef" class="form-control m-b-5 @error('price_koef') is-invalid @enderror" placeholder="@lang('company.price_koef')" value="{{old('price_koef')}}"/>
                                @error('price_koef')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">@lang('company.price_add')</button>
                    </form>
                </div>
            </div>


            <!-- end panel -->
        </div>
        <!-- end col-6 -->
        <!-- begin col-6 -->
        <div class="col-xl-6">
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
        <!-- end col-6 -->
    </div>
    <!-- end row -->

    @endsection

@push('scripts')
    <script src="/assets/plugins/highlight.js/highlight.min.js"></script>
    <script src="/assets/js/demo/render.highlight.js"></script>
    <script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
    <script>
		$('#datepicker-default').datepicker({
			todayHighlight: true
		});

		document.querySelector('.custom-file-input').addEventListener('change',function(e){
			var fileName = document.getElementById("uploadPhoto").files[0].name;
			var nextSibling = e.target.nextElementSibling
			nextSibling.innerText = fileName
		})

        @if (session('status'))
		$.gritter.add({
			title: '{{ session('status') }}',
		});

        @endif

    </script>
@endpush