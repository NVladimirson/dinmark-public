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
    <div id="selected_products" style="display:none"></div>

    <header class="nav">
        <div id="groupsToggle" onclick="$('#filterGroups').addClass('show')"><i class="fas fa-th-large"></i> Категорія</div>
        <div id="optionsToggle"><i class="fa fa-filter"></i> Властивості</div>
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

    <div id="actionsMultiProducts">
        <select class="form-control" id="mass_actions" data-size="2" data-style="btn-white">
            <option value="0">@lang('product.mass_actions.select')</option>
            <option value="wishlist">@lang('product.mass_actions.add-to-wishlist')</option>
            <option value="order">@lang('product.mass_actions.add-to-order')</option>
        </select>
    </div>

    <div id="loading"></div>
    <div id="filterGroups" class="myModal">
        <article>
            <i class="far fa-times-circle submit"></i>
            <h2>@lang('product.all_categories_name')</h2>
            <div id="jstreeGroups"></div>
            <button class="btn btn-success m-t-5 submit">Застосувати</button>
        </article>
    </div>
    <div id="filterOptions" class="myModal">
        <article>
            <i class="far fa-times-circle submit" onclick="$('#filterOptions').removeClass('show');"></i>
            <h2>@lang('product.filters-with-properties')</h2>
            <div id="optionfilters">Підбираємо...</div>
            <button class="btn btn-success m-t-5 submit">Застосувати</button>
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
    <script src="/assets/plugins/select2/dist/js/vue.min.js"></script>
    <script>
        let get__products = get__FilterOptions = true;

        const wrapTable = new Vue({
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
        });

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
                .on("changed.jstree", function (e, data) {
                    get__products = get__FilterOptions = true;
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

            $('#optionsToggle').click(function () {
                if(get__FilterOptions)
                    getFilterOptions();
                $('#filterOptions').addClass('show')
            });

            $('.myModal .submit').click(function () {
                $(this).closest('.myModal').removeClass('show');
                if(get__products)
                    initFilter();
            });

            $("#optionfilters").accordion({
                collapsible: true,
                active: false,
                heightStyle: "content"
            });

            $('header.nav input, header.nav select').change(initFilter);

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
                            "filter_with_options": function() {
                                let filter_selected_map = $(".filterElem[filter-selected=true]");
                                if(filter_selected_map.length)
                                {
                                    filter_selected_ids = Array();
                                    $.each(filter_selected_map, function(key, value) {
                                        if (value.attributes['filter-selected'].value === 'true') {
                                            let option_id = value.attributes['option_id'].value;
                                            //let option_name = value.attributes['option_name'].value;
                                            filter_selected_ids.push(option_id);
                                            //filter_selected_ids.option_id = option_name;
                                        }
                                    });
                                    //console.log('filter_with_options: ' + filter_selected_ids)
                                
                                  return filter_selected_ids;
                                }
                                return '';
                            }
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

            
            $('#data-table-buttons').on('draw.dt', function() {
                const arr = Array.from(document.querySelectorAll('.datatable_actions_class a'))
                for (let i = 0; i < arr.length; i++) {
                    if (i % 3 === 0) {
                        arr[i].setAttribute('title', "@lang('product.show_card_product')")
                    }else if(i % 2 === 0) {
                        arr[i].setAttribute('title', "@lang('product.add_to_wish_list')")
                    }else {
                        arr[i].setAttribute('title', "@lang('product.add_to_order')")
                    }
                }
                $('#select_all_products').prop('checked', false);

                $('.intable').change(function(event) {
                    if($(".intable:checked").length > 0)
                        $('#actionsMultiProducts').css('display', 'flex');
                    else
                        $('#actionsMultiProducts').css('display', 'none');
                });
            });

            $('#select_all_products').change(function() {
                if ($('#select_all_products').prop('checked')) {
                    window.products = [];
                    $(".intable").each(function() {
                        $(this).prop('checked', true);
                        products.push($(this).prop('id').slice(8));
                    });
                    $('#actionsMultiProducts').css('display', 'flex');
                } else {
                    $(".intable").each(function() {
                        $(this).prop('checked', false);
                        document.getElementById('selected_products').innerText = '';
                    });
                    window.products = [];
                    $('#actionsMultiProducts').css('display', 'none');
                }
            });

            $('#products_selector').on('change', function() {
                let id = this.options[this.selectedIndex].attributes['value'].value;
                let inputs = document.getElementById("multiple_input_div").children;
                let name_images = document.getElementById("name_image_container").children;

                $.each(inputs, function(key, value) {
                    $('#'+value.attributes['id'].value).hide();
                });
                $.each(name_images, function(key, value) {
                    $('#'+value.attributes['id'].value).hide();
                });
                $('#wrapper_name_image_'+id).show();
                $('#wrapper_inputs_'+id).show();
            });
        });

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

            let filter_selected_map = $(".filterElem[filter-selected=true]");
            if(filter_selected_map.length)
            {
                $.each(filter_selected_map, function(key, value) {
                    if (value.attributes['filter-selected'].value === 'true') {
                        filter__nodes[value.id] = value.title;
                    }
                });
                $('#optionsToggle').addClass('active');
            }
            else
                $('#optionsToggle').removeClass('active');

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
            get__products = false;
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
                else if(key[0] == 'option')
                {
                    get__FilterOptions = true;
                    $('#' + id).attr('filter-selected', 'false');
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

        function getFilterOptions() {
            if(!get__FilterOptions)
                return true;

            $('#loading').show();

            $.ajax({
                method: "POST",
                // url: '{{route("products.optionfilters")}}',
                // url: '{{env('DINMARK_URL')}}api/products/filters',
                url: 'https://dinmark.com.ua/api/products/filters',
                data: {
                    filter_with_options: function () {
                        let filter_selected_map = $(".filterElem[filter-selected=true]");
                        if(filter_selected_map.length)
                        {
                            filter_selected_ids = Array();
                            $.each(filter_selected_map, function(key, value) {
                                if (value.attributes['filter-selected'].value === 'true') {
                                    let option_id = value.attributes['option_id'].value;
                                    filter_selected_ids.push(option_id);
                                }
                            });
                            return filter_selected_ids;
                        }
                        return '';
                    },
                    categories: function () {
                        return $.jstree.reference('jstreeGroups').get_checked();
                    }
                },
                success: function(filters) {
                    get__products = true;
                    get__FilterOptions = false;
                    $('#optionfilters').empty();

                    if(filters)
                    {
                        for(let i in filters)
                        {
                            $('<h3/>', { text: filters[i].name }).appendTo('#optionfilters');
                            let div = $('<div/>', { class: 'filter'});
                            for(v in filters[i].values)
                            {
                                $('<div/>', {
                                    id: 'option-' + filters[i].values[v].id,
                                    class: 'filterElem',
                                    title: filters[i].name + ': ' + filters[i].values[v].name,
                                    html: filters[i].values[v].img + ' <span>' + filters[i].values[v].name + '</span>',
                                    'option_id': filters[i].values[v].id,
                                    'filter-selected': filters[i].values[v].selected,
                                    click: function () {
                                        if($(this).attr('filter-selected') == 'true')
                                            $(this).attr('filter-selected', 'false');
                                        else
                                            $(this).attr('filter-selected', 'true');

                                        get__FilterOptions = true;
                                        getFilterOptions();
                                    }
                                }).appendTo(div);
                            }
                            div.appendTo('#optionfilters');
                        }

                        $("#optionfilters").accordion("refresh");
                    }

                    $('#loading').hide();
                },
                error: function(xhr, str) {
                    console.log(xhr);
                    $('#loading').hide();
                }
            });
        }

        function initCalc(obj){
            let optionselected = $("option:selected", obj);
            let product_id = obj.getAttribute('product_id');

            let quantityinput = $('#calc_quantity_'+product_id);
            let packageweight = $('#package_weight_'+product_id);
            let sumwithtaxes = $('#sum_w_taxes_'+product_id);

            let orderbutton = $('#to_order_button_'+product_id);
            let getpricebutton = $('#get_price_button_'+product_id);
            let storage_id = optionselected.val();
            if(storage_id!=='0'){
                let min = optionselected[0].getAttribute('package_min');
                let max = optionselected[0].getAttribute('package_max');

                if(max !== '0' && (max - min > 0)){
                  //console.log('> min: '+min+', max: '+max);
                  quantityinput[0].setAttribute('value',min);
                  quantityinput[0].setAttribute('min',min);
                  quantityinput[0].setAttribute('step',min);
                  quantityinput[0].setAttribute('max',max);
                  quantityinput[0].setAttribute('datamax',max);

                  quantityinput.toggle(true);

                  orderbutton.toggle(true);
                  getpricebutton.toggle(false);

                  packageweight[0].parentElement.setAttribute('style','display:auto');
                  sumwithtaxes[0].parentElement.setAttribute('style','display:auto');
                }
                else{
                  //console.log('min: '+min+', max: '+max);
                  quantityinput.toggle(false);

                  orderbutton.toggle(false);
                  getpricebutton.toggle(true);

                  packageweight[0].parentElement.setAttribute('style','display:none');
                  sumwithtaxes[0].parentElement.setAttribute('style','display:none');
                }

                $.ajax({
                    type: "GET",
                    data: {
                        product_id:product_id,
                        storage_id:storage_id,
                        amount:quantityinput[0].getAttribute('value')
                    },
                    url: "{!! @route('priceCalc') !!}",
                    success: function(msg){
                        let retail_user_price = document.getElementById('retail_user_price_'+product_id);
                        retail_user_price.children[1].innerText = msg['retail'];
                        retail_user_price.children[5].innerText = msg['user_price'];
                        // if(msg['oldprice'] !== '0'){
                        //   retail_user_price.children[3].children[0].show;
                        //   retail_user_price.children[3].children[0].innerText = msg['oldprice'];
                        // }


                        let package_weight = document.getElementById('package_weight_'+product_id);
                        package_weight.children[0].innerText = msg['multiplier'];
                        package_weight.children[2].innerText = msg['package'];
                        package_weight.children[4].innerText = msg['weight'];

                        let sum_w_taxes = document.getElementById('sum_w_taxes_'+product_id);
                        sum_w_taxes.children[0].innerText = msg['price'];

                        if(msg['limit_amount_quantity_2'] === '0' || msg['limit_amount_quantity_2'] === 0){
                            sum_w_taxes.children[2].innerText = '';
                            sum_w_taxes.children[3].innerText = '';

                            sum_w_taxes.children[0].setAttribute('style','');
                            sum_w_taxes.children[2].setAttribute('style','');
                            sum_w_taxes.children[3].setAttribute('style','');
                        }else{
                            sum_w_taxes.children[2].innerText = '-'+msg['discount'];
                            sum_w_taxes.children[3].innerText = msg['discountamount'];

                            sum_w_taxes.children[2].setAttribute('style','display:auto');
                            sum_w_taxes.children[3].setAttribute('style','display:auto');
                        }

                        if(msg['limit_amount_quantity_1'] === '0' || msg['limit_amount_quantity_1'] === 0){
                            let limit_1 = document.getElementById('limit_1_'+product_id);
                            limit_1.children[0].innerText = '';
                            limit_1.children[2].innerText = '-';
                        }else{
                            let limit_1 = document.getElementById('limit_1_'+product_id);
                            limit_1.children[0].innerText = msg['limit_amount_price_1'];
                            limit_1.children[2].innerText = '>'+msg['limit_amount_quantity_1'];
                        }

                        if(msg['limit_amount_quantity_2'] === '0' || msg['limit_amount_quantity_2'] === 0){
                            let limit_2 = document.getElementById('limit_2_'+product_id);
                            limit_2.children[0].innerText = '';
                            limit_2.children[2].innerText = '-';
                        }else{
                            let limit_2 = document.getElementById('limit_2_'+product_id);
                            limit_2.children[0].innerText = msg['limit_amount_price_2'];
                            limit_2.children[2].innerText = '>'+msg['limit_amount_quantity_2'];
                        }

                        let add_to_order_button = document.getElementById('action_buttons_'+product_id).children[2];
                        add_to_order_button.setAttribute('data-storage',storage_id);
                        orderbutton[0].setAttribute('data-storage_max',optionselected[0].getAttribute('package_max'));
                        orderbutton[0].setAttribute('data-storage_min',optionselected[0].getAttribute('package_min'));
                        orderbutton[0].setAttribute('data-amount',optionselected[0].getAttribute('package_max'));
                    },
                    error: function(xhr, str) {
                        console.log(xhr);
                    }
                });

                packageweight.css("display","");

                sumwithtaxes.css("display","");


            }else{
                quantityinput.css("display","none");

                packageweight.css("display","none");

                sumwithtaxes.css("display","none");

                let limit_1 = document.getElementById('limit_1_'+product_id);
                limit_1.children[0].innerText = '';
                limit_1.children[2].innerText = '-';


                let limit_2 = document.getElementById('limit_2_'+product_id);
                limit_2.children[0].innerText = '';
                limit_2.children[2].innerText = '-';

                let productbutton = $('#action_buttons_'+product_id);
                if(productbutton[0].children[2]){
                    productbutton[0].children[2].remove();
                }
            }
        }

        function changeamount(obj){
            let id = obj.id;
            let product_id = id.substr(14);
            let optionselected = $("option:selected", document.getElementById('storage_product_'+product_id));
            let storage_id = optionselected.val();
            let amount = obj.value;
            let step = obj.step;

            if(amount%step){
              obj.value = amount - amount%step;
              amount = obj.value;
            }

            let getPrice = false;
            let getPriceRequestAmount = amount - obj.getAttribute('datamax');

            if(getPrice === true){
              document.getElementById('get_price_button_'+product_id).setAttribute('data-amount',getPriceRequestAmount);
              document.getElementById('get_price_button_'+product_id).setAttribute('data-step',step);
              document.getElementById('get_price_button_'+product_id).setAttribute('data-min',step);
              document.getElementById('get_price_button_'+product_id).click();
              obj.value = obj.getAttribute('datamax');
              amount = obj.getAttribute('datamax');
            }

            $.ajax({
                type: "GET",
                data: {
                    product_id:product_id,
                    storage_id:storage_id,
                    amount:amount
                },
                url: "{!! @route('priceCalc') !!}",
                success: function(msg){
                    console.log(msg);
                    let sum_w_taxes = document.getElementById('sum_w_taxes_'+product_id);

                    let retail_user_price = document.getElementById('retail_user_price_'+product_id);
                    if( (msg['price100_raw'] - msg['user_price_raw']) > (msg['price100_raw']*0.05) ){
                        retail_user_price.children[5].innerHTML = '<span style="background-color:#f0c674; padding: 2px;"><strike style="color:#E84124">'+msg['price100']+'</strike> '+' <span>'+msg['user_price']+'</span></span>';
                        sum_w_taxes.children[0].setAttribute('style',"background: #f0c674");
                        sum_w_taxes.children[2].setAttribute('style',"background: #f0c674");
                        sum_w_taxes.children[3].setAttribute('style',"background: #f0c674");
                    }
                    else if( (msg['price100_raw'] - msg['user_price_raw']) < (msg['price100_raw']*0.05) && ((msg['price100_raw'] -  msg['user_price_raw'])>0) ){
                        retail_user_price.children[5].innerHTML = '<span style="background-color:#96ca0a; padding: 2px;"><strike style="color:#E84124">'+msg['price100']+'</strike> '+' <span>'+msg['user_price']+'</span></span>';
                        sum_w_taxes.children[0].setAttribute('style',"background: #96ca0a");
                        sum_w_taxes.children[2].setAttribute('style',"background: #96ca0a");
                        sum_w_taxes.children[3].setAttribute('style',"background: #96ca0a");
                    }
                    else{
                        retail_user_price.children[5].innerText = msg['user_price'];
                        sum_w_taxes.children[0].setAttribute('style','');
                        sum_w_taxes.children[2].setAttribute('style','');
                        sum_w_taxes.children[3].setAttribute('style','');
                    }


                    let package_weight = document.getElementById('package_weight_'+product_id);
                    package_weight.children[0].innerText = msg['multiplier'];
                    package_weight.children[2].innerText = msg['package'];
                    package_weight.children[4].innerText = msg['weight'];

                    sum_w_taxes.children[0].innerText = msg['price'];

                    if(msg['limit_amount_quantity_2'] === '0' || msg['limit_amount_quantity_2'] === 0){
                        sum_w_taxes.children[2].innerText = '';
                        sum_w_taxes.children[3].innerText = '';
                        sum_w_taxes.children[0].setAttribute('style','');
                        sum_w_taxes.children[2].setAttribute('style','');
                        sum_w_taxes.children[3].setAttribute('style','');
                    }else{
                        sum_w_taxes.children[2].innerText = '-'+msg['discount'];
                        sum_w_taxes.children[3].innerText = msg['discountamount'];
                    }

                    let add_to_order_button = document.getElementById('action_buttons_'+product_id).children[2];
                    add_to_order_button.setAttribute('data-amount',amount);
                }
            });
        }

        //МОДАЛЬНЫЕ ФОРМЫ

        //modal order single
        $('#modal-order').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('.image').attr("src",button.data('image'));
            modal.find('.name').text(button.data('name'));

            modal.find('.product-name').text(button.data('product_name'));
            modal.find('.product_id').val(button.data('product'));
            modal.find('.storage_id').val(button[0].getAttribute("data-storage"));
            let amount = button[0].getAttribute("data-amount");
            modal.find('.amount').val(button[0].getAttribute("data-amount"));
            let order_inputs = document.getElementById("modal_order_inputs");
            while (order_inputs.firstChild) {
                order_inputs.removeChild(order_inputs.lastChild);
            }

            $('#modal_order_inputs').append($("<div class=\"form-group m-b-15\"><label>@lang('product.quantity_order')</label>"+
                "<input type=\"number\" name=\"quantity\" class=\"form-control m-b-5 quantity\" " +
                "placeholder=\"@lang('product.quantity_order')\" disabled/>"+
                "</div>"));
            $('#modal_order_inputs').append($("<div class=\"form-group m-b-15\"><label>@lang('product.quantity_order_request')</label>"+
                "<input type=\"number\" name=\"quantity_request\" class=\"form-control m-b-5 quantity_request\" " +
                "placeholder=\"@lang('product.quantity_order_request')\" disabled/>"+
                "</div>"));

            let quantity = modal.find('input[name="quantity"]');

            let quantity_request = modal.find('input[name="quantity_request"]');

            if (amount - button.data('storage_max') > 0) {
                quantity.val(button.data('storage_max'));
                quantity_request.val(amount - button.data('storage_max'));
            }
            else {
                if (amount % button.data('storage_min')) {
                    quantity.val(amount -
                        button.data('amount') % button.data('storage_min'));
                    quantity_request.val(amount
                        % button.data('storage_min'));
                } else {
                    quantity.val(amount);
                    quantity_request.val(0);
                }
            }

        });
        //modal order single

        //modal-order single submit
        $('#form_add_order').submit(function(e) {
            e.preventDefault();

            $('#modal-order').modal('hide');

            var form = $(this);

            let product_id_input = form[0].getElementsByClassName('product_id')[0].value;
            let storage_id_input = form[0].getElementsByClassName('storage_id')[0].value;
            let quantity_input = form[0].getElementsByClassName('form-control m-b-5 quantity')[0].value;
            let quantity_request_input = form[0].getElementsByClassName('form-control m-b-5 quantity_request')[0].value;

            var order_id = $('#order_id').val();
            var route = '{{route('orders')}}/add-to-order/' + order_id;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                method: "POST",
                url: route,
                data: 'product_id='+product_id_input+'&storage_id='+storage_id_input+
                '&quantity='+quantity_input+'&quantity_request='+quantity_request_input,
                success: function(resp) {
                    if (resp == "ok") {
                        $.gritter.add({
                            title: '@lang('order.modal_success')',
                        });
                        changeUpperCounter('order');
                    }
                },
                error: function(xhr, str) {
                    console.log(xhr);
                }
            });

            return false;
        });
        //modal-order single submit

        //modal-order multiple submit
        $('#form_add_order_multiple').submit(function(e) {
            e.preventDefault();

            let form = $(this)[0];
            let product_id = $('#products_selector').find(":selected")[0].value;
            let multiple_input_div = document.getElementById('multiple_input_div');
            let multiple_inputs = multiple_input_div.getElementsByClassName('multipleorderinput');
            let data = String();

            $.each(multiple_inputs, function(key, value) {
                let product_id = value.id.substr(15);
                if(product_id.indexOf('_qr') !== -1){
                    product_id = product_id.substr(0,product_id.indexOf('_qr'));
                    data += ',' + 'quantity_request:'+value.getAttribute('value');

                }
                else{
                    if(data.length === 0){
                        //data = product_id+'_:_'+'quantity='+value.getAttribute('value');
                        data = product_id + '=' + 'quantity:'+value.getAttribute('value') +
                            ',' + 'storage:'+value.getAttribute('data-storage');
                    }
                    else{
                        data +=  '&'+ product_id + '=' + 'quantity:'+value.getAttribute('value') +
                            ',' + 'storage:'+value.getAttribute('data-storage');
                    }
                }

            });


            let orders = multiple_inputs.length/2;
            let route = '{{route('orders')}}/add-to-order-multiple';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "GET",
                url: route,
                data: data,
                success: function(resp) {
                    if (resp == "ok") {

                        $('#new_wishlist_name').val('');
                        $.gritter.add({
                            title: '@lang("order.modal_success_multiple")'
                        });
                        changeUpperCounter('order');
                    }
                },
                error: function(xhr, str) {
                    console.log(xhr);
                }
            });
            $('#modal-order_multiple').modal('hide');
            return false;
        });
        //modal-order multiple submit

        //wishlist single
        $('#modal-wishlist').on('show.bs.modal', function(event) {
            if (!$('#products_wishlist').val()) {
                var button = $(event.relatedTarget);
                var modal = $(this);
                modal.find('.product_id').val(button.data('product'));
            }
        });
        //wishlist single

        $('#wishlist').change(function(e) {
            if ($(this).val() == 0) {
                $('#new_wishlist_name').parent().show();
                $('#new_wishlist_name').attr('required', 'required');
            } else {
                $('#new_wishlist_name').parent().hide();
                $('#new_wishlist_name').removeAttr('required');
            }
        });

        //modal-catalog single submit
        $('#form_add_catalog').submit(function(e) {
            e.preventDefault();

            $('#modal-wishlist').modal('hide');

            var form = $(this);
            let list_id = $('#wishlist').val();
            var route = '{{route('catalogs')}}/add-to-catalog/' + list_id;

            var form = $(this);
            var order_id = $('#order_id').val();
            //var route = '{{route('orders')}}/add-to-order/'+order_id;
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
                            title: '@lang('wishlist.modal_success')',
                        });
                        changeUpperCounter('catalog');
                    }
                },
                error:  function(xhr, str){
                    console.log(xhr);
                }
            });

            $('#products_wishlist').val('');
            return false;
        })
        //modal-catalog single submit

        //mass actions (modal-order wishlist) multiple
        $('#mass_actions').on('change', function(e) {
            let option = $("option:selected", $('#mass_actions')).val();
            window.products = [];
            if (option === 'wishlist') {
                $(".intable").each(function() {
                    if ($(this).prop('checked')) {
                        window.products.push($(this).prop('id').slice(8));
                    }
                });

                if (window.products.length > 0) {
                    $('#products_wishlist').val(window.products);
                    $('#mass_actions option[value="0"]').prop('selected', true);
                    $('#modal-wishlist').modal('show');
                }

            }
            if (option === 'order') {
                $(".intable").each(function() {
                    if ($(this).prop('checked')) {
                        window.products.push($(this).prop('id').slice(8));
                    }
                });

                if (window.products.length > 0) {
                    let element = document.getElementById("products_selector");
                    while (element.firstChild) {
                        element.removeChild(element.firstChild);
                    }

                    if (document.getElementById("name_image_container")) {
                        document.getElementById("name_image_container").remove();
                    }
                    if (document.getElementById("multiple_input_div")) {
                        document.getElementById("multiple_input_div").remove();
                    }

                    //let map = $('.data-product_name');
                    let map = $('.source');
                    window.multiple_order_map = [];
                    $.each(map, function(key, value) {
                        let id = value.attributes['data-product'].value;
                        if (window.products.indexOf(id) !== -1) {
                            multiple_order_map.push(
                              id +
                                '_:_' + value.attributes['data-product_name'].value +
                                '_:_' + value.attributes['data-storage'].value +
                                '_:_' + value.attributes['data-storage_min'].value +
                                '_:_' + value.attributes['data-storage_max'].value +
                                '_:_' + value.attributes['data-amount'].value +
                                '_:_' + value.attributes['data-image'].value
                            );
                        }
                    });

                    $('#append_to').prepend($('<div id="name_image_container"></div>'));
                    $('#append_to').append($('<div id="multiple_input_div"></div>'));
                    let inputs = document.getElementById("multiple_input_div").children;
                    let name_images = document.getElementById("name_image_container").children;
                    while (inputs.firstChild) {
                        inputs.removeChild(inputs.lastChild);
                    }
                    while (name_images.firstChild) {
                        name_images.removeChild(name_images.lastChild);
                    }

                    $.each(multiple_order_map, function(key, value) {
                        let model_order_data = value.split('_:_');
                        let product_id = model_order_data[0];
                        let text = value.split('_:_')[1];
                        let data_storage = value.split('_:_')[2];
                        let data_storage_min = value.split('_:_')[3];
                        let data_storage_max = value.split('_:_')[4];
                        let data_amount = value.split('_:_')[5];
                        let data_image = value.split('_:_')[6];
                        let quantity_amount = 0;
                        //console.log(data_storage_max);
                        if(data_storage_max === '0' || data_storage_max-data_storage_min<0){
                            return;
                        }

                        if(data_amount - data_storage_max > 0){
                            quantity_amount = data_amount - data_storage_max;
                            data_amount = data_storage_max;
                        }
                        else{
                            quantity_amount = data_amount % data_storage_min;
                            data_amount = data_amount - quantity_amount;
                        }

                        $('#products_selector')
                            .append($("<option></option>")
                                .attr("value", product_id)
                                .text(text)
                                .attr("data-storage", data_storage)
                                .attr("data-storage_min", data_storage_min)
                                .attr("data-storage_max", data_storage_max)
                            );

                        let wrapper_name_image = '<div class="row" id="wrapper_name_image_'+product_id+'" style="display: none">'+
                            '<div class="col-xl-3"><img class="image" src="'+data_image+'" width="80"></div>' +
                            '<div class="col-xl-9"><p class="name">'+text+'</p></div>' +
                            '</div>';
                        let wrapper_inputs = '<div id="wrapper_inputs_'+product_id+'" style="display: none"></div>';
                        if(key === 0){
                            wrapper_name_image = '<div class="row" id="wrapper_name_image_'+product_id+'">'+
                                '<div class="col-xl-3"><img class="image" src="'+data_image+'" width="80"></div>' +
                                '<div class="col-xl-9"><p class="name">'+text+'</p></div>' +
                                '</div>';
                            wrapper_inputs = '<div id="wrapper_inputs_'+product_id+'"></div>';
                        }
                        $('#multiple_input_div').append($(wrapper_inputs));

                        let inputdata = '<input id="multiple_input_' + product_id + '" type="number" ' +
                            'name="id_' + product_id +
                            ':storageid_' + data_storage + '" ' +
                            'class="form-control m-b-5 multipleorderinput" placeholder="@lang("product.quantity_order_request")"' +
                            'value ="' + data_amount + '" ' +
                            'data-storage ="' + data_storage +'"'+
                            'min="' + data_storage_min + '" ' +
                            'max="' + data_storage_max + '" ';
                        inputdata += 'disabled >';

                        let inputdataqr = '<input id="multiple_input_' + product_id + '_qr" type="number" ' +
                            'name="qr_id_' + product_id +
                            ':storageid_' + data_storage + '" ' +
                            'class="form-control m-b-5 multipleorderinput quantityinput" placeholder="@lang("product.quantity_order_request")"' +
                            'value ="' + quantity_amount + '" ' +
                            'min="' + data_storage_min + '" ' +
                            'max="' + data_storage_max + '" ';

                        inputdataqr += 'disabled >';

                        $('#wrapper_inputs_'+product_id).append($(inputdata));
                        $('#wrapper_inputs_'+product_id).append($(inputdataqr));
                        $('#name_image_container').append(wrapper_name_image);
                    });


                    $("#modal-order_multiple").modal('show');
                }
                $('#mass_actions option[value="0"]').prop('selected', true);
            }
        });
        //mass actions (modal-order wishlist) multiple

        //get price single
        $('#modal-get_price').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget);
            let modal = $(this);
            modal.find('.image').attr("src",button.data('image'));
            modal.find('.name').text(button.data('name'));
            modal.find('.product_id').val(button.data('product_id'));
            modal.find('.quantity').val(button.data('amount'));
            modal.find('.quantity')[0].setAttribute('min',button.data('min'));
            modal.find('.quantity')[0].setAttribute('value',button.data('amount'));
            modal.find('.quantity')[0].setAttribute('step',button.data('step'));
            modal.find('.comment').val('');
        });

        //get price single

        //get_price single submit
        $('#form_get_price').submit(function(e) {
            e.preventDefault();
            $('#modal-get_price').modal('hide');
            var product_id = $('#get_price_product_id').val();
            var form = $(this);

            var route = '{{route('products')}}/' + product_id + '/get-price/';

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "GET",
                url: route,
                data: form.serialize(),
                success: function(resp) {
                    if (resp == "ok") {
                        $.gritter.add({
                            title: '@lang('product.get_price_success')',
                        });

                    }
                },
                error: function(xhr, str) {
                    console.log(xhr);
                }
            });

            return false;
        });
        //get_price single submit
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
            z-index: 2000;
            background-color: rgb(0 0 0 / 70%);
            left: 0;
            bottom: 0;
            right: 0;
            top: 0;
        }
        .myModal article {
            background: #fff;
            padding: 25px;
            min-width: 500px;
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

        .filter {
            height: min-content;
            max-height: 300px;
            overflow-y: scroll;
            max-width: 450px;
            display: flex;
            flex-wrap: wrap;
            padding: 0 !important;
        }

        .filterElem {
            display: flex;
            align-items: center;
            width: 33%;
            padding: 10px;
            box-sizing: border-box;
            cursor: pointer;
            height: min-content
        }
        .filterElem:hover,
        .filterElem[filter-selected=true] { background-color: #01aadf; color: #fff }
        .filterElem img { width: 25px; margin-right: 5px }

        #actionsMultiProducts {
            position: fixed;
            z-index: 10;
            width: calc(100% - 220px);
            left: 220px;
            bottom: 0;
            padding: 10px;
            background: #01aadf;
            /*display: flex;*/
            display: none;
            justify-content: center;
        }
        #actionsMultiProducts select { max-width: 700px }

        .datatable_weight_class span, .datatable_sum_class span { padding: 5px 10px; display: inline-block }


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

        @media screen and (max-width: 500px) {
            .myModal article {
                padding: 15px;
                min-width: 300px;
                width: 90%;
                height: auto;
                max-height: 80%;
                overflow: auto;
            }
            #actionsMultiProducts {
                width: 100%;
                left: 0;
            }
        }
    </style>

@endpush
