@extends('layouts.default', ['contentFullWidth' => true])

@section('title', 'Form Elements')
@push('css')
    <link href="/assets/plugins/superbox/superbox.min.css" rel="stylesheet" />
    <link href="/assets/plugins/lity/dist/lity.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="profile" style="margin-top: -1px">
        <div class="profile-header">
            <!-- BEGIN profile-header-cover -->
            <div class="profile-header-cover"></div>
            <!-- END profile-header-cover -->
            <!-- BEGIN profile-header-content -->
            <div class="profile-header-content">
                <!-- BEGIN profile-header-img -->
                <div class="profile-header-img">
                    @if(auth()->user()->photo)
                        <img src="{{env('DINMARK_URL')}}images/profile/{{auth()->user()->photo}}" alt="{{auth()->user()->name}}" />
                    @else
                        <img src="{{env('DINMARK_URL')}}images/empty-avatar.png" alt="{{auth()->user()->name}}" />
                    @endif
                </div>

                <!-- END profile-header-img -->
                <!-- BEGIN profile-header-info -->
                <div class="profile-header-info">
                    <h4 class="mt-0 mb-1">{{auth()->user()->name}}</h4>
                    <p class="mb-2">{{auth()->user()->getCompany->name}}</p>
                    <p class="mb-0"><strong>@lang('sidebar.manager'):</strong> {{auth()->user()->getCompany->getManager->name}}</p>
                </div>
                <!-- END profile-header-info -->
            </div>
        </div>
    </div>

    <h1 class="page-header">@lang('user.edit_page_name')</h1>
    <div class="profile-content p-t-0">
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
                                <input type="text" name="birthday" class="form-control @error('birthday') is-invalid @enderror" id="datepicker-default" placeholder="@lang('user.edit_birthday')" autocomplete="off"  value="{{ (auth()->user()->info->firstWhere('field','birthday'))? auth()->user()->info->firstWhere('field','birthday')->value : '' }}"/>
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
            @if(auth()->user()->export_key)
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
                    <p>@lang('user.feed_message')</p>
                    <p><strong>@lang('user.feed_link_ua'):</strong> <a href="https://dinmark.com.ua/shop/export_prom?userKey={{auth()->user()->export_key}}" target="_blank">https://dinmark.com.ua/shop/export_prom?userKey={{auth()->user()->export_key}}</a></p>
                    <p><strong>@lang('user.feed_link_ru'):</strong> <a href="https://dinmark.com.ua/ru/shop/export_prom?userKey={{auth()->user()->export_key}}" target="_blank">https://dinmark.com.ua/ru/shop/export_prom?userKey={{auth()->user()->export_key}}</a></p>
                </div>
            </div>
            @endif
        </div>
        <!-- end col-6 -->
    </div>
    <!-- end row -->
    </div>
    @endsection

@push('scripts')
    <script src="/assets/plugins/highlight.js/highlight.min.js"></script>
    <script src="/assets/js/demo/render.highlight.js"></script>
    <script>
		$('#datepicker-default').datepicker({
			todayHighlight: true
		});

		document.querySelector('.custom-file-input').addEventListener('change',function(e){
			var fileName = document.getElementById("uploadPhoto").files[0].name;
			var nextSibling = e.target.nextElementSibling
			nextSibling.innerText = fileName
		})

    </script>
@endpush
