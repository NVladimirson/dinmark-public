@extends('layouts.default')

@section('title', 'Managed Tables - Buttons')

@push('css')
    <link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
    <link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="/assets/plugins/lightbox2/dist/css/lightbox.css" rel="stylesheet" />
    <link href="/assets/css/default/table-ptoduct.css" rel="stylesheet" />
    <link href="/assets/plugins/jstree/dist/style.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
@endpush

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

            var loaded_nodes = [];
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
                if (!loaded_nodes.includes(data.node.id)) {

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
                    loaded_nodes.push(data.node.id);
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
            });

            $("#accordion").accordion({
                collapsible: true,
                active: false,
                heightStyle: "content",
                content: '.content1'
            });


            function initOptionFilters() {
                    let filter_selected_map = $("[filter-selected=true]");
                    let all_filters = $(".filter_with_options");
                    $.each(all_filters, function(key, value) {
                        value.setAttribute("style", "color:grey;cursor:progress");
                    });
                    filter_selected_ids = Array();
                    $.each(filter_selected_map, function(key, value) {
                        if (value.attributes['filter-selected'].value === 'true') {
                            let option_id = value.attributes['option_id'].value;
                            let option_name = value.attributes['option_name'].value;
                            filter_selected_ids.push(option_id + ';' + option_name);
                            //filter_selected_ids.option_id = option_name;
                        }
                    });
                    let route = '{{route("products.optionfilters")}}';

                    $.ajax({
                        method: "GET",
                        url: route,
                        data: {
                            filter_with_options: filter_selected_ids,
                            language: 'ru'
                        },
                        success: function(resp) {
                            let element = document.getElementById("filters_selected");
                            while (element.firstChild) {
                                element.removeChild(element.firstChild);
                            }

                            $.each(all_filters, function(key, value) {
                                //value.setAttribute("style", "color:red;cursor:not-allowed");
                                value.setAttribute("style", "color:red;cursor:not-allowed");
                                value.setAttribute("filter-accessible", "false");
                            });

                            if (resp.available) {
                                //console.log(resp);
                                $.each(resp.available, function(index, value) {
                                    let filter_by_id = $("[option_id=" + index + "]");
                                    //console.log(filter_by_id);
                                    if(typeof(filter_by_id[0]) !== 'undefined'){
                                      filter_by_id[0].setAttribute("style", "cursor:pointer");
                                      filter_by_id[0].setAttribute("filter-accessible", "true");
                                    }
                                });
                            }

                            //console.log($('#instockToggler').prop('checked'));
                            if ($('#instockToggler').prop('checked')) {
                                $('#filters_selected').append($('<div class="selected_filter">' +
                                    '<p class="tesst" id="deselected_filter_toggler" style="font-size: 10pt;' +
                                    'text-align: center;margin: auto;">' + "@lang('product.in_stock_button_name')" + '' +
                                    '<a class="deselect_filter" deselectid="deselected_filter_toggler" href=#>' +
                                    '<i class="far fa-times-circle" style="float: right;color: red;' +
                                    'text-align: center;"></i></a></p></div>'));
                            }
                            if ($("option:selected", $('#storages')).val() !== '0') {
                              $.each($("option:selected", $('#storages')), function(index, value) {
                                $('#filters_selected').append($('<div class="selected_filter">' +
                                    '<p class="tesst" id="deselected_filter_storage" deselected-value="'+value.value+'" style="font-size: 10pt;' +
                                    'text-align: center;margin: auto;">' + "@lang('product.storage_filter_name.storage_term')" + " : " +
                                    value.innerText +
                                    '<a class="deselect_filter" deselectid="deselected_filter_storage" href=#>' +
                                    '<i class="far fa-times-circle" style="float: right;color: red;' +
                                    'text-align: center;"></i></a></p></div>'));
                              });
                          }
                            if (!($('#new-checked').css("display") === 'none')) {
                                $('#filters_selected').append($('<div class="selected_filter">' +
                                    '<p class="tesst" id="deselected_filter_new" style="font-size: 10pt;' +
                                    'text-align: center;margin: auto;">' + "@lang('product.filters.new')" + '' +
                                    '<a class="deselect_filter" deselectid="deselected_filter_new" href=#>' +
                                    '<i class="far fa-times-circle" style="float: right;color: red;' +
                                    'text-align: center;"></i></a></p></div>'));
                            }
                            if (!($('#hits-checked').css("display") === 'none')) {
                                $('#filters_selected').append($('<div class="selected_filter">' +
                                    '<p class="tesst" id="deselected_filter_hits" style="font-size: 10pt;' +
                                    'text-align: center;margin: auto;">' + "@lang('product.filters.hits')" + '' +
                                    '<a class="deselect_filter" deselectid="deselected_filter_hits" href=#>' +
                                    '<i class="far fa-times-circle" style="float: right;color: red;' +
                                    'text-align: center;"></i></a></p></div>'));
                            }
                            if (!($('#discount-checked').css("display") === 'none')) {
                                $('#filters_selected').append($('<div class="selected_filter">' +
                                    '<p class="tesst" id="deselected_filter_discount" style="font-size: 10pt;' +
                                    'text-align: center;margin: auto;">' + "@lang('product.filters.discount')" + '' +
                                    '<a class="deselect_filter" deselectid="deselected_filter_discount" href=#>' +
                                    '<i class="far fa-times-circle" style="float: right;color: red;' +
                                    'text-align: center;"></i></a></p></div>'));
                            }

                            let loaded_filter_categories = document.getElementById('reload').textContent.split(",");

                            if (loaded_filter_categories.length > 0 || loaded_filter_categories[0] !== "") {
                                $.each(loaded_filter_categories, function(index, value) {
                                    let id = value;
                                    let name = document.getElementById(value + '_anchor');
                                    if (name) {
                                        name = name.innerText;
                                        if (name.length > 35) {
                                            name = name.substr(0, 35) + '...';
                                        }
                                        $('#filters_selected').append($('<div class="selected_filter">' +
                                            '<p class="tesst" id="deselected_filter_categories_' + id + '" style="font-size: 10pt;' +
                                            'text-align: center;margin: auto;">' + "@lang('product.storage_filter_name.category')" + " : " + name + '' +
                                            '<a class="deselect_filter" deselectid="deselected_filter_categories_' + id + '" href=#>' +
                                            '<i class="far fa-times-circle" style="float: right;' +
                                            'color: red;' +
                                            'text-align: center;"></i></a></p></div>'));
                                    }
                                });
                            }

                            $.each(resp['checked'], function(index, value) {
                                if (value.length > 35) {
                                    value = value.substr(0, 35) + '...';
                                }
                                let filter_by_id = $("[option_id=" + index + "]");
                                filter_by_id[0].setAttribute("style", "cursor:pointer");
                                filter_by_id[0].setAttribute("filter-accessible", "true");
                                $('#filters_selected').append($('<div class="selected_filter">' +
                                    '<p class="tesst" id="deselected_filter_' + index + '" style="font-size: 10pt;' +
                                    'text-align: center;margin: auto;">' + filter_by_id[0].getAttribute('option_filter_name') + ' : ' + value + '' +
                                    '<a class="deselect_filter" deselectid="deselected_filter_' + index + '" href=#>' +
                                    '<i class="far fa-times-circle" style="float: right;color: red;' +
                                    'text-align: center;"></i></a></p></div>'));
                            });


                            // if((resp.available) && !(resp['checked'])){
                            //     $.each(all_filters,function (key,value) {
                            //         value.setAttribute("style", "color:black;cursor:pointer");
                            //         value.setAttribute("filter-accessible","true");
                            //     });
                            // }

                            if ((resp.available.length === 0) && (resp['checked'].length === 0)) {
                                $.each(all_filters, function(key, value) {
                                    value.setAttribute("style", "cursor:pointer");
                                    value.setAttribute("filter-accessible", "true");
                                });
                            }

                            setFiltersQuantity();
                        },
                        error: function(xhr, str) {
                            console.log(xhr);
                        }
                    });

                    $.each(all_filters, function(key, value) {
                        value.setAttribute("style", "color:grey;cursor:progress");
                        // value.setAttribute("filter-accessible","false");
                    });

            }

            function setFiltersQuantity() {
                let accessible = $("[filter-accessible=true]");
                let accessible_per_filter = Object();
                $.each(accessible, function(key, value) {
                    let option_filter_name = value.getAttribute("option_filter_name");
                    // if(value.getAttribute("filter-selected")==='false'){
                    if (typeof accessible_per_filter[option_filter_name] !== 'undefined') {
                        accessible_per_filter[option_filter_name] = accessible_per_filter[option_filter_name] + 1;
                    } else {
                        accessible_per_filter[option_filter_name] = 1;
                    }
                    //}
                });

                $.each(accessible_per_filter, function(key, value) {
                    let header = $("[filter_name='" + key + "']")[0];
                    if (value > 1) {
                        header.innerText = key + ' (' + value + ')';
                    } else {
                        header.innerText = key;
                    }

                });
            }

            $("#optionfilters").accordion({
                collapsible: true,
                active: false,
                heightStyle: "content",
                content: '.filter'
            });

            $('#select_all_products').change(function() {
                    if ($('#select_all_products').prop('checked')) {
                        window.products = [];
                        $(".intable").each(function() {
                            $(this).prop('checked', true);
                            products.push($(this).prop('id').slice(8));
                        });
                    } else {
                        $(".intable").each(function() {
                            $(this).prop('checked', false);
                            document.getElementById('selected_products').innerText = '';
                        });
                        window.products = [];
                    }
            });

            $('#storages').on('change', function(e) {
                    initOptionFilters();
                    jsTreetoDatatable();

            });

            $('#instockToggler').click(function() {
                    initOptionFilters();
                    jsTreetoDatatable();
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


            //?????????????????? jstree ?????? datatable
            function jsTreetoDatatable() {

                let collection = document.getElementsByClassName("jstree-node");
                let loaded = [];
                for (let i = 0; i < collection.length; i++) {
                    if (collection[i].getAttribute('aria-selected') == 'true') {
                        loaded.push(collection[i].getAttribute('id'));
                    }
                    loaded = loaded.filter(function(value, index, self) {
                        return self.indexOf(value) === index;
                    });
                }
                var node = document.getElementById('reload');
                node.textContent = loaded.toString();
                window.table.ajax.reload();
            }
            //?????????????????? jstree ?????? datatable

            //?????????????????? ??????????

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
                                title: '@lang('purchases.modal_success')',
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
                                title: '@lang("purchases.modal_success_multiple")'
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


                // $.ajaxSetup({
                //     headers: {
                //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //     }
                // });
                // $.ajax({$('#modal-get_price').trigger('show.bs.modal')
                //     method: "GET",
                //     url: route,
                //     data: form.serialize(),
                //     success: function(resp) {
                //         if (resp == "ok") {
                //
                //             $('#new_wishlist_name').val('');
                //
                //             $.gritter.add({
                //                 title: '@lang('wishlist.modal_success')',
                //             });
                //         }
                //     },
                //     error: function(xhr, str) {
                //         console.log(xhr);
                //     }
                //
                // });
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

                                // var quantity_request = modal.find('input[name="quantity_request"]');
                                //
                                // if (button.data('amount') > button.data('storage_max')) {
                                //     //data-storage_min
                                //     if (button.data('amount') % button.data('storage_min')) {
                                //         quantity.val(button.data('storage_max'));
                                //         quantity_request.val(button.data('amount') - button.data('storage_max'));
                                //     }
                                //     else {
                                //         quantity.val(button.data('amount'));
                                //         quantity_request.val(button.data('amount') - button.data('storage_max'));
                                //     }
                                // }
                                // else {
                                //     if (button.data('amount') % button.data('storage_min')) {
                                //         quantity.val(button.data('amount') -
                                //             button.data('amount') % button.data('storage_min'));
                                //         quantity_request.val(button.data('amount')
                                //             % button.data('storage_min'));
                                //     } else {
                                //         quantity.val(button.data('amount'));
                                //         quantity_request.val(0);
                                //     }
                                // }
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


            //??????????????

            //new filters
            $('#new').click(function(e) {
                if ($('#new-checked').css("display") === 'none') {
                    $('#new-checked').css("display", "");
                } else {
                    $('#new-checked').css("display", "none");
                }
                initOptionFilters();
                jsTreetoDatatable();
            });
            //??????????

            //hits filters
            $('#hits').click(function(e) {
                if ($('#hits-checked').css("display") === 'none') {
                    $('#hits-checked').css("display", "");
                } else {
                    $('#hits-checked').css("display", "none");
                }
                initOptionFilters();
                jsTreetoDatatable();
            });
            //????????

            //discount filters
            $('#discount').click(function(e) {
                if ($('#discount-checked').css("display") === 'none') {
                    $('#discount-checked').css("display", "");
                } else {
                    $('#discount-checked').css("display", "none");
                }
                initOptionFilters();
                jsTreetoDatatable();
            });
            //?????????????????? ??????????????????????

            //option filters
            //?????????????? ???? ????????????????????
            $('.filter_with_options').click(function() {
                if ($(this).attr('filter-accessible') == 'true') {
                    if ($(this).attr('filter-selected') === 'true') {
                        $(this).attr('filter-selected', 'false');
                        $("#filter-checked_" + $(this).attr('option_id')).css("display", "none");
                    } else {
                        $(this).attr('filter-selected', 'true');
                        $("#filter-checked_" + $(this).attr('option_id')).css("display", "");
                    }
                    initOptionFilters();
                    jsTreetoDatatable();
                }
            });
            //?????????????? ???? ????????????????????

            //delete filters
            //???????????????? ???????????????? ???? ????????????
            $(document).on("click", ".deselect_filter", function(e) {
                let id = $(this)[0].getAttribute("deselectid");
                if (id === 'deselected_filter_toggler') {
                    if ($('#instockToggler').prop('checked') === true) {
                        $('#instockToggler').prop('checked', false);
                    } else {
                        $('#instockToggler').prop('checked', true);

                    }
                    var myobj = document.getElementById(id);
                } else if (id === 'deselected_filter_storage') {
                    var myobj = document.getElementById(id);
                    let deselected_value = myobj.getAttribute('deselected-value');
                    var wanted_option = $('#storages option[value="'+ deselected_value +'"]');
                    wanted_option.prop('selected', false);
                    let labels = '';
                    $.each($('#storages option'), function(key, option) {
                      if(option.selected === true){
                        if(labels !== ''){
                          labels = labels + ', ' + option.label;
                        }
                        else{
                          labels = option.label;
                        }
                      }
                        // if (value.getAttribute("option_id") === id) {
                        //     value.setAttribute("style", "cursor:pointer");
                        //     value.setAttribute("filter-selected", "false");
                        //     return false;
                        // }
                    });
                    if(labels === ''){
                      labels = '@lang('product.select_term')';
                    }
                    console.log(document.getElementsByClassName('btn dropdown-toggle btn-white')[0]);
                    document.getElementsByClassName('btn dropdown-toggle btn-white')[0].setAttribute('title',labels);
                    document.getElementsByClassName('btn dropdown-toggle btn-white')[0].children[0].children[0].children[0].innerText = labels;
                     //$('#storages').trigger('change.select2');
                } else if (id === 'deselected_filter_new') {
                    if ($('#new-checked').css("display") === 'none') {
                        $('#new-checked').css("display", "");
                    } else {
                        $('#new-checked').css("display", "none");
                    }
                    var myobj = document.getElementById(id);
                } else if (id === 'deselected_filter_hits') {
                    if ($('#hits-checked').css("display") === 'none') {
                        $('#hits-checked').css("display", "");
                    } else {
                        $('#hits-checked').css("display", "none");
                    }
                    var myobj = document.getElementById(id);
                } else if (id === 'deselected_filter_discount') {
                    if ($('#discount-checked').css("display") === 'none') {
                        $('#discount-checked').css("display", "");
                    } else {
                        $('#discount-checked').css("display", "none");
                    }
                    var myobj = document.getElementById(id);
                } else if ($(this)[0].getAttribute("deselectid").split('deselected_filter_categories_')[1]) {
                    let id = $(this)[0].getAttribute("deselectid").split('deselected_filter_categories_')[1];

                    $('#jstree').jstree().deselect_node(id, true);

                    var myobj = document.getElementById('deselected_filter_categories_' + id);
                } else {
                    let id = $(this)[0].getAttribute("deselectid").split('deselected_filter_')[1];
                    let all_filters = $(".filter_with_options");
                    $.each(all_filters, function(key, value) {
                        if (value.getAttribute("option_id") === id) {
                            value.setAttribute("style", "cursor:pointer");
                            value.setAttribute("filter-selected", "false");
                            return false;
                        }
                    });
                    var myobj = document.getElementById('deselected_filter_' + id);
                }
                myobj.remove();
                initOptionFilters();
                jsTreetoDatatable();
            });
            //???????????????? ???????????????? ???? ????????????



        });
    </script>

    <script>
        jQuery(function($) {
            window.table =
                $('#datatable').DataTable( {
                    "language": {
                        "url": "@lang('table.localization_link')",
                    },
                    // "scrollX": true,
                    "pageLength": 25,
                    // "autoWidth": true,
                    "processing": true,
                    "autoWidth": true,
                    "serverSide": true,
                    deferRender: true,
                    "ajax": {
                      "url": "{!! route('purchases.get_ajax') !!}",
                      "data": {
                        "filter_with_options": function() {
                            let filter_selected_map = $("[filter-selected=true]");
                            let filter_selected_ids = Array();
                            $.each(filter_selected_map, function(key, value) {
                                if (value.attributes['filter-selected'].value === 'true') {
                                    let option_id = value.attributes['option_id'].value;
                                    //let option_name = value.attributes['option_name'].value;
                                    filter_selected_ids.push(option_id);
                                    //filter_selected_ids.option_id = option_name;
                                }
                            });
                            //console.log('filter_with_options: ' + filter_selected_ids)
                            if(filter_selected_map.length){
                              return filter_selected_ids;
                            }

                        },
                        "dates": function(){
                          let startdate = $("#startdate")[0].value;
                          if(startdate === ''){
                            startdate = 0;
                          }

                          let enddate = $("#enddate")[0].value;
                          if(enddate === ''){
                            enddate = 0;
                          }
                          return [startdate,enddate];
                        }
                      },
                    },
                    "order": [[ 0, "desc" ]],
                    "columns": [
                        {
                        data: 'date_html',
                        "width": "10%"
                        },
                        {
                            data:'code_name',
                            "orderable": true,
                            "searchable": true,
                            "width": "150px"
                        },
                        {
                            data:'photo',
                            "orderable": true,
                            "searchable": false,
                            "width": "14%"
                        },
                        {
                            data:'quantity_in_orders_sellings_returns',
                            "orderable": true,
                            "searchable": false,
                            "width": "150px"
                        },
                        {
                            data:'sum_of_orders/sellings/reclamations',
                            "orderable": true,
                            "searchable": false,
                            "width": "16%"
                        },
                        {
                            data:'percentage_of_confirmed_orders',
                            "orderable": true,
                            "searchable": false,
                            "width": "14%"
                        },
                        {
                            data:'sellings_weight',
                            "orderable": true,
                            "searchable": false,
                            "width": "14%"
                        },
                    ],
                });
                    $("#startdate").datepicker({ dateFormat: "dd-mm-yy" }).val();
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

                    $("#startdate").change(function() {
                      window.startdate = $("#startdate")[0].value;
                      window.enddate = $("#enddate")[0].value;
                        window.table.ajax.reload();
                    });

                    $("#enddate").change(function() {
                      window.enddate = $("#enddate")[0].value;
                      window.startdate = $("#startdate")[0].value;
                      window.table.ajax.reload();
                    });

                    $("#cleardates").click(function(){
                      $("#startdate").val('');
                      $("#enddate").val('');
                      window.table.ajax.reload();
                    });

                    window.loading = false;
                    $('#csvreport').click(function(){
                      let url = "{!! @route('purchases.getcsv') !!}";
                      // if(window.loading === false){
                      //   window.loading = true;
                      //   $.ajax({
                      //       method: "GET",
                      //       url: url,
                      //       data: {
                      //           startdate: $("#startdate")[0].value,
                      //           enddate: $("#enddate")[0].value
                      //       },
                      //       success: function(resp) {
                      //         window.loading = false;
                      //         console.log(resp);
                      //       }
                      //   });
                      // }
                      // else{
                      //   alert('???????? ????????????????');
                      // }
                      url = url + '?startdate=' + $("#startdate")[0].value + '&enddate=' + $("#enddate")[0].value;
                      window.location.href = url;
                    });

        });
    </script>
@endpush

@section('content')
	{{ Breadcrumbs::render('purchases') }}
<div id="filters_selected"></div>
<div id="wrap-table">
        <i v-show="!isShow" v-on:click="toggleShow" id="slide-filter-on" class="fa fa-filter"></i>
        <i v-show="isShow" v-on:click="toggleShow" id="slide-filter-of" class="fa fa-angle-double-right"></i>
    <h1 class="page-header">@if(isset($page_name)) {{$page_name}} @else @lang('purchases.purchases_pagename') @endif</h1>
    <div v-show="isShow" class="responsive-width">
    <div id="scroll-filter" class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">@lang('product.right_widget_name')</h4>
        </div>
        <div id="reload" style="display:none"></div>
        <div id="selected_products" style="display:none"></div>
        <div id="filters_selected">

        </div>


    </div>
    </div>

    <!-- begin row -->
    <div class="row">
       <!-- begin row -->
       <div class="col-xl-12">
          <div class="panel panel-primary">
             <div class="panel-heading">
               <h4 class="panel-title-control">
                 ???????????? ??????????????
               </h4>
             </div>
             <div class="panel-body">
               <div class="row" style="margin-bottom: 10px">
                  <!-- <div class="col-xl-6"> -->
                     <!-- <select class="custom-select" multiple>
                        <option selected>None</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                     </select> -->
                  <!-- </div> -->
                  <div class="col-xl-5">
                     <p>@lang('purchases.purchases_startdate')<input type="text" id="startdate"></p>
                  </div>
                  <div class="col-xl-5">
                     <p>@lang('purchases.purchases_enddate')<input type="text" id="enddate"></p>
                  </div>
                  <div class="col-xl-2">
                     <a id="cleardates" class="btn btn-sm btn-primary m-r-5"><i class="fa fa-times" aria-hidden="true"> ????????????????</i></a>
                  </div>

               </div>
               <div class="row" style="margin-bottom: 10px">
                  <div class="col-xl-5">

                  </div>
                  <div class="col-xl-5">

                  </div>
                  <div class="col-xl-2">
                     <a id="csvreport" class="btn btn-sm btn-primary m-r-5"><i class="fa fa-download" aria-hidden="true"> ?????????????? ??????????</i></a>
                  </div>

               </div>
                <div class="row">
                   <div class="col-xl-12">
                      <table id="datatable" class="table table-striped table-bordered table-td-valign-middle">
                         <thead>
                            <tr>
                               <th>@lang('order.table_header_date_create')</th>
                               <th>@lang('purchases.purchases_table_code/name')</th>
                               <th>@lang('purchases.purchases_table_photo')</th>
                               <th>@lang('purchases.purchases_table_quantity_in_orders_sellings_returns')</th>
                               <th>@lang('purchases.purchases_table_sum_of_orders/sellings/reclamations')</th>
                               <th>@lang('purchases.purchases_table_percentage_of_confirmed_orders')</th>
                               <th>@lang('purchases.purchases_table_sellings_weight')</th>
                            </tr>
                         </thead>
                         <tbody>
                         </tbody>
                      </table>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
    <!-- <div class="row">

    </div> -->
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
