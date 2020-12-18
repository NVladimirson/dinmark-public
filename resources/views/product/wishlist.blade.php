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
								<th></th>
								<th width="30"></th>
								<th width="1%" data-orderable="false"></th>
                               <th class="text-nowrap">@lang('wishlist.table_header_name_article')</th>
								{{--<th class="text-nowrap">@lang('wishlist.table_header_name')</th>--}}
								{{--<th class="text-nowrap">@lang('wishlist.table_header_article')</th>--}}
								<th class="text-nowrap">@lang('wishlist.table_header_holding_article')</th>
								<th>USER</th>
								<!-- <th class="text-nowrap">@lang('wishlist.table_header_price')</th>
								<th class="text-nowrap coef-header">@lang('wishlist.table_header_user_price') x {{
				($curentWishlist->price)? $curentWishlist->price->koef : 1 }}</th> -->
								<th class="text-nowrap">@lang('wishlist.table_header_storage')</th>
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
					"ajax": ajaxRoute,
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
							data: 'check_html',
							"visible": true	,
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
							data: 'storage_html',
							"orderable":      false,
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



				$('#modal-order').on('show.bs.modal', function (event) {
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

				$('#modal-get_price').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var modal = $(this);
					modal.find('.product_id').val(button.data('product_id'));
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
								window.table.ajax.reload();
							}
						},
						error:  function(xhr, str){
							console.log(xhr);
						}
					});

					return false;
				})

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
								name: params.term
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
						method: "GET",
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
@endpush
