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
    <link href="/assets/css/default/table-ptoduct.css" rel="stylesheet" />
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

    <div id="filters_selected"></div>

    <header class="nav">
        <div id="groupsToggle" onclick="$('#filterGroups').addClass('show')"><i class="fas fa-th-large"></i> Категорія</div>
        <div><i class="fa fa-filter"></i> Властивості</div>
        <div>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="instockToggler">
                <label class="custom-control-label" for="instockToggler"> @lang('product.in_stock_button_name')</label>
            </div>
        </div>
        <div>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="newToggler">
                <label class="custom-control-label" for="newToggler"> @lang('product.filters.new')</label>
            </div>
        </div>
        <div>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="hitsToggler">
                <label class="custom-control-label" for="hitsToggler"> @lang('product.filters.hits')</label>
            </div>
        </div>
        <div>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="discountToggler">
                <label class="custom-control-label" for="discountToggler"> @lang('product.filters.discount')</label>
            </div>
        </div>
        @if(isset($terms))
            <div class="select">
                <select class="form-control selectpicker" id="storages" data-style="btn-white" multiple="multiple">
                    <!-- <option value="0">@lang('product.select_term')</option> -->
                    @foreach($terms as $key => $term)
                      <option value="{{$key}}" id="termToggler-{{$key}}">{!! $term !!}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </header>

    <table id="data-table-buttons" class="table-responsive table-striped table-bordered table-td-valign-middle">
        <thead>
        <tr>
            <th colspan="4" class="text-nowrap">@lang('product.table_header_info')</th>
            <th colspan="3" class="text-nowrap">@lang('product.table_header_price_per_100')</th>
            <th rowspan="2" class="text-nowrap" >@lang('product.table_header_storage')</th>
            <th colspan="4" class="text-nowrap">@lang('product.table_header_calc_price')</th>
        </tr>
        <tr>
            <th></th>
            <th>
                <div class="checkbox checkbox-css">
                    <input type="checkbox" id="select_all_products">
                    <label for="select_all_products"> </label>
                </div>
            </th>
            <th data-orderable="false">@lang('product.table_header_photo')</th>
            <th class="text-nowrap">@lang('product.table_header_name/article')</th>
            <th class="text-nowrap">@lang('product.table_header_price')</th>
            <th id="price_porog_1" class="text-nowrap">@lang('product.table_header_price_porog_1')</th>
            <th id="price_porog_2" class="text-nowrap">@lang('product.table_header_price_porog_2')</th>

            <th>
                @lang('product.table_header_quantity')
            </th>
            <th>
                @lang('product.table_header_package_weight')
            </th>
            <th>
                @lang('product.table_header_sum_w_taxes')
            </th>
            <th ></th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>


    <div id="loading"></div>
    <div id="filterGroups" class="myModal">
        <article>
            <i class="far fa-times-circle" onclick="$('#filterGroups').removeClass('show'); initFilter()"></i>
            <h2>Оберіть категорії</h2>
            <div id="jstreeGroups"></div>
            <button class="btn btn-success m-t-5" onclick="$('#filterGroups').removeClass('show'); initFilter()">Застосувати</button>
        </article>
    </div>

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
    <!-- <script src="/assets/plugins/select2/dist/js/vue.min.js"></script> -->
    <script>

        /* const wrapTable = new Vue({
            el: "#data-table-buttons",
            data: {
                products: []
            },
            computed: {
                cells: () => {
                    let row = document.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                    let tds = Array.from(row).map(td => Array.from(td.children))
                    this.products = tds;
                    return tds;
                },
                checked: () => {
                    let a = this.products.filter(td => td[0].getElementsByTagName('div').getElementsByTagName('input').checked);
                    return a;
                }
            }
        }) */ 

        jQuery(function($) {

            $('#loading').hide();

            // storages
            document.getElementsByClassName('btn dropdown-toggle btn-white')[0].setAttribute('title','@lang('product.select_term')');
            document.getElementsByClassName('btn dropdown-toggle btn-white')[0].children[0].children[0].children[0].style.color = "#4e5c68";
            document.getElementsByClassName('btn dropdown-toggle btn-white')[0].children[0].children[0].children[0].innerText = '@lang('product.select_term')';

            var loaded_nodes = [];
            $("#jstreeGroups")
                .jstree({
                    "plugins": ["wholerow", "checkbox", "json_data"],
                    "core": {
                        "check_callback": true,
                        "data": {
                            url: "{!! @route('getnode', ['id' => 0]) !!}",
                            contentType: "application/json; charset=utf-8",
                        },
                    }
                })
                .on('before_open.jstree', function(e, data) {
                    if (!loaded_nodes.includes(data.node.id)) {

                        var url = "{!! @route('getnode', ['id' => 0]) !!}";
                        url = url.substring(0, url.length - 1) + data.node.id;
                        $.ajax({
                            method: "GET",
                            url: url,
                            success: function(resp) {
                                $('#jstreeGroups').jstree().delete_node($('#jstreeGroups').jstree().get_node(data.node.id).children);
                                var child = resp;
                                child.forEach(function(index, item) {
                                    $('#jstreeGroups').jstree().create_node(data.node.id, index, "last", function() { });
                                    $('#jstreeGroups').jstree().deselect_node(index.id, true);
                                });
                            },
                            error: function(xhr, str) {
                                console.log(xhr);
                            }
                        });
                        loaded_nodes.push(data.node.id);
                    }
                });

            window.table =
                $('#data-table-buttons').DataTable({
                     scrollY: "100vh",
                    //  fixedColumns: true,
                    deferRender: true,
                    //  scroller: true,
                    "language": {
                        "url": "@lang('table.localization_link')",
                    },
                    "scrollX": true,
                    "pageLength": 25,
                    "autoWidth": true,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "{!! route('products.all_ajax') !!}",
                        "data": {
                            "categories": function () {
                                return $.jstree.reference('jstreeGroups').get_checked();
                            },
                            "instock": function() {
                                return $('#instockToggler').prop('checked');
                            },
                            "new": function() {
                                return $('#newToggler').prop('checked');
                            },
                            "hits": function() {
                                return $('#hitsToggler').prop('checked');
                            },
                            "discount": function() {
                                return $('#discountToggler').prop('checked');
                            },
                            "term": function() {
                                var optionSelected = $("option:selected", $('#storages')).val();
                                if(optionSelected !== 'undefined') {
                                    return optionSelected;
                                }
                            },

                            // "filter_with_options": function() {
                            //     let filter_selected_map = $("[filter-selected=true]");
                            //     filter_selected_ids = Array();
                            //     $.each(filter_selected_map, function(key, value) {
                            //         if (value.attributes['filter-selected'].value === 'true') {
                            //             let option_id = value.attributes['option_id'].value;
                            //             //let option_name = value.attributes['option_name'].value;
                            //             filter_selected_ids.push(option_id);
                            //             //filter_selected_ids.option_id = option_name;
                            //         }
                            //     });
                            //     //console.log('filter_with_options: ' + filter_selected_ids)
                            //     if(filter_selected_map.length){
                            //       return filter_selected_ids;
                            //     }

                            // }
                        },
                        complete: function() {
                            $('#loading').hide();
                        }
                    },
                    "order": [
                        [3, "asc"]
                    ],
                    "columns": [
                        {
                           data: 'id',
                           "visible": false,
                           "searchable": false,
                        },
                        {
                            "orderable": false,
                            data: 'check_html',
                            className: "datatable_checkbox_class"
                        },
                        {
                            "orderable": false,
                            data: 'image_html',
                            className: "datatable_image_class"
                        },
                        {
                            "orderable": true,
                            data: 'name_article_html',
                            className: "datatable_namearticle_class"
                        },
                        // {
                        //     data: 'retail_price',
                        //     className: "datatable_retailprice_class"
                        // },
                        // {
                        //     data: 'user_price',
                        //     className: "datatable_userprice_class"
                        // },
                        {
                          "orderable": false,
                          data: 'retail_user_prices',
                          className: "datatable_userprice_class"
                        },
                        {
                            "orderable": false,
                            data: 'html_limit_1',
                            className: "datatable_limit1_class"
                        },
                        {
                            "orderable": false,
                            data: 'html_limit_2',
                            className: "datatable_limit2_class"
                        },
                        {
                            data: 'storage_html',
                            "orderable": false,
                            className: "datatable_storage_class"
                        },
                        {
                            data: 'calc_quantity',
                            "orderable": false,
                            className: "datatable_quantity_class"
                        },
                        {
                            data: 'package_weight',
                            "orderable": false,
                            className: "datatable_weight_class"
                        },
                        {
                            data: 'sum_w_taxes',
                            "orderable": false,
                            className: "datatable_sum_class"
                        },
                        {
                            data: 'actions',
                            "orderable": false,
                            className: "datatable_actions_class"
                        },
                    ],
                    "preUpload": function(settings, json) {
                        $('#select_all_products').prop('checked', false);
                    }
                });

            
        });
    
        $('header.nav input, header.nav select').change(initFilter);

        function initFilter() {
            $('#loading').show();

            $('#filters_selected').empty();
            let filter__nodes = Object();

            let groups = $.jstree.reference('jstreeGroups').get_checked(true);
            for (var i in groups) {
                if(Number.parseInt(groups[i].id))
                    filter__nodes['group-' + groups[i].id] = 'Категорія: ' + groups[i].text;
            }

            if(Object.keys(filter__nodes).length > 0)
                $('#groupsToggle').addClass('active');
            else
                $('#groupsToggle').removeClass('active');

            if($('#instockToggler').prop('checked'))
                filter__nodes['instock'] = '@lang('product.in_stock_button_name')';

            if($('#newToggler').prop('checked'))
                filter__nodes['new'] = '@lang('product.filters.new')';

            if($('#hitsToggler').prop('checked'))
                filter__nodes['hits'] = '@lang('product.filters.hits')';

            if($('#discountToggler').prop('checked'))
                filter__nodes['discount'] = '@lang('product.filters.discount')';

            $("option:selected", $('#storages')).each(function () {
                filter__nodes[this.id] = 'Термін: ' + this.innerText;
            });

            

            // let nodes = Object.assign(filter__nodes, filter__group_nodes);
            for (var key in filter__nodes) {
                if(filter__nodes[key])
                    $('<div/>', {
                        'data-deselect': key,
                        "class": 'selected_filter',
                        html: filter__nodes[key] + ' <i class="far fa-times-circle"></i>'
                    }).appendTo('#filters_selected');
            }

            $('#filters_selected i').click(deselectFilter)

            window.table.ajax.reload();
        }

        function deselectFilter() {
            let id = this.closest('div').dataset.deselect;

            let checkBox = ['instock', 'new', 'hits', 'discount'];
            if(checkBox.includes(id))
            {
                $('#' + id + 'Toggler').prop('checked', false);
            }
            else
            {
                let key = id.split('-');
                if(key[0] == 'group')
                {
                    $.jstree.reference('jstreeGroups').deselect_node(key[1]);
                }
                else
                {
                    $('#'+id).prop('selected', false);

                    let labels = '';
                    $("option:selected", $('#storages')).each(function () {
                        if(labels !== '') {
                            labels = labels + ', ' + this.innerText;
                        }
                        else {
                            labels = this.innerText;
                        }
                    });

                    if(labels === '') {
                        labels = '@lang('product.select_term')';
                    }
                    document.getElementsByClassName('btn dropdown-toggle btn-white')[0].setAttribute('title',labels);
                    document.getElementsByClassName('btn dropdown-toggle btn-white')[0].children[0].children[0].children[0].innerText = labels;
                }
            }
            
            initFilter();
        }
    </script>

    <style>
        #filters_selected i { color: red; cursor: pointer }
        header.nav {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        header.nav > div {
            padding: 7px 25px;
            border-left: 1px solid #d5dbe0;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        header.nav > div:first-child { border-left: none }
        header.nav > div.active { color: #2daae1 }
        header.nav > div.select { width: 250px }
        header.nav > div:not(.select):hover { background-color: #01aadf; color: #fff }
        header.nav > div > i { font-size: 20px; margin-right: 10px }

        .checkbox.checkbox-css label {
            padding: 8px;
            margin-left: 6px;
        }

        .myModal.show { display: flex }
        .myModal {
            display: none;
            justify-content: center;
            align-items: center;
            position: fixed;
            z-index: 1100;
            background-color: rgb(0 0 0 / 70%);
            left: 0;
            bottom: 0;
            right: 0;
            top: 0;
        }
        .myModal article {
            background: #fff;
            padding: 25px;
            width: 500px;
            height: auto;
            max-height: 80%;
            overflow-y: auto;
        }
        .myModal article > i {
            float: right;
            font-size: 25px;
            color: red;
            cursor: pointer;
        }
        #loading {
            display: block;
            position: fixed;
            z-index: 10001;
            background-image: url(https://dinmark.com.ua/style/images/icon-loading.gif);
            background-color: #000;
            background-size: 80px;
            opacity: 0.7;
            background-repeat: no-repeat;
            background-position: center;
            left: 0;
            bottom: 0;
            right: 0;
            top: 0;
        }
    </style>

@endpush
