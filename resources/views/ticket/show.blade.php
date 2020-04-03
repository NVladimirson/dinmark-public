@extends('layouts.default')

@push('css')
	<link href="/assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet" />
@endpush

@section('content')
	{{ Breadcrumbs::render('ticket.show',$ticket) }}
	<h1 class="page-header">{{trans('ticket.dialog').': '.$ticket->subject}}</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">{{$ticket->subject}}</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="widget-chat widget-chat-rounded">
						<!-- begin widget-chat-body -->
						<div class="widget-chat-body" data-scrollbar="true" data-height="500px">
							@php
								$current_date = "";
							@endphp
							@foreach($ticket->messages as $message)
								@if($current_date != \Carbon\Carbon::parse($message->created_at)->format('d.m.Y'))
									@php
										$current_date = \Carbon\Carbon::parse($message->created_at)->format('d.m.Y');
									@endphp
								<div class="text-center text-muted m-10 f-w-600">{{ $current_date }}</div>
								@endif
							<div class="widget-chat-item @if($message->user_id != auth()->user()->id) with-media left @else right @endif">
								@if($message->user_id != auth()->user()->id)
								<div class="widget-chat-media">
									@if($message->user->photo)
										<img src="{{env('DINMARK_URL')}}images/profile/{{$message->user->photo}}" alt="{{$message->user->name}}" />
									@else
										<img src="{{env('DINMARK_URL')}}images/empty-avatar.png" alt="{{$message->user->name}}" />
									@endif
								</div>
								@endif
								<div class="widget-chat-info">
									<div class="widget-chat-info-container">
										@if($message->user_id != auth()->user()->id)
										<div class="widget-chat-name text-indigo">{{$message->user->name}}</div>
										@endif
										<div class="widget-chat-message">{{$message->text}}</div>
										<div class="widget-chat-time">{{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}</div>
									</div>
								</div>
							</div>
							@endforeach
						</div>
						<!-- end widget-chat-body -->

						<!-- begin widget-input -->
						<div class="widget-input widget-input-rounded">
							<form action="{{route('ticket.update',[$ticket->id])}}" method="POST">
								@csrf
								<div class="widget-input-container">
									<div class="widget-input-box">
										<input type="text" name="text" required class="form-control" placeholder="@lang('ticket.message')" autofocus/>
									</div>
									<div class="widget-input-icon"><button type="submit" class="text-grey btn"><i class="ion fa-2x ion-md-send fa-fw "></i></button></div>
								</div>
							</form>
						</div>
						<!-- end widget-input -->
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