@extends('layouts.default')

@section('title', 'Managed Tables - Buttons')

@push('css')
	<link href="/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
	<link href="/assets/css/default/table-ptoduct.css" rel="stylesheet" />
	<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
	<link href="/assets/plugins/jstree/dist/style.min.css" rel="stylesheet" />
    <style>
        .panel-title-control{
            line-height: 20px;
            font-size: 12px;
            margin-top: 0;
            margin-bottom: 0;
            color: inherit;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }

				.image-container img {
						max-height: 100%;
						max-width: 30px;
				}

				.ui-accordion .ui-accordion-content {
					padding:0px !important;
				}

				.ui-accordion-header {
					height: 60px;
					font-size: 24px;
				}
		</style>

@endpush

@section('content')
	{{ Breadcrumbs::render('catalogs') }}
	<div id="filters_selected">
    </div>
<div id="wrap-table">
				<i v-show="!isShow" v-on:click="toggleShow" id="slide-filter-on" class="fa fa-filter"></i>
        <i v-show="isShow" v-on:click="toggleShow" id="slide-filter-of" class="fa fa-angle-double-right"></i>
	<!-- <div id="accordion" class=".ui-helper-reset">
			<p style="font-size: 12pt;">@lang('product.all_categories_name')</p>
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
										@endforeach
			</div>
	</div> -->

	<h1 class="page-header">@lang('wishlist.page_list')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title-control">

                                @lang('product.all_tab_name')
                            <div class="d-inline-block m-r-5 m-l-5" style="width: 300px">
                                <select class="form-control selectpicker m-b-5" id="change_wishlist" data-size="10" data-live-search="true" data-style="btn-white">
                                    @foreach($wishlists as $wishlist)
                                        <option value="{{$wishlist->id}}" data-main="{{$wishlist->is_main}}" data-koef="{{
				($wishlist->price)? $wishlist->price->koef : 1 }}" data-price="{{$wishlist->price_id}}" @if(session('current_catalog') == $wishlist->id) selected="selected" @endif>{{$wishlist->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                                <a href="#modal-wishlist_new" id="new_wishlist_btn" class="btn btn-sm btn-green m-r-5 m-b-5" data-toggle="modal" title="@lang('wishlist.btn_new')"><i class="fas fa-plus-circle"></i></a>
                                <a href="#modal-wishlist_rename" class="btn btn-sm btn-primary m-r-5 m-b-5" data-toggle="modal" title="@lang('wishlist.btn_rename')"><i class="fas fa-pencil-alt"></i></a>
                                <a href="#modal-wishlist_price" class="btn  btn-sm btn-primary m-r-5 m-b-5" data-toggle="modal" title="@lang('wishlist.btn_price')"><i class="fas fa-tag"></i></a>
                                <a href="#modal-wishlist_delete" id="delete_wishlist_btn" class="btn btn-sm btn-danger m-r-5 m-b-5" data-toggle="modal" title="@lang('wishlist.btn_delete')"><i class="fas fa-trash-alt"></i></a>


                        </h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6 m-b-15">
							<p class="m-b-5">@lang('wishlist.add_hand_message')</p>
							<form id="wishlist_add_product_form" action="{{route('catalogs.store')}}" method="get">
								@csrf
								<div class="row m-b-15">
									<div class="col-lg-8 m-b-5">
										<select class="form-control m-b-5" id="product_select" name="product_id">
										</select>
									</div>
									<div class="col-lg-4">
										<button type="submit" class="btn btn-green " title="@lang('wishlist.add_product_btn')"><i class="fas fa-plus-circle fa-lg"></i></button>
									</div>
								</div>
							</form>
						</div>
						<div class="col-lg-6 m-b-15">
							<p class="m-b-5">@lang('wishlist.add_import_message')</p>
							<form id="wishlist_import_product_form" action="{{route('catalogs.import')}}" method="post" enctype="multipart/form-data">
								@csrf
								<div class="row">
									<div class="col-lg-8">
										<div class="form-group m-b-5">
											<div class="input-group m-b-5 @error('import') is-invalid @enderror">
												<div class="custom-file">
													<input type="file" name="import" class="custom-file-input @error('import') is-invalid @enderror" id="uploadPhoto">
													<label class="custom-file-label" for="uploadPhoto">@lang('wishlist.select_file')</label>
												</div>
											</div>
											@error('import')
											<span class="invalid-feedback " role="alert">
											 <strong>{{ $message }}</strong>
										</span>
											@enderror
										</div>
									</div>
									<div class="col-lg-4">
										<button type="submit" class="btn btn-primary" title="@lang('wishlist.import_product_btn')"><i class="fas fa-cloud-upload-alt fa-lg"></i></button>
                                        <a href="{{route('catalogs.download_price',session('current_catalog'))}}" id="download_price" class="btn btn-primary " title="@lang('wishlist.btn_price_excel')"><i class="fas fa-cloud-download-alt fa-lg"></i></a>
                                    </div>
								</div>
							</form>

                            <div class="col-lg-2">
                            </div>
							<p class="m-b-0">@lang('wishlist.import_file_note') <a href="{{asset('import/catalog_import.xlsx')}}" target="_blank">@lang('wishlist.import_file_example')</a></p>
						</div>
					</div>
                    <div class="table-scroll-container">
					<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle">
						<thead>
                        <!-- <tr>
                            <th>

                            </th>
                            <th>

                            </th>
                            <th>

                            </th>
                        </tr> -->
							<tr>
								<th width="30"></th>
								<th width="1%" data-orderable="false"></th>
                               <th class="text-nowrap">@lang('wishlist.table_header_name_article')</th>
								{{--<th class="text-nowrap">@lang('wishlist.table_header_name')</th>--}}
								{{--<th class="text-nowrap">@lang('wishlist.table_header_article')</th>--}}
								<th class="text-nowrap">@lang('wishlist.table_header_holding_article')</th>
								<th>@lang('product.table_header_price')</th>
								<th id="price_porog_1" class="text-nowrap">@lang('product.table_header_price_porog_1')</th>
								<th id="price_porog_2" class="text-nowrap">@lang('product.table_header_price_porog_2')</th>
								<th>
									??????????????/????????????  ????????????????
								</th>
								<th>
										@lang('product.table_header_quantity')
								</th>
								<th>
										@lang('product.table_header_package_weight')
								</th>
								<!-- <th class="text-nowrap">@lang('wishlist.table_header_price')</th>
								<th class="text-nowrap coef-header">@lang('wishlist.table_header_user_price') x {{
				($curentWishlist->price)? $curentWishlist->price->koef : 1 }}</th> -->
								<th class="text-nowrap">@lang('product.table_header_sum_w_taxes')</th>
								<th width="100"></th>
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
		<!-- end col-10 -->
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
	<!-- end row -->

    @include('product.include.modal_wishlist')
	@include('product.include.modal_wishlist_new')
	@include('product.include.modal_wishlist_rename')
	@include('product.include.modal_wishlist_price')
	@include('product.include.modal_wishlist_delete')
	@include('product.include.modal_order')
    @include('product.include.modal_get_price')
@endsection

@push('scripts')
	{{--<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>--}}
	{{--<script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>--}}
	{{--<script src="https://cdn.datatables.net/fixedheader/3.1.7/js/dataTables.fixedHeader.min.js"></script>--}}

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
	{{--<script src="/assets/js/demo/table-manage-buttons.demo.js"></script>--}}

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
	$(document).ready(function() {
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

            // document.getElementsByClassName('btn dropdown-toggle btn-white')[0].setAttribute('title','@lang('product.select_term')');
            // document.getElementsByClassName('btn dropdown-toggle btn-white')[0].children[0].children[0].children[0].style.color = "#4e5c68";
            // document.getElementsByClassName('btn dropdown-toggle btn-white')[0].children[0].children[0].children[0].innerText = '@lang('product.select_term')';


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

                var form = $(this);
                let product_id_input = form[0].getElementsByClassName('product_id')[0].value;
                let storage_id_input = form[0].getElementsByClassName('storage_id')[0].value;
                let quantity_input = form[0].getElementsByClassName('form-control m-b-5 quantity')[0].value;
                let quantity_request_input = form[0].getElementsByClassName('form-control m-b-5 quantity_request')[0].value;

								if(quantity_input === '0'){
									return 0;
								}
								else{
									$('#modal-order').modal('hide');


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
								}
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
				})
    </script>

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {
				var catalogPath = "{{route('catalogs')}}";
				var ajaxRouteBase = "{!! route('catalogs.all_ajax') !!}"
				var ajaxRoute = "{!! route('catalogs.all_ajax') !!}?group={{session('current_catalog')}}"

				window.table = $('#data-table-buttons').DataTable( {
					"language": {
						"url": "@lang('table.localization_link')",
					},
					//"scrollX": true,
                    // fixedHeader: {
                     //    header: true,
                     //    footer: true
                    // },

					"pageLength": 25,
					"autoWidth": true,
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": ajaxRoute,
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

							}
						},
					},
					"order": [[ 3, "asc" ]],
					"columns": [
						{
							className: 'text-center',
							data: 'id',
							"visible": false,
							"searchable": false
						},
						{
							"orderable":      false,
							data: 'image_html',
						},
						{
							"orderable":      true,
							data: 'name_article_html',
							class: 'datatable_namearticle_class'
						},
						// {
						// 	data: 'article_show_html',
						// },
						{
							data: 'article_holding',
						},
						// {
						// 	"orderable":      false,
						// 	data: 'user_price',
						// },
						// {
						// 	"orderable":      false,
						// 	data: 'catalog_price',
						// },
						{
							"orderable":      false,
							data: 'retail_user_prices',
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
							"orderable":      false,
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
						},
					],
					"drawCallback": function( settings ) {
						$('.holding-article').change(function (e) {
							e.preventDefault();
							var product_id = $(this).data('product');
							var article = $(this).val();
							var route = '{{route('catalogs')}}/change-article/' + product_id;

							$.ajaxSetup({
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								}
							});
							$.ajax({
								method: "POST",
								url: route,
								data: {
									article: article
								},
								success: function (resp) {
									if (resp == "ok") {
										window.table.ajax.reload();
									}else{
										window.table.ajax.reload();
										$.gritter.add({
											title: resp,
										});
									}
								},
								error: function (xhr, str) {
									console.log(xhr);
								}
							});
						});

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

						$('.product-wishlist-remove').on('click',function (e) {
							e.preventDefault();

							var product_id = $(this).data('product');
							var route = '{{route('catalogs')}}/remove-to-catalog/' + $('#change_wishlist').val();

							$.ajaxSetup({
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								}
							});
							$.ajax({
								method: "POST",
								url: route,
								data: {
									product_id: product_id
								},
								success: function (resp) {
									if (resp == "ok") {
										window.table.ajax.reload();
									}
								},
								error: function (xhr, str) {
									console.log(xhr);
								}
							});
						});
					}
				} );


				$('#modal-wishlist').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					modal.find('.product_id').val(button.data('product'));
					modal.find('.old_catalog_id').val($('#change_wishlist').val());
					modal.find('.wishlist-description').hide();
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

				$('#form_add_catalog').submit(function (e) {
					e.preventDefault();

					$('#modal-wishlist').modal('hide');

					var form = $(this);
					let list_id = $('#wishlist').val();
					var route = '{{route('catalogs')}}/change-catalog/'+list_id;

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
									title: '@lang('wishlist.modal_change_success')',
								});
								window.table.ajax.reload();
							}
						},
						error:  function(xhr, str){
							console.log(xhr);
						}
					});

					return false;
				})



				// $('#modal-order').on('show.bs.modal', function (event) {
				// 	var button = $(event.relatedTarget);
				// 	var modal = $(this);
				// 	modal.find('.product-name').text(button.data('product_name'));
				// 	modal.find('.product_id').val(button.data('product'));
				// 	modal.find('.storage_id').val(button.data('storage'));
				// 	modal.find('.order-storage-amount').text(button.data('storage_max'));
				// 	var quantity = modal.find('input[name="quantity"]');
				// 	quantity.val(button.data('storage_min'));
				// 	quantity.attr('min',button.data('storage_min'));
				// 	quantity.attr('step',button.data('storage_min'));
				// 	quantity.attr('data-max',button.data('storage_max'));
				//
				// 	var quantity_request = modal.find('input[name="quantity_request"]');
				// 	quantity_request.val(0);
				// 	quantity_request.attr('min',0);
				// 	quantity_request.attr('step',button.data('storage_min'));
				//
				// 	$('.storage-limit-info').hide();
				// 	$('.storage-limit-request').hide();
				// 	$('input[name="quantity_request"]').change();
				// });

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

				$('#modal-get_price').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					modal.find('.product_id').val(button.data('product_id'));
				})

				// $('#form_add_order').submit(function (e) {
				// 	e.preventDefault();
				//
				// 	$('#modal-order').modal('hide');
				//
				// 	var form = $(this);
				//
				// 	var order_id = $('#order_id').val();
				// 	var route = '{{route('orders')}}/add-to-order/'+order_id;
				//
				// 	$.ajaxSetup({
				// 		headers: {
				// 			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				// 		}
				// 	});
				// 	$.ajax({
				// 		method: "POST",
				// 		url: route,
				// 		data: form.serialize(),
				// 		success: function(resp)
				// 		{
				// 			if(resp == "ok"){
				// 				$.gritter.add({
				// 					title: '@lang('order.modal_success')',
				// 				});
				// 				if(order_id == 0){
				// 					document.location.reload(true);
				// 				}
				// 				window.table.ajax.reload();
				// 			}
				// 		},
				// 		error:  function(xhr, str){
				// 			console.log(xhr);
				// 		}
				// 	});
				//
				// 	return false;
				// })

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
				})


				setList();
				$('#change_wishlist').change(function () {
					ajaxRoute = ajaxRouteBase+'?group='+$(this).val();
					window.table.ajax.url( ajaxRoute ).load();
					setList();
				});

				function setList() {
					$('#download_price').attr('href',catalogPath+'/download-price/'+$('#change_wishlist').val());

					var new_form = $('#wishlist_new_form');

					var list_name   = $("#change_wishlist option:selected").text();
					var price       = $("#change_wishlist option:selected").data('price');
					var koef        = $("#change_wishlist option:selected").data('koef');

					$('#wishlist_rename_form').attr('action',new_form.attr('action')+'/'+$('#change_wishlist').val());
					$('#wishlist_delete_form').attr('action',new_form.attr('action')+'/destroy/'+$('#change_wishlist').val());
					$('#wishlist_price_form').attr('action',new_form.attr('action')+'/set-price/'+$('#change_wishlist').val());
					$('#wishlist_add_product_form').attr('action',new_form.attr('action')+'/add-to-catalog/'+$('#change_wishlist').val());
					$('#wishlist_rename_form').find('input[name="rename"]').val(list_name);
					//$('.catalog-name').text(list_name);
					$('#wishlist_price_form').find('select').val(price);
					$('#wishlist_price_form').find('select').selectpicker('render');
					$('.coef-header').text("@lang('wishlist.table_header_user_price') x " + koef);

					if($("#change_wishlist option:selected").data('main') == 1){
						$('#delete_wishlist_btn').hide(0);
						/*$('#new_wishlist_btn').parent().addClass('offset-lg-3');
						$('#new_wishlist_btn').parent().removeClass('offset-lg-1');*/
					}else{
						$('#delete_wishlist_btn').show(0);
						/*$('#new_wishlist_btn').parent().addClass('offset-lg-1');
						$('#new_wishlist_btn').parent().removeClass('offset-lg-3');*/
					}
				}


				$('#product_select').select2({
					placeholder: "@lang('wishlist.select_product')",
					minimumInputLength: 3,
					ajax: {
						url: function () {
							return '{{route('products.search')}}'
						},
						dataType: 'json',
						data: function (params) {
							return {
								name: params.term,
								storages: '1'
							};
						},
						processResults: function (data) {
							return {
								results: data
							};
						},
						cache: true
					}
				});

				$('#wishlist_add_product_form').submit(function (e) {
					e.preventDefault();

					var form = $(this);
					var route = form.attr('action');

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
								window.table.ajax.reload();
							}
						},
						error:  function(xhr, str){
							console.log(xhr);
						}
					});

					return false;
				})

				document.getElementById('data-table-buttons').addEventListener('change', (event) => {

						if(event.target.className === "custom-select storage-catalog") {

							let product_id = event.target.attributes['product_id'].value;
							let storage_id = event.target.options[event.target.selectedIndex].attributes['value'].value;
							let route = '{{route('catalogs.change_storage')}}';

							$.ajax({
									method: "GET",
									url: route,
									data: 'product_id='+product_id+'&storage_id='+storage_id,
									success: function(resp) {
												document.getElementById('catalog_user_price_'+product_id).innerText = resp;
												document.getElementById('catalog_catalog_price_'+product_id).innerText = resp;
									},
									error: function(xhr, str) {
											console.log(xhr);
									}
							});
						}
					});



				document.querySelector('.custom-file-input').addEventListener('change',function(e){
					var fileName = document.getElementById("uploadPhoto").files[0].name;
					var nextSibling = e.target.nextElementSibling
					nextSibling.innerText = fileName
				})

				@error('name')
					$('#modal-wishlist_new').modal('show');
				@enderror


				@error('rename')
					$('#modal-wishlist_rename').modal('show');
				@enderror
			});
		})(jQuery);
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

			// let text = document.createElement('span');

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
					// if(getPriceRequestAmount > 0){
					//   // obj.value = max;
					//   const td = obj.parentNode
					//   text.innerHTML = '???????????????? ?????? ?????????? ???????????? <br> &le; ' + obj.getAttribute('datamax')
					//   td.append(text)
					//   // obj.value = obj.getAttribute('datamax')
					//   setTimeout(() => {
					//       text.innerHTML = ''
					//       td.append(text)
					//   }, 5000)
					//   getPrice = true;
					// }

					if(getPrice === true){
						//let get_price_button = $('get_price_button_'+product_id);
						document.getElementById('get_price_button_'+product_id).setAttribute('data-amount',getPriceRequestAmount);
						document.getElementById('get_price_button_'+product_id).setAttribute('data-step',step);
						document.getElementById('get_price_button_'+product_id).setAttribute('data-min',step);
						document.getElementById('get_price_button_'+product_id).click();
						obj.value = obj.getAttribute('datamax');
						amount = obj.getAttribute('datamax');
						// docu.click();
						// get_price_button.attr('data-amount',getPriceRequestAmount);
						//get_price_button.click();
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

									let add_to_order_button = document.getElementById('action_buttons_'+product_id).children[1];
									add_to_order_button.setAttribute('data-amount',amount);
							}
					});

					// $("#storages").select2({
					//   language: "ru"
					// });
			}

	</script>
@endpush
