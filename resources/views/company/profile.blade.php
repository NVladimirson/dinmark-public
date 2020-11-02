@extends('layouts.default', ['contentFullWidth' => true])

@section('title', 'Form Elements')
@push('css')
    <link href="/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="profile" style="margin-top: -1px;">
        <div class="profile-header" style="height: 107px;">
            <!-- BEGIN profile-header-cover -->
            <div class="profile-header-cover"></div>
            <!-- END profile-header-cover -->
            <!-- BEGIN profile-header-content -->
            <div class="profile-header-content">
                <!-- BEGIN profile-header-img -->
                <div class="profile-header-img">
                    @if(auth()->user()->getCompany->logo)
                        <img src="{{env('DINMARK_URL')}}images/company/{{auth()->user()->getCompany->logo}}" alt="{{auth()->user()->name}}" />
                    @else
                        <img src="{{env('DINMARK_URL')}}images/empty-avatar.png" alt="{{auth()->user()->name}}" />
                    @endif
                </div>

                <!-- END profile-header-img -->
                <!-- BEGIN profile-header-info -->
                <div class="profile-header-info">
                    <h4 class="mt-0 mb-1">{{auth()->user()->getCompany->name}}</h4>
                    <p class="mb-2">{{auth()->user()->name}}</p>
                    <p class="mb-0"><strong>@lang('sidebar.manager'):</strong> {{auth()->user()->getCompany->getManager->name}}</p>
                </div>
                <!-- END profile-header-info -->
            </div>
        </div>
    </div>

    <h1 class="page-header">@lang('company.edit_page_name')</h1>
    <div class="profile-content p-t-0">
    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-xl-6">
            <!-- begin panel -->

            <div class="panel panel-primary">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('company.edit_personal_data')</h4>
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

            <div class="panel panel-primary">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('company.prices_block')</h4>
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
            <div class="panel panel-primary">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('company.prices_form_block')</h4>
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

            <div class="panel panel-primary">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('company.edit_payment_data')</h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <form action="{{route('company.update_payment_data')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('company.edit_address')</label>
                            <div class="col-md-9">
                                <input type="text" name="address" class="form-control m-b-5 @error('address') is-invalid @enderror" placeholder="@lang('company.edit_address')" value="{{$company->address}}"/>
                                @error('address')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('company.edit_edrpo')</label>
                            <div class="col-md-9">
                                <input type="text" name="edrpo" class="form-control m-b-5 @error('edrpo') is-invalid @enderror" placeholder="@lang('company.edit_edrpo')" value="{{$company->edrpo}}"/>
                                @error('edrpo')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('company.edit_inn')</label>
                            <div class="col-md-9">
                                <input type="text" name="inn" class="form-control m-b-5 @error('inn') is-invalid @enderror" placeholder="@lang('company.edit_inn')" value="{{$company->inn}}"/>
                                @error('inn')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('company.edit_bank')</label>
                            <div class="col-md-9">
                                <input type="text" name="bank" class="form-control m-b-5 @error('bank') is-invalid @enderror" placeholder="@lang('company.edit_bank')" value="{{$company->bank}}"/>
                                @error('bank')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('company.edit_mfo')</label>
                            <div class="col-md-9">
                                <input type="text" name="mfo" class="form-control m-b-5 @error('mfo') is-invalid @enderror" placeholder="@lang('company.edit_mfo')" value="{{$company->mfo}}"/>
                                @error('mfo')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('company.edit_pp')</label>
                            <div class="col-md-9">
                                <input type="text" name="pp" class="form-control m-b-5 @error('pp') is-invalid @enderror" placeholder="@lang('company.edit_pp')" value="{{$company->pp}}"/>
                                @error('pp')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary m-r-5">@lang('company.edit_save')</button>
                    </form>
                </div>
            </div>

            <div class="panel panel-primary">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('company.users_block')</h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <div class="table-scroll-container1">
                        <table id="users-table" class="table table-striped table-bordered table-td-valign-middle">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>@lang('company.users_block_name')</th>
                                    <th>@lang('company.users_block_email')</th>
                                    <th>@lang('company.users_block_status')</th>
                                    <th>@lang('company.users_block_registered_time')</th>
                                    <th>@lang('company.users_block_last_login')</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <a href="#modal-add_user" class="btn btn-sm btn-primary" data-toggle="modal">@lang('company.users_block_btn_add')</a>
                </div>
            </div>


            <!-- end panel -->
        </div>
        <!-- end col-6 -->
        <!-- begin col-6 -->
        <div class="col-xl-6">
            <div class="panel panel-primary">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('company.documents_block')</h4>
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
            <div class="panel panel-primary">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('company.documents_load_block')</h4>
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
            <div class="panel panel-primary">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('company.documents_request_block')</h4>
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
    </div>
    <div class="modal fade" id="modal-add_user" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('company.modal_add_user_header')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="form_add_user" method="post" action="{{route('company.users_add')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group m-b-15">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>@lang('company.modal_add_user_name')</label>
                                    <input type="text" name="user_name" class="form-control m-b-5 @error('user_name') is-invalid @enderror" placeholder="@lang('company.modal_add_user_name')" required value="{{old('user_name')}}"/>
                                    @error('user_name')
                                    <span class="invalid-feedback " role="alert">
                                         <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label>@lang('company.modal_add_user_last_name')</label>
                                    <input type="text" name="user_last_name" class="form-control m-b-5 @error('user_last_name') is-invalid @enderror" placeholder="@lang('company.modal_add_user_last_name')" required value="{{old('user_last_name')}}"/>
                                    @error('user_last_name')
                                    <span class="invalid-feedback " role="alert">
                                         <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-b-15">
                            <label>@lang('company.modal_add_user_email')</label>
                            <input type="text" name="user_email" class="form-control m-b-5 @error('user_email') is-invalid @enderror" placeholder="@lang('company.modal_add_user_email')" required value="{{old('user_email')}}"/>
                            @error('user_email')
                            <span class="invalid-feedback " role="alert">
                                         <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-15">
                            <label>@lang('company.modal_add_user_password')</label>
                            <input type="password" name="user_password" class="form-control m-b-5 @error('user_password') is-invalid @enderror" placeholder="@lang('company.modal_add_user_password')" required/>
                            @error('user_name')
                            <span class="invalid-feedback " role="alert">
                                         <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-15">
                            <label>@lang('company.modal_add_user_repassword')</label>
                            <input type="password" name="user_password_confirmation" class="form-control m-b-5 @error('user_password_confirmation') is-invalid @enderror" placeholder="@lang('company.modal_add_user_repassword')" required/>
                            @error('user_password_confirmation')
                            <span class="invalid-feedback " role="alert">
                                         <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">@lang('global.cancel')</a>
                        <button type="submit" class="btn btn-primary btn-add-order">@lang('global.add')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script src="/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/plugins/highlight.js/highlight.min.js"></script>
    <script src="/assets/js/demo/render.highlight.js"></script>
    <script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
    <script>

			$(document).ready(function () {
                @php
                    $error = false;
                @endphp
                @error('user_name')
                    @php
                        $error = true;
                    @endphp
                @enderror
                @error('user_last_name')
                    @php
                        $error = true;
                    @endphp
                @enderror
                @error('user_email')
                    @php
                        $error = true;
                    @endphp
                @enderror
                @error('user_password')
                    @php
                        $error = true;
                    @endphp
                @enderror
                @error('user_password_confirmation')
                    @php
                        $error = true;
                    @endphp
                @enderror

                @if($error)
                    $('#modal-add_user').modal('show');
                @enderror

				$('#datepicker-default').datepicker({
					todayHighlight: true
				});

				document.querySelector('.custom-file-input').addEventListener('change', function (e) {
					var fileName = document.getElementById("uploadPhoto").files[0].name;
					var nextSibling = e.target.nextElementSibling
					nextSibling.innerText = fileName
				})

                @if (session('status'))
				$.gritter.add({
					title: '{{ session('status') }}',
				});
                @endif

					window.table = $('#users-table').DataTable({
					"language": {
						"url": "@lang('table.localization_link')",
					},
					"pageLength": 25,
					"autoWidth": false,
					"processing": true,
					"serverSide": true,
					"ajax": "{{route('company.users_ajax')}}",
					"order": [[0, "desc"]],
					sScrollX: true,
					//"ordering": false,
					//"searching": true,
					fixedHeader: {
						header: true,
						footer: true
					},
					"columns": [
						{
							data: 'id',
							"visible": false,
						},
						{
							className: 'text-center',
							data: 'image_html',
							"orderable": false,
						},
						{
							data: 'name',
						},
						{
							data: 'email',
						},
						{
							data: 'status_html',
							"orderable": false,
						},
						{
							data: 'registered_time',
						},
						{
							data: 'last_login_time',
						},
						{
							data: 'actions',
							"orderable": false,
						},
					],
					"fnDrawCallback": function( oSettings ) {
						$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust()
					}
				});


			});
    </script>
@endpush
