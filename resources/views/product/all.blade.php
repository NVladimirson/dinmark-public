@extends('layouts.default')

@section('title', 'Managed Tables - Buttons')

@push('css')
    <link href="/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="/assets/plugins/jstree/dist/style.min.css" rel="stylesheet" />
@endpush

@section('content')
    @if(isset($breadcrumbs))
        {{ Breadcrumbs::render('product.categories',$breadcrumbs) }}
    @else
        {{ Breadcrumbs::render('product.all') }}
    @endif
    @php
        use Illuminate\Support\Str;
    @endphp
    <h1 class="page-header">@if(isset($page_name)) {{$page_name}} @else @lang('product.all_page_name') @endif</h1>
    <!-- begin row -->
    <div class="row">
        <div class="col-xl-9">
            <!-- begin panel -->
            <div class="panel panel-primary">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">@lang('product.all_tab_name')</h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xl-3">

                            @if(Request::get('instock'))
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="instockToggler" checked>
                                    <label class="custom-control-label" for="instockToggler">	@lang('product.in_stock_button_name')</label>
                                </div>
                            @else
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="instockToggler">
                                    <label class="custom-control-label" for="instockToggler">	@lang('product.in_stock_button_name')</label>
                                </div>
                            @endif
                        </div>

                        <div class="col-xl-4">
                            <select class="form-control" id="mass_actions" data-size="2" data-style="btn-white">
                                <option value="0">@lang('product.mass_actions.select')</option>
                                <option value="wishlist">@lang('product.mass_actions.add-to-wishlist')</option>
                                <option value="order">@lang('product.mass_actions.add-to-order')</option>
                            </select>
                        </div>

                        <div class="col-xl-5">
                            <div class="right-align m-b-15">
                                @if(isset($terms))
                                    <select class="form-control selectpicker" id="storages" data-size="10" data-live-search="true" data-style="btn-white">
                                        <option value="0">@lang('product.select_term')</option>
                                        @foreach($terms as $key => $term)
                                            <option value="{{$key}}">{!! $term !!}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="table-scroll-container">
                        <table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle">
                            <thead>
                            <tr>
                                <th></th>
                                <th width="30">
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="select_all_products">
                                        <label for="select_all_products"> </label>
                                    </div>
                                </th>
                                <th width="1%" data-orderable="false"></th>
                                <th class="text-nowrap">@lang('product.table_header_name')</th>
                                <th class="text-nowrap">@lang('product.table_header_article')</th>
                                <th class="text-nowrap">@lang('product.table_header_price')</th>
                                <th class="text-nowrap">@lang('product.table_header_price_porog_1')</th>
                                <th class="text-nowrap">@lang('product.table_header_price_porog_2')</th>
                                <th class="text-nowrap">@lang('product.table_header_storage')</th>
                                <th width="124">
                                    <!-- <div id = "mass_actions">
                                        <a href="#" class="btn btn-sm btn-success m-r-4"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="btn btn-sm btn-success m-r-4"><i class="fas fa-star"></i></a>
                                        <a href="#" class="btn btn-sm btn-success m-r-4"><i class="fas fa-cart-plus"></i></a>
                                    </div> -->
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
        </div>
        <div class="col-xl-3">

            <div class="panel panel-primary">
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
                        @foreach($filters as $filtername=>$filterdata)
                            <h3 class="filtername" filter_name="{!! $filtername !!}">{!! $filtername !!}</h3>
                            <div class="filter" id="filter">
                                @php $i=0;@endphp
                                @foreach($filterdata as $value=>$data)

                                    @if($i % 2 == 0)
                                        <div class="row">
                                    @endif

                                            <div class="col-md-6">
                                                <p class="filter_with_options"
                                                   option_id="{!! $data[array_key_first($data)]['value_id'] !!}"
                                                   option_name="{!! $value !!}"
                                                   option_filter_name = "{!! $filtername !!}"
                                                   filter-selected="false"
                                                   filter-accessible="true"
                                                   style="cursor:pointer">{!! $value !!}
                                                    {{--<i id="filter-checked_{!! $value !!}" class="fas fa-check-circle"--}}
                                                       {{--aria-hidden="true" style="display: none"></i>--}}
                                                </p>
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
                                <i class="fa fa-bullhorn" aria-hidden="true"></i>  @lang('product.filters.new')</a>
                            <i id="new-checked" class="fas fa-check-circle" style="display: none"></i></h5>
                    </div>
                    <div style="height: 10%;padding: 10px;background-color: #FCF2DF">
                        <h5 style="text-align: center"><a href="#" id="hits">
                                <i class="fa fa-thumbs-up" aria-hidden="true"></i>  @lang('product.filters.hits')</a>
                            <i id="hits-checked" class="fas fa-check-circle" style="display: none"></i></h5>
                    </div>
                    <div style="height: 10%;padding: 10px;background-color: #FCE1DF">
                        <h5 style="text-align: center"><a href="#" id="discount">
                                <i class="fa fa-percent" aria-hidden="true"></i>  @lang('product.filters.discount')</a>
                            <i id="discount-checked" class="fas fa-check-circle" style="display: none"></i></h5>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    <!-- end row -->

    @include('product.include.modal_wishlist')
    @include('product.include.modal_order')
    @include('product.include.modal_order_multiple')
    @include('product.include.modal_get_price')
@endsection

@push('scripts')
    <script src="/assets/plugins/jstree/dist/jstree.min.js"></script>
    <script src="/assets/plugins/jstree/dist/jstree.js"></script>
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

    <script src="/assets/plugins/pdfmake/build/pdfmake.min.js"></script>
    <script src="/assets/plugins/pdfmake/build/vfs_fonts.js"></script>
    <script src="/assets/plugins/jszip/dist/jszip.min.js"></script>
    <script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
    <script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>

    <script>
        jQuery(function($) {
            window.loading = 0;
            var loaded = [];
            setFiltersQuantity();
            $("#jstree").jstree({
                "plugins": ["wholerow", "checkbox", "json_data"],
                "core": {
                    "check_callback": true,
                    "data": {
                        url: "{!! @route('getnode', ['id' => 0]) !!}",
                        contentType: "application/json; charset=utf-8",
                    },
                }
            }).on('deselect_node.jstree', function(e, data) {
                jsTreetoDatatable();
                initOptionFilters();
            })
                .on('select_node.jstree', function(e, data) {
                    jsTreetoDatatable();
                    initOptionFilters();
                }).
            on('before_open.jstree', function(e, data) {
                if (!loaded.includes(data.node.id)) {

                    var url = "{!! @route('getnode', ['id' => 0]) !!}";
                    url = url.substring(0, url.length - 1) + data.node.id;
                    $.ajax({
                        method: "GET",
                        url: url,
                        success: function(resp) {
                            // $('#jstree').jstree().delete_node();
                            $('#jstree').jstree().delete_node($('#jstree').jstree().get_node(data.node.id).children);
                            var child = resp;
                            //var child = {"text" : "atet", "id" : 46};
                            child.forEach(function(index, item) {
                                $('#jstree').jstree().create_node(data.node.id, index, "last",
                                    function() {

                                    });
                                $('#jstree').jstree().deselect_node(index.id, true);
                            });
                        },
                        error: function(xhr, str) {
                            console.log(xhr);
                        }
                    });
                    loaded.push(data.node.id);
                }
            });

            $('#data-table-buttons').on( 'draw.dt', function () {
                $('#select_all_products').prop('checked', false);
            } );

            window.table =
                $('#data-table-buttons').DataTable( {
                    "language": {
                        "url": "@lang('table.localization_link')",
                    },
                    //"scrollX": true,
                    "pageLength": 25,
                    "autoWidth": true,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "{!! route('products.all_ajax') !!}",
                        // "data" : {
                        // 	"categories" : getCategoryParams()
                        // }
                        "data": {
                            "categories" : 	function ( ) {
                                var node = document.getElementById('reload');
                                textContent = node.textContent;
                                var res = textContent.split(",");
                                if(!res){
                                    return [];
                                }
                                return res;
                            },
                            "instock" : 	function ( ) {
                                return $('#instockToggler').prop('checked');
                            },
                            "term" : 	function ( ) {
                                var optionSelected = $("option:selected", $('#storages')).val();
                                return optionSelected;
                            },
                            "new" : function ( ){
                                if($('#new-checked').css("display") === 'none'){
                                    return 0;
                                }
                                else{
                                    return 1;
                                }
                            },
                            "hits" : function ( ){
                                if($('#hits-checked').css("display") === 'none'){
                                    return 0;
                                }
                                else{
                                    return 1;
                                }
                            },
                            "discount" : function ( ){
                                if($('#discount-checked').css("display") === 'none'){
                                    return 0;
                                }
                                else{
                                    return 1;
                                }
                            },
                            "filter_with_options":function ( ){
                                let filter_selected_map = $("[filter-selected=true]");
                                filter_selected_ids = Array();
                                $.each(filter_selected_map,function (key,value) {
                                    if(value.attributes['filter-selected'].value === 'true'){
                                        let option_id = value.attributes['option_id'].value;
                                        let option_name = value.attributes['option_name'].value;
                                        filter_selected_ids.push(option_id+';'+option_name);
                                        //filter_selected_ids.option_id = option_name;
                                    }
                                });
                                //console.log('filter_with_options: ' + filter_selected_ids)
                                return filter_selected_ids;

                            }
                        }

                    },
                    "order": [[ 0, "desc" ]],
                    "columns": [
                        {
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
                            data: 'name_html',
                        },
                        {
                            data: 'article_show_html',
                        },
                        {
                            data: 'user_price',
                        },
                        {
                            data: 'html_limit_1',
                        },
                        {
                            data: 'html_limit_2',
                        },
                        {
                            data: 'storage_html',
                            "orderable":      false,
                        },
                        {
                            data: 'actions',
                            "orderable":      false,
                        },
                    ],
                    "preUpload": function(settings, json) {
                        $('#select_all_products').prop('checked', false);
                    }
                });

            $( "#accordion" ).accordion({
                collapsible: true,
                active: false,
                heightStyle: "content",
                content : '.content1'
            });
            function initOptionFilters(){
                if(window.loading === 0){
                    window.loading = 1;
                    let filter_selected_map = $("[filter-selected=true]");
                    let all_filters = $(".filter_with_options");
                    $.each(all_filters,function (key,value) {
                        value.setAttribute("style", "color:grey;cursor:progress");
                    });
                    filter_selected_ids = Array();
                    $.each(filter_selected_map,function (key,value) {
                        if(value.attributes['filter-selected'].value === 'true'){
                            let option_id = value.attributes['option_id'].value;
                            let option_name = value.attributes['option_name'].value;
                            filter_selected_ids.push(option_id+';'+option_name);
                            //filter_selected_ids.option_id = option_name;
                        }
                    });
                    let route = 'products/option-filters';

                    $.ajax({
                        method: "GET",
                        url: route,
                        data: {
                            filter_with_options : filter_selected_ids,
                            language: 'ru'
                        },
                        success: function(resp)
                        {
                            let element = document.getElementById("filters_selected");
                            while (element.firstChild) {
                                element.removeChild(element.firstChild);
                            }

                            $.each(all_filters,function (key,value) {
                                value.setAttribute("style", "color:red;cursor:not-allowed");
                                value.setAttribute("filter-accessible","false");
                            });

                            if(resp.available){
                                $.each(resp.available, function( index, value ) {
                                    let filter_by_id = $("[option_id="+index+"]");
                                    filter_by_id[0].setAttribute("style", "cursor:pointer");
                                    filter_by_id[0].setAttribute("filter-accessible","true");
                                });
                            }

                                //console.log($('#instockToggler').prop('checked'));
                                if($('#instockToggler').prop('checked')){
                                    $('#filters_selected').append($('<div class="selected_filter">' +
                                        '<p class="tesst" id="deselected_filter_toggler" style="font-size: 10pt;' +
                                        'text-align: center;margin: auto;">'+"@lang('product.in_stock_button_name')"+'' +
                                        '<a class="deselect_filter" deselectid="deselected_filter_toggler" href=#>' +
                                        '<i class="far fa-times-circle" style="float: right;color: red;' +
                                        'text-align: center;"></i></a></p></div>'));
                                }
                                if($("option:selected", $('#storages')).val() !== '0'){
                                    $('#filters_selected').append($('<div class="selected_filter">' +
                                        '<p class="tesst" id="deselected_filter_storage" style="font-size: 10pt;' +
                                        'text-align: center;margin: auto;">'+
                                        $("option:selected", $('#storages')).text() +
                                        '<a class="deselect_filter" deselectid="deselected_filter_storage" href=#>' +
                                        '<i class="far fa-times-circle" style="float: right;color: red;' +
                                        'text-align: center;"></i></a></p></div>'));
                                }
                                if(!($('#new-checked').css("display") === 'none')){
                                    $('#filters_selected').append($('<div class="selected_filter">' +
                                        '<p class="tesst" id="deselected_filter_new" style="font-size: 10pt;' +
                                        'text-align: center;margin: auto;">'+"@lang('product.filters.new')"+'' +
                                        '<a class="deselect_filter" deselectid="deselected_filter_new" href=#>' +
                                        '<i class="far fa-times-circle" style="float: right;color: red;' +
                                        'text-align: center;"></i></a></p></div>'));
                                }
                                if(!($('#hits-checked').css("display") === 'none')){
                                    $('#filters_selected').append($('<div class="selected_filter">' +
                                        '<p class="tesst" id="deselected_filter_hits" style="font-size: 10pt;' +
                                        'text-align: center;margin: auto;">'+"@lang('product.filters.hits')"+'' +
                                        '<a class="deselect_filter" deselectid="deselected_filter_hits" href=#>' +
                                        '<i class="far fa-times-circle" style="float: right;color: red;' +
                                        'text-align: center;"></i></a></p></div>'));
                                }
                                if(!($('#discount-checked').css("display") === 'none')){
                                    $('#filters_selected').append($('<div class="selected_filter">' +
                                        '<p class="tesst" id="deselected_filter_discount" style="font-size: 10pt;' +
                                        'text-align: center;margin: auto;">'+"@lang('product.filters.discount')"+'' +
                                        '<a class="deselect_filter" deselectid="deselected_filter_discount" href=#>' +
                                        '<i class="far fa-times-circle" style="float: right;color: red;' +
                                        'text-align: center;"></i></a></p></div>'));
                                }

                                let loaded_filter_categories = document.getElementById('reload').textContent.split(",");
                                if(loaded_filter_categories.length > 0 || loaded_filter_categories[0] !== ""){
                                    $.each(loaded_filter_categories, function( index, value ) {
                                        let id = value;
                                        let name = document.getElementById(value+'_anchor');
                                        if(name){
                                        name = name.innerText;
                                        if (name.length > 35){
                                            name = name.substr(0,35)+'...';
                                        }
                                            $('#filters_selected').append($('<div class="selected_filter">' +
                                                '<p class="tesst" id="deselected_filter_categories_'+id+'" style="font-size: 10pt;' +
                                                'text-align: center;margin: auto;">'+name+'' +
                                                '<a class="deselect_filter" deselectid="deselected_filter_categories_'+id+'" href=#>' +
                                                '<i class="far fa-times-circle" style="float: right;' +
                                                'color: red;' +
                                                'text-align: center;"></i></a></p></div>'));
                                        }
                                    });
                                }

                                $.each(resp['checked'], function( index, value ) {
                                    if(value.length > 35){
                                        value = value.substr(0,35)+'...';
                                    }
                                    let filter_by_id = $("[option_id="+index+"]");
                                    filter_by_id[0].setAttribute("style", "cursor:pointer");
                                    filter_by_id[0].setAttribute("filter-accessible","true");
                                    $('#filters_selected').append($('<div class="selected_filter">' +
                                        '<p class="tesst" id="deselected_filter_'+index+'" style="font-size: 10pt;' +
                                        'text-align: center;margin: auto;">'+value+'' +
                                        '<a class="deselect_filter" deselectid="deselected_filter_'+index+'" href=#>' +
                                        '<i class="far fa-times-circle" style="float: right;color: red;' +
                                        'text-align: center;"></i></a></p></div>'));
                                });


                            // if((resp.available) && !(resp['checked'])){
                            //     $.each(all_filters,function (key,value) {
                            //         value.setAttribute("style", "color:black;cursor:pointer");
                            //         value.setAttribute("filter-accessible","true");
                            //     });
                            // }

                            if((resp.available.length === 0) && (resp['checked'].length === 0)){
                                $.each(all_filters,function (key,value) {
                                    value.setAttribute("style", "cursor:pointer");
                                    value.setAttribute("filter-accessible","true");
                                });
                            }

                            setFiltersQuantity();
                            window.loading = 0;
                        },
                        error:  function(xhr, str){
                            console.log(xhr);
                        }
                    });

                    $.each(all_filters,function (key,value) {
                        value.setAttribute("style", "color:grey;cursor:progress");
                        // value.setAttribute("filter-accessible","false");
                    });
                }

            }

            function setFiltersQuantity(){
                let accessible = $("[filter-accessible=true]");
                let accessible_per_filter = Object();
                $.each(accessible,function(key,value){
                    let option_filter_name = value.getAttribute("option_filter_name");
                    // if(value.getAttribute("filter-selected")==='false'){
                        if (typeof accessible_per_filter[option_filter_name] !== 'undefined') {
                            accessible_per_filter[option_filter_name] = accessible_per_filter[option_filter_name] + 1;
                        }else{
                            accessible_per_filter[option_filter_name] = 1;
                        }
                    //}
                });

                $.each(accessible_per_filter,function(key,value){
                    let header = $("[filter_name='"+key+"']")[0];
                    if(value>1){
                        header.innerText = key + ' ('+value+')';
                    }else{
                        header.innerText = key;
                    }

                });
            }

            $( "#optionfilters" ).accordion({
                collapsible: true,
                active: false,
                heightStyle: "content",
                content: '.filter'
            });

            $('#select_all_products').change(function() {
                if($('#select_all_products').prop('checked')){
                    window.products = [];
                    $(".intable").each(function(){
                        $(this).prop('checked', true);
                        products.push($(this).prop('id').slice(8));
                    });
                }
                else{
                    $(".intable").each(function(){
                        $(this).prop('checked', false);
                        document.getElementById('selected_products').innerText = '';
                    });
                    window.products = [];
                }
            });

            $('#storages').on('change', function (e) {
                    initOptionFilters();
                    jsTreetoDatatable()

            });


            $('#instockToggler').click(function() {

                    initOptionFilters();
                    jsTreetoDatatable()

            });

            $('#mass_actions').on('change', function (e) {
                let option = $("option:selected", $('#mass_actions')).val();
                window.products = [];
                if(option === 'wishlist'){
                    $(".intable").each(function(){
                        if($(this).prop('checked')){
                            window.products.push($(this).prop('id').slice(8));
                        }
                    });

                    if(window.products.length>0) {
                        $('#products_wishlist').val(window.products);
                        $('#mass_actions option[value="0"]').prop('selected',true);
                        $('#modal-wishlist').modal('show');
                    }

                }
                if(option === 'order'){
                    // $('#modal-order_multiple').modal('show');

                    $(".intable").each(function(){
                        if($(this).prop('checked')){
                            window.products.push($(this).prop('id').slice(8));
                        }
                    });

                    if(window.products.length>0){
                        let multiple_select = document.getElementById("products_selector");
                        while (multiple_select.firstChild) {
                            multiple_select.removeChild(multiple_select.firstChild);
                        }

                        if(document.getElementById("multiple_input_div")){
                            multiple_input_div.remove();
                        }



                        //let map = $('.data-product_name');
                        let map = $('.source');
                        window.multiple_order_map = [];
                        $.each(map,function (key,value) {
                            let id = value.attributes['data-product'].value;
                            if(window.products.indexOf(id)!==-1){
                                multiple_order_map.push(id+'_:_'+value.attributes['data-product_name'].value
                                    +'_:_'+value.attributes['data-storage'].value
                                    +'_:_'+value.attributes['data-storage_min'].value
                                    +'_:_'+value.attributes['data-storage_max'].value
                                );
                            }
                        });

                        $('#append_to').append($('<div id="multiple_input_div"></div>'));
                        $.each(multiple_order_map, function(key, value) {
                            $('#products_selector')
                                .append($("<option></option>")
                                    .attr("value", value.split('_:_')[0])
                                    .text(value.split('_:_')[1])
                                    .attr("data-storage", value.split('_:_')[2])
                                    .attr("data-storage_min", value.split('_:_')[3])
                                    .attr("data-storage_max", value.split('_:_')[4])
                                );

                            if(key !== 0){
                                $('#multiple_input_div').append($("" +
                                    //"<label>" + "@lang('product.quantity_order_request')" + multiple_order_map[0].split('_:_')[3] +"</label>"+
                                    '<input id="multiple_input_'+multiple_order_map[key].split('_:_')[0]+'" type="number" ' +
                                    'name="id_'+multiple_order_map[key].split('_:_')[0]+
                                    ':storageid_'+multiple_order_map[key].split('_:_')[2]+'" '+
                                    'class="form-control m-b-5 multipleorderinput" placeholder="@lang("product.quantity_order_request")"'+
                                    'value ="'+multiple_order_map[key].split('_:_')[3]+'" '+
                                    'min="'+multiple_order_map[key].split('_:_')[3]+'" '+
                                    'max="'+multiple_order_map[key].split('_:_')[4]+'" '+ 'style="display:none"'+'>'+
                                    ""));
                            }
                            else{
                                $('#multiple_input_div').append($("" +
                                    //"<label>" + "@lang('product.quantity_order_request')" + multiple_order_map[0].split('_:_')[3] +"</label>"+
                                    '<input id="multiple_input_'+multiple_order_map[key].split('_:_')[0]+'" type="number" ' +
                                    'name="id_'+multiple_order_map[key].split('_:_')[0]+
                                    ':storageid_'+multiple_order_map[key].split('_:_')[2]+'" '+
                                    'class="form-control m-b-5 multipleorderinput" placeholder="@lang("product.quantity_order_request")"'+
                                    'value ="'+multiple_order_map[key].split('_:_')[3]+'" '+
                                    'min="'+multiple_order_map[key].split('_:_')[3]+'" '+
                                    'max="'+multiple_order_map[key].split('_:_')[4]+'" '+'>'+
                                    ""));

                            }
                        });


                        $("#modal-order_multiple").modal('show');
                    }
                    $('#mass_actions option[value="0"]').prop('selected',true);
                }


            });

            $('#products_selector').on('change',function(){
                let id = this.options[this.selectedIndex].attributes['value'].value;
                let inputs = $('.multipleorderinput');
                $.each(inputs,function(key, value){
                    $('#'+value.attributes['id'].value).hide();
                });
                $('#multiple_input_'+id).show();
                // $('#multiple_input').attr('value', selector.attributes['data-storage_min'].value);
            });

            $('#form_add_order_multiple').submit(function (e) {
                e.preventDefault();

                let form = $(this);

                let route = '{{route('orders')}}/add-to-order-multiple';
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "GET",
                    url: route,
                    data: form.serialize(),
                    success: function(resp)
                    {
                        if(resp == "ok"){

                            $('#new_wishlist_name').val('');

                            $.gritter.add({
                                title: '@lang('wishlist.modal_success')',
                            });
                        }
                    },
                    error:  function(xhr, str){
                        console.log(xhr);
                    }

                });
                $('#modal-order_multiple').modal('hide');
                return false;
            });


            function jsTreetoDatatable(){
                let collection = document.getElementsByClassName("jstree-node");
                //let arr = [].slice.call(collection);
                let loaded = [];
                for (let i = 0; i < collection.length; i++) {
                    if(collection[i].getAttribute('aria-selected') == 'true'){
                        loaded.push(collection[i].getAttribute('id'));
                    }
                    loaded = loaded.filter(function(value,index,self){
                        return self.indexOf(value) === index;
                    });
                }
                var node = document.getElementById('reload');
                node.textContent = loaded.toString();
                // console.log(node.textContent);
                window.table.ajax.reload();
            }

            $('#modal-get_price').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var modal = $(this);
                modal.find('.product_id').val(button.data('product_id'));
            })

            $('#modal-wishlist').on('show.bs.modal', function (event) {
                if(!$('#products_wishlist').val()){
                    var button = $(event.relatedTarget);
                    var modal = $(this);
                    modal.find('.product_id').val(button.data('product'));
                }
            });

            $('#wishlist').change(function (e) {
                if($(this).val() == 0){
                    $('#new_wishlist_name').parent().show();
                    $('#new_wishlist_name').attr('required','required');
                }else{
                    $('#new_wishlist_name').parent().hide();
                    $('#new_wishlist_name').removeAttr('required');
                }
            });

            $('#modal-order').on('show.bs.modal', function (event) {
                if(!$('#get_price_product_id').val()){
                    var button = $(event.relatedTarget);
                    var modal = $(this);
                    modal.find('.product-name').text(button.data('product_name'));
                    modal.find('.product_id').val(button.data('product'));
                    modal.find('.storage_id').val(button.data('storage'));
                    modal.find('.order-storage-amount').text(button.data('storage_max'));
                    var quantity = modal.find('input[name="quantity"]');
                    quantity.val(button.data('storage_min'));
                    quantity.attr('min',button.data('storage_min'));
                    quantity.attr('step',button.data('storage_min'));
                    quantity.attr('data-max',button.data('storage_max'));

                    var quantity_request = modal.find('input[name="quantity_request"]');
                    quantity_request.val(0);
                    quantity_request.attr('min',0);
                    quantity_request.attr('step',button.data('storage_min'));

                    $('.storage-limit-info').hide();
                    $('.storage-limit-request').hide();
                    $('input[name="quantity_request"]').change();
                }
            });

            $('input[name="quantity"]').change(function (e) {
                e.preventDefault();
                if($(this).val() > $(this).data('max')){
                    var request =  $(this).val() - $(this).data('max');
                    $(this).val($(this).data('max'));
                    $('input[name="quantity_request"]').val(request);
                    $('.storage-limit-info').show();
                    $('.storage-limit-request').show();
                    $('input[name="quantity_request"]').change();
                }
            });

            $('input[name="quantity_request"]').change(function (e) {
                e.preventDefault();
                if($(this).val() > 0){
                    $('.btn-add-order').text($('.btn-add-order').data('btn_order_request'));
                }else{
                    $('.btn-add-order').text($('.btn-add-order').data('btn_order'));
                }
            });


            $('#form_add_catalog').submit(function (e) {
                e.preventDefault();

                $('#modal-wishlist').modal('hide');

                var form = $(this);
                let list_id = $('#wishlist').val();

                var route = '{{route('catalogs')}}/add-to-catalog/'+list_id;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "GET",
                    url: route,
                    data: form.serialize(),
                    success: function(resp)
                    {
                        if(resp == "ok"){

                            $('#new_wishlist_name').val('');

                            $.gritter.add({
                                title: '@lang('wishlist.modal_success')',
                            });
                        }
                    },
                    error:  function(xhr, str){
                        console.log(xhr);
                    }

                });
                $('#products_wishlist').val('');
                return false;
            })
            $('#form_add_order').submit(function (e) {
                e.preventDefault();

                $('#modal-order').modal('hide');

                var form = $(this);
                var order_id = $('#order_id').val();
                var route = '{{route('orders')}}/add-to-order/'+order_id;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: route,
                    data: form.serialize(),
                    success: function(resp)
                    {
                        if(resp == "ok"){
                            $.gritter.add({
                                title: '@lang('order.modal_success')',
                            });
                            if(order_id == 0){
                                document.location.reload(true);
                            }
                            //window.table.ajax.reload();
                        }
                    },
                    error:  function(xhr, str){
                        console.log(xhr);
                    }
                });

                return false;
            });
            $('#form_get_price').submit(function (e) {
                e.preventDefault();
                $('#modal-get_price').modal('hide');
                var product_id = $('#get_price_product_id').val();
                var form = $(this);

                var route = '{{route('products')}}/'+product_id+'/get-price/';

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "GET",
                    url: route,
                    data: form.serialize(),
                    success: function(resp)
                    {
                        if(resp == "ok"){
                            $.gritter.add({
                                title: '@lang('product.get_price_success')',
                            });
                        }
                    },
                    error:  function(xhr, str){
                        console.log(xhr);
                    }
                });

                return false;
            });

            $('#new').click(function(e){
                if($('#new-checked').css("display") === 'none'){
                    $('#new-checked').css("display","");
                }
                else{
                    $('#new-checked').css("display","none");
                }
                initOptionFilters();
                jsTreetoDatatable();
            });
            $('#hits').click(function(e){
                if($('#hits-checked').css("display") === 'none'){
                    $('#hits-checked').css("display","");
                }
                else{
                    $('#hits-checked').css("display","none");
                }
                initOptionFilters();
                jsTreetoDatatable();
            });
            $('#discount').click(function(e){
                if($('#discount-checked').css("display") === 'none'){
                    $('#discount-checked').css("display","");
                }
                else{
                    $('#discount-checked').css("display","none");
                }
                initOptionFilters();
                jsTreetoDatatable();
            });

            $('.filter_with_options').click(function(){
                    if(window.loading === 0 && ($(this).attr('filter-accessible') == 'true')){
                        if($(this).attr('filter-selected') === 'true'){
                            $(this).attr('filter-selected', 'false');
                            $("#filter-checked_"+$(this).attr('option_id')).css("display","none");
                        }
                        else{
                            $(this).attr('filter-selected', 'true');
                            $("#filter-checked_"+$(this).attr('option_id')).css("display","");
                        }
                        initOptionFilters();
                        jsTreetoDatatable();
                    }
            });
            // $('.deselect_filter').click(function(e){
            //     alert('sdsd');
            // });
            $(document).on("click", ".deselect_filter", function(e) {
                let id = $(this)[0].getAttribute("deselectid");
                if(id === 'deselected_filter_toggler'){
                    if($('#instockToggler').prop('checked') === true){
                        $('#instockToggler').prop('checked',false);
                    }else{
                        $('#instockToggler').prop('checked',true);

                    }
                    var myobj = document.getElementById(id);
                }
                else if (id === 'deselected_filter_storage'){
                    $('#storages option[value=0]').prop('selected', true);
                    var myobj = document.getElementById(id);
                }
                else if(id === 'deselected_filter_new'){
                    if($('#new-checked').css("display") === 'none'){
                        $('#new-checked').css("display","");
                    }
                    else{
                        $('#new-checked').css("display","none");
                    }
                    var myobj = document.getElementById(id);
                }
                else if(id === 'deselected_filter_hits'){
                    if($('#hits-checked').css("display") === 'none'){
                        $('#hits-checked').css("display","");
                    }
                    else{
                        $('#hits-checked').css("display","none");
                    }
                    var myobj = document.getElementById(id);
                }
                else if(id === 'deselected_filter_discount'){
                    if($('#discount-checked').css("display") === 'none'){
                        $('#discount-checked').css("display","");
                    }
                    else{
                        $('#discount-checked').css("display","none");
                    }
                    var myobj = document.getElementById(id);
                }
                else if($(this)[0].getAttribute("deselectid").split('deselected_filter_categories_')[1]){
                    let id = $(this)[0].getAttribute("deselectid").split('deselected_filter_categories_')[1];

                    $('#jstree').jstree().deselect_node(id, true);

                    var myobj = document.getElementById('deselected_filter_categories_'+id);
                }
                else{
                    let id = $(this)[0].getAttribute("deselectid").split('deselected_filter_')[1];
                    let all_filters = $(".filter_with_options");
                    $.each(all_filters,function (key,value) {
                        if(value.getAttribute("option_id")===id){
                            value.setAttribute("style", "cursor:pointer");
                            value.setAttribute("filter-selected","false");
                            return false;
                        }
                    });
                    var myobj = document.getElementById('deselected_filter_'+id);
                }
                myobj.remove();
                initOptionFilters();
                jsTreetoDatatable();

            });

        });
    </script>
    <script>
        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.display === "block") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        }
    </script>
    <style>
        .ui-accordion .ui-accordion-content {
            padding:0px !important;
        }
        .jstree{
            padding:0px;
        }
        .jstree-default a {
            white-space:normal !important; height: auto;
        }
        .jstree-anchor {
            height: auto !important;
            width: 90%;
        }
        .jstree-default li > ins {
            vertical-align:top;
        }
        .jstree-leaf {
            height: auto;
        }
        .jstree-leaf a{
            height: auto !important;
        }
        .checkbox.checkbox-css label{
            padding:8px;
            margin-left:6px;

        }

        .custom-control-label {
            margin-top:10px;
            margin-left:12px;
            font-size: 1rem;
            line-height: 1.0;
        }

        .right-align{
            float: right;
        }

        .ui-accordion .ui-accordion-content {padding:10px }
        .ui-accordion-header {
            height: 60px;
        }
        .filter_with_options{
            padding-left: 14px;
        }

        .selected_filter{
            border: 1px solid #c5c5c5;
            background: #f6f6f6;
            font-weight: 400;
            color: #454545;

        }
    </style>

@endpush
