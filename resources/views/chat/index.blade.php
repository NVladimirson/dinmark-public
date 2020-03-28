@extends('layouts.default')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
	{{ Breadcrumbs::render('chat') }}
	<h1 class="page-header">@lang('chat.page_name')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('chat.tab_list')</h4>
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
							<a href="{{route('chat.create')}}" class="btn btn-primary">@lang('chat.button_new')</a>
						</div>
						<div class="col-md-8">

							<div class="pull-right">
								{{ $chats->links() }}
							</div>
						</div>
					</div>
					@forelse($chats as $chat)
						<div class="widget-list widget-list-rounded">
							<!-- begin widget-list-item -->
							<a href="{{route('chat.show',[$chat->id])}}" class="widget-list-item">
								<div class="widget-list-media">
									@if(auth()->user()->type == 1 || auth()->user()->type == 2 )
										@if($chat->user->photo)
											<img class="rounded-corner" src="{{env('DINMARK_URL')}}images/profile/{{$chat->user->photo}}" alt="{{$chat->user->name}}" />
										@else
											<img class="rounded-corner" src="{{env('DINMARK_URL')}}images/empty-avatar.png" alt="{{$chat->user->name}}" />
										@endif
									@else
										@if($chat->manager->photo)
											<img class="rounded-corner" src="{{env('DINMARK_URL')}}images/profile/{{$chat->manager->photo}}" alt="{{$chat->manager->name}}" />
										@else
											<img class="rounded-corner" src="{{env('DINMARK_URL')}}images/empty-avatar.png" alt="{{$chat->manager->name}}" />
										@endif
									@endif
								</div>
								<div class="widget-list-content">
									<h4 class="widget-list-title">{{$chat->subject}}</h4>
									<p class="widget-list-desc">{{mb_strimwidth ($chat->messages->last()->text, 0, 50)}}</p>
								</div>
								<div class="widget-list-action">
									@if($chat->messages_count > 0)
									<span class="badge badge-secondary pull-right">{{$chat->messages_count}}</span>
									@endif
								</div>
							</a>
							<!-- end widget-list-item -->
						</div>
					@empty
						<div class="alert alert-light fade show">@lang('chat.empty')</div>
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

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {
				window.table = $('#data-table-buttons').DataTable( {
					"language": {
						"url": "@lang('table.localization_link')",
						searchPlaceholder: "Поиск"
					},
					//"scrollX": true,
					"pageLength": 25,
					"autoWidth": true,
					"processing": true,
					"serverSide": true,
					"ajax": "{!! route('products.all_ajax') !!}",
					"order": [[ 0, "desc" ]],
					"columns": [
						{
							className: 'text-center',
							data: 'id',
							"visible": false,
							"searchable": false
						},
						{
							"orderable":      false,
							data: 'check_html',
						},
						{
							"orderable":      false,
							data: 'image_html',
						},
						{
							"orderable":      false,
							data: 'name',
						},
						{
							data: 'article_show',
						},
						{
							data: 'user_price',
						},
						{
							data: 'limit_1',
						},
						{
							data: 'limit_2',
						},
						{
							data: 'storage_html',
						},
						{
							data: 'actions',
						},
					],
				} );
			});
		})(jQuery);
	</script>
@endpush