@extends('layouts.default')

@section('title', 'Managed Tables - Buttons')

@push('css')
	<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
@endpush

@section('content')
	{{ Breadcrumbs::render('faq') }}

	<h1 class="page-header">@lang('faq.page_name')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('faq.all_tab_name')</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div id="accordion" class="accordion">
					@foreach($questions as $question)
						<div class="card bg-white ">
							<div class="card-header d-flex align-items-center" data-toggle="collapse" data-target="#collapse-{{$question['id']}}" aria-expanded="true" style="cursor: pointer">
								<i class="fa fa-circle fa-fw text-blue mr-2 f-s-8"></i> {{$question['question']}}
							</div>
							<div id="collapse-{{$question['id']}}" class="collapse" data-parent="#accordion" style="">
								<div class="card-body">
									{{$question['answer']}}
								</div>
							</div>
						</div>
					@endforeach
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
	<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
@endpush
