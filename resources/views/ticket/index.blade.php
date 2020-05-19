@extends('layouts.default')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
	{{ Breadcrumbs::render('ticket') }}
	<h1 class="page-header">@lang('ticket.page_name')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('ticket.tab_list')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row m-b-15">
						<div class="col-md-4">
							<a href="{{route('ticket.create')}}" class="btn btn-primary">@lang('ticket.button_new')</a>
						</div>
						<div class="col-md-8">

							<div class="pull-right">
								{{ $tickets->links() }}
							</div>
						</div>
					</div>
					@forelse($tickets as $ticket)
						<div class="widget-list widget-list-rounded">
							<!-- begin widget-list-item -->
							<a href="{{route('ticket.show',[$ticket->id])}}" class="widget-list-item">
								<div class="widget-list-media">
									@if(auth()->user()->type == 1 || auth()->user()->type == 2 )
										@if($ticket->user->photo)
											<img class="rounded-corner" src="{{env('DINMARK_URL')}}images/profile/{{$ticket->user->photo}}" alt="{{$ticket->user->name}}" />
										@else
											<img class="rounded-corner" src="{{env('DINMARK_URL')}}images/empty-avatar.png" alt="{{$ticket->user->name}}" />
										@endif
									@else
										@if($ticket->manager->photo)
											<img class="rounded-corner" src="{{env('DINMARK_URL')}}images/profile/{{$ticket->manager->photo}}" alt="{{$ticket->manager->name}}" />
										@else
											<img class="rounded-corner" src="{{env('DINMARK_URL')}}images/empty-avatar.png" alt="{{$ticket->manager->name}}" />
										@endif
									@endif
								</div>
								<div class="widget-list-content">
									<h4 class="widget-list-title">{{$ticket->subject}}</h4>
									<p class="widget-list-desc">{{mb_strimwidth ($ticket->messages->last()->text, 0, 50)}}</p>
								</div>
								<div class="widget-list-action">
									@if($ticket->messages_count > 0)
									<span class="badge badge-primary pull-right">{{$ticket->messages_count}}</span>
										@else
										<span class="badge badge-secondary pull-right">{{$ticket->messages_count}}</span>
									@endif
								</div>
							</a>
							<!-- end widget-list-item -->
						</div>
					@empty
						<div class="alert alert-light fade show">@lang('ticket.empty')</div>
					@endforelse
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
@endpush