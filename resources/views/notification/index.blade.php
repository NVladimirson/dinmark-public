@extends('layouts.default')

@push('css')
@endpush

@section('content')
	{{ Breadcrumbs::render('notification') }}
	<h1 class="page-header">@lang('notification.page_name')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('notification.tab_list')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
						<div class="col-12">
							<div class="dropdown-menu media-list" style="display: block; width: 100%; max-width: 100%; position: static; box-shadow: none">
								@forelse($notifications as $notification)
									@include('notification.item')
								@empty
									<div class="alert alert-light fade show">@lang('notification.empty')</div>
								@endforelse
							</div>
						</div>
					</div>
						<div class="row m-t-15">
							<div class="col-md-12">
								<div class="pull-right">
									{{ $notifications->links() }}
								</div>
							</div>
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
@endpush
