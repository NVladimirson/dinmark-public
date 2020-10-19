@extends('layouts.default')

@push('css')
    <link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
    <link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="/assets/plugins/lightbox2/dist/css/lightbox.css" rel="stylesheet" />

@endpush

@push('scripts')
    <script src="/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/buttons.print.min.js"></script>

    <script>
        jQuery(function($) {
            window.table =
                $('#datatable').DataTable( {
                    "language": {
                        "url": "@lang('table.localization_link')",
                    },
                    "scrollX": true,
                    "pageLength": 25,
                    "autoWidth": true,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "{!! route('purchases.get_ajax') !!}",
                    },
                    "order": [[ 0, "desc" ]],
                    "columns": [
                        {
                            data:'',
                            "orderable": true,
                            "searchable": false,
                            "width": "10%"
                        },
                        {
                            data:'',
                            "orderable": true,
                            "searchable": false,
                            "width": "10%"
                        },
                        {
                            data:'',
                            "orderable": true,
                            "searchable": false,
                            "width": "10%"
                        },
                        {
                            data:'',
                            "orderable": true,
                            "searchable": false,
                            "width": "10%"
                        },
                        {
                            data:'',
                            "orderable": true,
                            "searchable": false,
                            "width": "10%"
                        },
                        {
                            data:'',
                            "orderable": true,
                            "searchable": false,
                            "width": "10%"
                        },
                        {
                            data:'',
                            "orderable": true,
                            "searchable": false,
                            "width": "10%"
                        },
                        {
                            data:'',
                            "orderable": true,
                            "searchable": false,
                            "width": "10%"
                        },
                        {
                            data:'',
                            "orderable": true,
                            "searchable": false,
                            "width": "10%"
                        },
                        {
                            data:'',
                            "orderable": true,
                            "searchable": false,
                            "width": "10%"
                        }
                    ],
                });
            window.startdate =
                    $("#startdate").datepicker({ dateFormat: "dd-mm-yy" }).val();
            window.enddate =
                    $("#enddate").datepicker({ dateFormat: "dd-mm-yy" }).val()

        });
    </script>
@endpush

@section('content')
    <h1 class="page-header">@if(isset($page_name)) {{$page_name}} @else @lang('order.purchases_pagename') @endif</h1>
    <!-- begin row -->
    <div class="row">
        <div class="col-xl-6">
            <h6>@lang('order.purchases_status')</h6>
        </div>
        <div class="col-xl-6">
            <h6>@lang('order.purchases_search_by_data')</h6>
        </div>
    </div>
    <div class="row" style="margin-bottom: 10px">
        <div class="col-xl-6">
            <select class="custom-select" multiple>
                <option selected>None</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
        <div class="col-xl-3">
            <p>@lang('order.purchases_startdate')<input type="text" id="startdate"></p>
        </div>
        <div class="col-xl-3">
            <p>@lang('order.purchases_enddate')<input type="text" id="enddate"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <table id="datatable" class="table table-striped table-bordered table-td-valign-middle">
                <thead>
                <tr>
                    <th>@lang('order.purchases_table_code/name')</th>
                    {{--<th></th>--}}
                    <th>@lang('order.purchases_table_photo')</th>
                    <th>@lang('order.purchases_table_quantity_in_orders')</th>
                    <th>@lang('order.purchases_table_quantity_in_sellings')</th>
                    <th>@lang('order.purchases_table_quantity_in_returns')</th>
                    <th>@lang('order.purchases_table_sum_of_orders/sellings/reclamations')</th>
                    <th>@lang('order.purchases_table_percentage_of_confirmed_orders')</th>
                    <th>@lang('order.purchases_table_sellings_weight')</th>
                    <th>@lang('order.purchases_CSV-export')</th>
                </tr>
                </thead>

                {{--<tfoot>--}}
                {{--<tr>--}}
                    {{--<th>One</th>--}}
                    {{--<th>Two</th>--}}
                    {{--<th>Three</th>--}}
                {{--</tr>--}}
                {{--</tfoot>--}}
            </table>
        </div>
    </div>
    <div class="row">

    </div>
    <style>

    </style>
@endsection