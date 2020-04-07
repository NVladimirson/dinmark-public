@extends('layouts.default')

@section('title', 'Form Elements')
@push('css')
    <link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
@endpush

@section('content')
    {{ Breadcrumbs::render('user.log') }}

    <h1 class="page-header">@lang('user.log_page_name')</h1>

    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-xl-12">
            <!-- begin panel -->

            <div class="panel panel-primary" data-sortable-id="form-stuff-1">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('user.log_tab_name')</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>@lang('user.log_date')</th>
                            <th>@lang('user.log_action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>{{\Carbon\Carbon::parse($log->date)->format('d.m.Y i:h')}}</td>
                                <td>{{$log->action->title_public}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
            <!-- end panel -->
        </div>
    </div>
    <!-- end row -->

    @endsection

@push('scripts')
    <script src="/assets/plugins/highlight.js/highlight.min.js"></script>
    <script src="/assets/js/demo/render.highlight.js"></script>
@endpush