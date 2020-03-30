@extends('layouts.default')

@push('css')
@endpush

@section('content')
	{{ Breadcrumbs::render('notification') }}
	<h1 class="page-header">@lang('admin_user.change_data_page_name')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-6">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('admin_user.change_data_tab_name')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					@if($changeData->status != 'await')
						<div class="alert alert-{{$changeData->status == 'success'?'green':'danger'}} fade show m-b-10">
							@lang('admin_user.status_'.$changeData->status)
						</div>
					@endif
					<dl class="row">
						<dt class="text-inverse text-right col-6 text-truncate">@lang('admin_user.user_id'):</dt>
						<dd class="col-6 text-truncate">{{$changeData->user->id}}</dd>

						<dt class="text-inverse text-right col-6 text-truncate">@lang('admin_user.user_name'):</dt>
						<dd class="col-6 text-truncate">{{$changeData->user->name}}</dd>
						@if($changeData->type == 'email')
							<dt class="text-inverse text-right col-6 text-truncate">@lang('admin_user.user_old_email'):</dt>
							<dd class="col-6 text-truncate">{{$changeData->user->email}}</dd>
							<dt class="text-inverse text-right col-6 text-truncate">@lang('admin_user.user_new_email'):</dt>
							<dd class="col-86 text-truncate">{{$changeData->value}}</dd>
						@else
							<dt class="text-inverse text-right col-6 text-truncate">@lang('admin_user.user_old_phone'):</dt>
							<dd class="col-6 text-truncate">{{$userPhone}}</dd>
							<dt class="text-inverse text-right col-6 text-truncate">@lang('admin_user.user_new_phone'):</dt>
							<dd class="col-6 text-truncate">{{$changeData->value}}</dd>
						@endif
					</dl>
						<div class="alert alert-muted">
							<p>@lang('admin_user.change_data_note')</p>
						</div>
					@if($changeData->status == 'await')
					<form action="{{route('admin.user.change_request_answer',[$changeData->id])}}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-6"><button type="submit" name="submit" value="success" class="btn btn-sm btn-success pull-right">@lang('admin_user.ok')</button></div>
							<div class="col-6"><button type="submit" name="submit" value="rejected" class="btn btn-sm btn-danger m-r-5">@lang('admin_user.rejected')</button></div>
						</div>
					</form>
					@endif
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
@endpush