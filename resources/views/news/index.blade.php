@extends('layouts.default')

@push('css')

@endpush

@section('content')
	{{ Breadcrumbs::render('news') }}
	<h1 class="page-header">@lang('news.index_page_name')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('news.all_tab_name')</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
					@foreach($newsData as $newsDatum)
						<div class="col-lg-4 col-sm-6 m-b-15">
							<a width="300" href="{{route('news.show',[$newsDatum['id']])}}" class="card bg-dark border-0 text-white">
								<img class="card-img" src="https://dinmark.com.ua/{{$newsDatum['image']}}" alt="http://dinmark.com.ua/{{$newsDatum['name']}}">
								<div class="card-img-overlay bg-black-transparent-5 rounded">
									<h4 class="card-title">{{$newsDatum['name']}}</h4>
									<p class="card-text">{!! mb_strimwidth($newsDatum['text'],0,100,'...') !!}</p>
								</div>
							</a>
						</div>
					@endforeach
						<div class="col-12">
							<div class="pull-right">
								{{ $news->links() }}
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

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {
			});
		})(jQuery);
	</script>
@endpush
