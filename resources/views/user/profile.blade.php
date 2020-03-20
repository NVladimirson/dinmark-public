@extends('layouts.default')

@section('title', 'Form Elements')
@push('css')
    <link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
@endpush

@section('content')
    {{ Breadcrumbs::render('user.profile') }}

    <h1 class="page-header">@lang('user.edit_page_name')</h1>

    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-xl-6">
            <!-- begin panel -->

            <div class="panel panel-primary" data-sortable-id="form-stuff-1">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('user.edit_personal_data')</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <form action="{{route('user.profile.update_data')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('user.edit_name')</label>
                            <div class="col-md-9">
                                <input type="text" name="name" class="form-control m-b-5 @error('name') is-invalid @enderror" placeholder="@lang('user.edit_name')" value="{{auth()->user()->name}}"/>
                                @error('name')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">@lang('user.edit_birthday')</label>
                            <div class="col-lg-9">
                                <input type="text" name="birthday" class="form-control @error('birthday') is-invalid @enderror" id="datepicker-default" placeholder="@lang('user.edit_birthday')" />
                                @error('birthday')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="input-group mb-3 @error('photo') is-invalid @enderror">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="uploadPhotoAddon">@lang('user.edit_photo')</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="photo" class="custom-file-input @error('photo') is-invalid @enderror" id="uploadPhoto" aria-describedby="uploadPhotoAddon">
                                    <label class="custom-file-label" for="uploadPhoto">@lang('user.edit_select_photo')</label>
                                </div>
                            </div>
                            @error('photo')
                            <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary m-r-5">@lang('user.edit_save')</button>
                    </form>
                </div>
            </div>

            <div class="panel panel-primary" data-sortable-id="form-stuff-2">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('user.edit_send_request_data')</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <form action="{{route('user.profile.change_request')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('user.edit_email')</label>
                            <div class="col-md-9">
                                <input type="email" name="email" class="form-control m-b-5 @error('email') is-invalid @enderror" placeholder="@lang('user.edit_email')" value="{{auth()->user()->email}}"/>
                                <small class="f-s-12 text-grey-darker">@lang('user.edit_email_note')</small>
                                @error('email')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('user.edit_phone')</label>
                            <div class="col-md-9">
                                <input type="text" name="phone" class="form-control m-b-5 @error('phone') is-invalid @enderror" placeholder="@lang('user.edit_phone')"  value="{{ (auth()->user()->info->firstWhere('field','phone'))? auth()->user()->info->firstWhere('field','phone')->value : '' }}"/>
                                @error('phone')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary m-r-5 m-b-15">@lang('user.edit_request')</button>

                        @if(auth()->user()->dataChangeRequest)
                            <div class="alert alert-muted">
                                <h4>@lang('user.data_change_request_header')</h4>
                                @if(auth()->user()->dataChangeRequest->firstWhere('type','email'))
                                    <p><strong>@lang('user.data_change_request_email'):</strong> {{auth()->user()->dataChangeRequest->firstWhere('type','email')->value}}</p>
                                @endif
                                @if(auth()->user()->dataChangeRequest->firstWhere('type','phone'))
                                    <p><strong>@lang('user.data_change_request_phone'):</strong> {{auth()->user()->dataChangeRequest->firstWhere('type','phone')->value}}</p>
                                @endif
                            </div>
                        @endif
                    </form>
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-6 -->
        <!-- begin col-6 -->
        <div class="col-xl-6">
            <div class="panel panel-primary" data-sortable-id="form-stuff-3">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('user.edit_password_block')</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <form action="{{route('user.profile.update_password')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">@lang('user.edit_password')</label>
                            <div class="col-md-9">
                                <input type="password" name="password" class="form-control m-b-5 @error('password') is-invalid @enderror" placeholder="@lang('user.edit_password')" />
                                @error('password')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">@lang('user.edit_password_repeat')</label>
                            <div class="col-lg-9">
                                <input type="password" name="password_confirmation" class="form-control m-b-5" placeholder="@lang('user.edit_password_repeat')" />
                            </div>
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary m-r-5">@lang('user.edit_save')</button>
                    </form>
                </div>
            </div>

            <div class="panel panel-primary" data-sortable-id="form-stuff-4">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('user.feeds')</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <p>Тут будут Фиды</p>
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