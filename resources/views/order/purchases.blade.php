@extends('layouts.default')

@push('css')
    <link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
    <link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="/assets/plugins/lightbox2/dist/css/lightbox.css" rel="stylesheet" />
    <link href="/assets/css/default/table-ptoduct.css" rel="stylesheet" />
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
    <script src="/assets/plugins/select2/dist/js/vue.min.js"></script>
    <script>
        const wrapTable = new Vue({
            el: "#wrap-table",
            data: {
                isShow: false
            },
            methods: {
                toggleShow: function() {
                    this.isShow = !this.isShow
                }
            }
        })
    </script>
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
                    $("#enddate").datepicker({ dateFormat: "dd-mm-yy" }).val();

                    $("#optionfilters").accordion({
                        collapsible: true,
                        active: false,
                        heightStyle: "content",
                        content: '.filter'
                    });

                    $("#accordion").accordion({
                        collapsible: true,
                        active: false,
                        heightStyle: "content",
                        content: '.content1'
                    });

        });
    </script>
@endpush

@section('content')
<div id="wrap-table">
        <i v-show="!isShow" v-on:click="toggleShow" id="slide-filter-on" class="fa fa-filter"></i>
        <i v-show="isShow" v-on:click="toggleShow" id="slide-filter-of" class="fa fa-angle-double-right"></i>
    <h1 class="page-header">@if(isset($page_name)) {{$page_name}} @else @lang('order.purchases_pagename') @endif</h1>
    <div v-show="isShow" class="responsive-width">
    <div id="scroll-filter" class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">@lang('product.right_widget_name')</h4>
        </div>
        <div id="reload" style="display:none"></div>
        <div id="selected_products" style="display:none"></div>
        <div id="filters_selected">

        </div>
        <div id="accordion" class=".ui-helper-reset">
            <p style="font-size: 12pt;">@lang('product.all_categories_name')</p>
            <div id="jstree" class="content1"></div>

            <p style="font-size: 12pt;"> @lang('product.filters-with-properties')</p>
            <div id="optionfilters" class="content1">
                @foreach($filters as $option_id=>$filterdata)
                    <h3 class="filtername" filter_name="{!! $filterdata['data']['name'] !!}"><b>{!! $filterdata['data']['name'] !!}</b></h3>
                    <div class="filter" id="filter">
                        @php $i=0;@endphp
                        @foreach($filterdata['options'] as $branch_id => $data)

                            @if($i % 2 == 0)
                                <div class="row" style="margin: auto">
                                    @endif

                                    <div class="col-md-12">
                                        <div class="row" style="margin: auto">
                                            @if(isset($data['data']['photo']))
                                                @php $url = $dinmark_url.'/images/shop/options/'.$filterdata['data']['alias'].
                            '/'.$data['data']['photo']; @endphp
                                                <div class="image-container"><img width="50" src="{!! $url !!}" title="{!! $data['data']['name'] !!}"></div>
                                            @else
                                                @php $url = $dinmark_url.'style/images/checkbox.svg'; @endphp
                                                <div class="image-container"><img width="50" src="{!! $url !!}" title="{!! $data['data']['name'] !!}" alt="unset"></div>
                                            @endif
                                            <p class="filter_with_options" option_id="{!! $data['data']['option'] !!}" option_name="{!! $data['data']['name'] !!}" option_filter_name="{!! $filterdata['data']['name'] !!}" filter-selected="false" filter-accessible="true" style="cursor:pointer">{!! $data['data']['name'] !!}
                                                {{--<i id="filter-checked_{!! $value !!}" class="fas fa-check-circle"--}}
                                                {{--aria-hidden="true" style="display: none"></i>--}}
                                            </p>
                                        </div>
                                    </div>

                                    @if($i % 2 == 1)
                                </div>
                            @endif

                            @php $i++; @endphp
                        @endforeach
                        @if($i % 2 != 0)
                    </div>
                    @endif
            </div>
            @endforeach
        </div>

    </div>
    </div>

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
    <div v-show="isShow" class="responsive-width">
            <div id="scroll-filter" class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('product.right_widget_name')</h4>
                </div>
                <div id="reload" style="display:none"></div>
                <div id="selected_products" style="display:none"></div>
                <div id="filters_selected">

                </div>
                <div id="accordion" class=".ui-helper-reset">
                    <p style="font-size: 12pt;">@lang('product.all_categories_name')</p>
                    <div id="jstree" class="content1"></div>

                    <p style="font-size: 12pt;"> @lang('product.filters-with-properties')</p>
                    <div id="optionfilters" class="content1">
                        @foreach($filters as $option_id=>$filterdata)
                            <h3 class="filtername" filter_name="{!! $filterdata['data']['name'] !!}"><b>{!! $filterdata['data']['name'] !!}</b></h3>
                            <div class="filter" id="filter">
                                @php $i=0;@endphp
                                @foreach($filterdata['options'] as $branch_id => $data)

                                    @if($i % 2 == 0)
                                        <div class="row" style="margin: auto">
                                            @endif

                                            <div class="col-md-12">
                                                <div class="row" style="margin: auto">
                                                    @if(isset($data['data']['photo']))
                                                        @php $url = $dinmark_url.'/images/shop/options/'.$filterdata['data']['alias'].
                                    '/'.$data['data']['photo']; @endphp
                                                        <div class="image-container"><img width="50" src="{!! $url !!}" title="{!! $data['data']['name'] !!}"></div>
                                                    @else
                                                        @php $url = $dinmark_url.'style/images/checkbox.svg'; @endphp
                                                        <div class="image-container"><img width="50" src="{!! $url !!}" title="{!! $data['data']['name'] !!}" alt="unset"></div>
                                                    @endif
                                                    <p class="filter_with_options" option_id="{!! $data['data']['option'] !!}" option_name="{!! $data['data']['name'] !!}" option_filter_name="{!! $filterdata['data']['name'] !!}" filter-selected="false" filter-accessible="true" style="cursor:pointer">{!! $data['data']['name'] !!}
                                                        {{--<i id="filter-checked_{!! $value !!}" class="fas fa-check-circle"--}}
                                                        {{--aria-hidden="true" style="display: none"></i>--}}
                                                    </p>
                                                </div>
                                            </div>

                                            @if($i % 2 == 1)
                                        </div>
                                    @endif

                                    @php $i++; @endphp
                                @endforeach
                                @if($i % 2 != 0)
                            </div>
                            @endif
                    </div>
                    @endforeach
                </div>

            </div>

            <div id="filters">
                {{--<p style="font-size: 12pt;">@lang('product.filters.header')</p>--}}
                <div style="height: 10%;padding: 10px;background-color: #E4F1DD">
                    <h5 style="text-align: center">
                        <a href="#" id="new">
                            <i class="fa fa-bullhorn" aria-hidden="true"></i> @lang('product.filters.new')</a>
                        <i id="new-checked" class="fas fa-check-circle" style="display: none"></i></h5>
                </div>
                <div style="height: 10%;padding: 10px;background-color: #FCF2DF">
                    <h5 style="text-align: center"><a href="#" id="hits">
                            <i class="fa fa-thumbs-up" aria-hidden="true"></i> @lang('product.filters.hits')</a>
                        <i id="hits-checked" class="fas fa-check-circle" style="display: none"></i></h5>
                </div>
                <div style="height: 10%;padding: 10px;background-color: #FCE1DF">
                    <h5 style="text-align: center"><a href="#" id="discount">
                            <i class="fa fa-percent" aria-hidden="true"></i> @lang('product.filters.discount')</a>
                        <i id="discount-checked" class="fas fa-check-circle" style="display: none"></i></h5>
                </div>

            </div>
        </div>
    </div>
@endsection
