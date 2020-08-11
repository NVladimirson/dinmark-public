@extends('layouts.default')

@section('title', 'Managed Tables - Buttons')

@push('css')
	<link href="/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
	<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
@endpush

@section('content')
	{{ Breadcrumbs::render('order.show',$order) }}

	<h1 class="page-header">@lang('order.page_update')№{{$order->id.' / '.(($order->public_number)?$order->public_number:'-')}} @lang('order.from') {{Carbon\Carbon::parse($order->date_add)->format('d.m.Y')}}</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">

                    <ul id="order_tab" class="nav nav-tabs nav-tabs-panel panel-title">
                        <li class="nav-item">
                            <a href="#order-tab" data-toggle="tab" class="nav-link active">
                                <span>@lang('order.page_update')<br>{{$order->id.' / '.(($order->public_number)?$order->public_number:'-')}}</span>
                            </a>
                        </li>
                        @foreach($implementationsData as $implementation)
                        <li class="nav-item">
                            <a href="#implementation-tab-{{$implementation['id']}}" data-toggle="tab" class="nav-link">
                                <span>@lang('order.implementation_number')<br>{{$implementation['public_number']}}</span>
                            </a>
                        </li>
                        @endforeach
                        @foreach($order->payments as $payment)
                        <li class="nav-item">
                            <a href="#payment-tab-{{$payment->id}}" data-toggle="tab" class="nav-link">
                                <span>@lang('order.payment_number')<br>{{$payment->public_number}}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="order-tab">
                            <form action="{{route('orders.update', [$order->id])}}" enctype="multipart/form-data" method="post">
                                @csrf

                                <input type="hidden" id="order_id" name="order_id" value="{{$order->id}}">

                                <div class="row m-b-15">
                                    <div class="col-md-8">
                                        <table class="table table-striped table-bordered table-td-valign-middle m-b-15">
                                            <tbody>
                                            <tr>
                                                <th>@lang('order.table_header_date_create')</th>
                                                <td>{{\Carbon\Carbon::parse($order->date_add)->format('d.m.Y h:i')}}</td>
                                                <th>@lang('order.table_header_shipping_method')</th>
                                                <td>
                                                    @if($order->shipping_id != 4)
                                                        @if($shippings->firstWhere('id',$order->shipping_id))
                                                            {{unserialize($shippings->firstWhere('id',$order->shipping_id)->name)[LaravelLocalization::getCurrentLocale() == 'ua'?'uk':LaravelLocalization::getCurrentLocale()]}}
                                                        @endif
                                                    @else
                                                        @lang('order.select_shipping_nova_poshta')
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>@lang('order.table_header_date_update')</th>
                                                <td>{{\Carbon\Carbon::parse($order->date_edit)->format('d.m.Y h:i')}}</td>
                                                <th>@lang('order.table_header_client')</th>
                                                <td>{{$order->getUser->getCompany->name}}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('order.table_header_status')</th>
                                                <td>{{$order->getStatus->name}}</td>
                                                <th>@lang('order.table_header_manager')</th>
                                                <td>@if($order->getUser) {{$order->getUser->name}} @endif</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('order.table_header_status_payment')</th>
                                                @if($order->payments->count() > 0)
                                                    @if($order->payments->sum('payed') < $order->total)
                                                        <td>@lang('order.payment_status_partial')</td>
                                                    @else
                                                        <td>@lang('order.payment_status_success')</td>
                                                    @endif
                                                @else
                                                    <td>@lang('order.payment_status_none')</td>
                                                @endif
                                                <th>@lang('order.table_new_weight')</th>
                                                <td>{{$weight}} @lang('global.kg')</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="pull-right">
                                            @if($order->status == 1 || $order->status == 2)
                                                <a href="{{ route('orders.pdf_bill',[$order->id]) }}" class="btn btn-sm btn-primary m-b-5 m-r-5">@lang('order.btn_pdf_bill')</a>
                                            @endif
                                            @if($order->status == 7)
                                                <a href="{{ route('orders.to_order',[$order->id]) }}" class="btn btn-sm btn-green m-b-5 m-r-5">@lang('order.btn_open_order')</a>
                                            @endif
                                                <a href="{{route('orders.copy',['id'=>$order->id])}}" class="btn btn-sm btn-primary m-b-5 m-r-5" title="@lang('order.btn_copy')"><i class="far fa-copy"></i></a>
                                                <a href="{{route('orders')}}" class="btn btn-sm btn-danger m-b-5 m-r-5" title="@lang('order.btn_cancel_close')"><i class="fas fa-times"></i></a>
                                            @if($order->status == 1)
                                                <a href="{{ route('orders.to_cancel',[$order->id]) }}" class="btn btn-sm btn-danger m-b-5" title="@lang('order.btn_cancel_order')"><i class="fas fa-trash-alt"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-b-15">
                                    <div class="col-md-6">
                                        <label>@lang('order.select_sender')</label>
                                        <select class="form-control selectpicker" id="sender_id" name="sender_id" data-live-search="true" data-style="btn-white" disabled>
                                            <option value="0" @if($order->sender_id == 0) selected="selected" @endif>@lang('order.sender_dinmark')</option>
                                            @foreach($companies as $company)
                                                <option value="{{$company->id}}" @if($company->id == $order->sender_id) selected="selected" @endif>{{$company->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('order.select_customer')</label>
                                        <select class="form-control selectpicker" id="customer_id" name="customer_id" data-live-search="true" data-style="btn-white" disabled>
                                            <optgroup label="@lang('order.select_customer_user')">
                                                @foreach($companies as $company)
                                                    @foreach($company->users as $user)
                                                        <option value="{{$user->id}}" @if(($order->customer_id == $user->id) || ( !$order->customer_id && $user->id == $order->user)) selected="selected" @endif>{{$user->name}} ({{$company->name}})</option>
                                                    @endforeach
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="@lang('order.select_customer_client')">
                                                @foreach($clients as $client)
                                                    <option value="{{-$client->id}}" @if($client->id == -$order->customer_id) selected="selected" @endif>{{$client->name}} ({{$client->company_name}})</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="row m-b-15">
                                    <div class="col-md-6">
                                        <label>@lang('order.select_payment')</label>
                                        <select class="form-control selectpicker" disabled="disabled" id="payment_id" name="payment_id" data-live-search="false" data-style="btn-white">
                                            <option value="2" selected="selected">@lang('order.select_payment_cashless')</option>
                                        </select>
                                    </div>
                                    @php
                                        $shipping_info = null;
                                        if($order->shipping_info){
                                            if(@unserialize($order->shipping_info) !== false){
                                                $shipping_info = unserialize($order->shipping_info);
                                            }
                                        }
                                    @endphp
                                    @if($shipping_info)
                                    <div class="col-md-6">
                                        <label>@lang('order.select_address')</label>
                                        <table class="table table-striped table-bordered table-td-valign-middle m-b-15">
                                            @if(isset($shipping_info['city']))
                                            <tr>
                                                <th>@lang('order.table_shipping_city')</th>
                                                <td>{{$shipping_info['city']}}</td>
                                            </tr>
                                            @endif
                                            @if(isset($shipping_info['warehouse']))
                                            <tr>
                                                <th>@lang('order.table_shipping_warehouse')</th>
                                                <td>{{$shipping_info['warehouse']}}</td>
                                            </tr>
                                            @endif
                                            @if(isset($shipping_info['address']) && $order->shipping_id == 4)
                                            <tr>
                                                <th>@lang('order.table_shipping_address')</th>
                                                <td>{{$shipping_info['address']}}</td>
                                            </tr>
                                            @endif
                                            @if(isset($shipping_info['address']) && $order->shipping_id != 4)
                                            <tr>
                                                <th>@lang('order.table_shipping_address_me')</th>
                                                <td>{{$shipping_info['address']}}</td>
                                            </tr>
                                            @endif
                                            @if(isset($shipping_info['house_float']))
                                            <tr>
                                                <th>@lang('order.table_shipping_house_float')</th>
                                                <td>{{$shipping_info['house_float']}}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                    @endif
                                </div>

                                <div class="table-scroll-container">
                                    <table class="table table-striped table-bordered table-td-valign-middle m-b-15">
                                        <thead>
                                        <tr>
                                            <th class="text-nowrap">@lang('order.table_new_prodct')</th>
                                            <th class="text-nowrap text-center" width="100">@lang('order.table_new_price')</th>
                                            <th class="text-nowrap text-center" width="100">@lang('order.table_new_quantity')</th>
                                            <th class="text-nowrap text-center" width="200">@lang('order.table_new_storage')</th>
                                            <th class="text-nowrap text-center" width="100">@lang('order.table_new_package')</th>
                                            <th class="text-nowrap text-center" width="100">@lang('order.table_new_weight')</th>
                                            <th class="text-nowrap text-center" width="100">@lang('order.table_new_total')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($products as $product)
                                            <tr>
                                                <td><a href="{{route('products.show',[$product['product_id']])}}">{{$product['name']}}</a></td>
                                                <td class="text-nowrap text-center">{{$product['price']}}</td>
                                                <td class="text-nowrap text-center">{{$product['quantity']}}</td>
                                                <td class="text-nowrap text-center order-product-storage">
                                                    @foreach($product['storages'] as $storage)
                                                        @if($storage->storage_id == $product['storage_id'])
                                                            {{$storage->storage->name}} - {{$storage->amount-($storage->amount%$storage->package)}} - {{$storage->storage->term}}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td class="text-nowrap text-center order-product-package">{{$product['package']}}*{{$product['min']}} @lang('global.pieces')</td>
                                                <td class="text-nowrap text-center order-product-weight">{{$product['weight']*($product['quantity']/100)}} @lang('global.kg')</td>
                                                <td class="text-nowrap text-center">{{$product['total']}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="5"></td>
                                            <th class="text-nowrap text-center order-weight">{{$weight}} @lang('global.kg')</th>
                                            <th class="text-nowrap text-center">{{number_format($order->total*$koef,2,'.',' ')}}</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group m-b-15">
                                            <label>@lang('order.form_comment')</label>
                                            <textarea name="comment" id="comment" cols="30" rows="5" class="form-control m-b-5" readonly>{{$order->comment}}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        @foreach($implementationsData as $implementation)
                        <div class="tab-pane fade" id="implementation-tab-{{$implementation['id']}}">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-striped table-bordered table-td-valign-middle m-b-15">
                                        <tbody>
                                            <tr>
                                                <th>@lang('implementation.table_header_data')</th>
                                                <td>{{$implementation['date']}}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('implementation.table_header_sender')</th>
                                                <td>{{$implementation['sender']}}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('implementation.table_header_customer')</th>
                                                <td>{{$implementation['customer'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('implementation.table_header_ttn')</th>
                                                <td>{{$implementation['ttn'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <div class="pull-right">
                                        <a href="{{route('implementations.pdf',[$implementation['id']])}}" class="btn btn-sm btn-primary">{{trans('implementation.btn_generate_pdf')}}</a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-td-valign-middle m-b-15">
                                        <thead>
                                        <tr>
                                            <th class="text-nowrap">@lang('implementation.table_product_name')</th>
                                            <th class="text-nowrap text-center">@lang('implementation.table_product_quantity')</th>
                                            <th class="text-nowrap text-center">@lang('implementation.table_product_total')</th>
                                            <th class="text-nowrap text-center">@lang('implementation.table_product_order')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($implementation['products'] as $product)
                                            <tr>
                                                <td><a href="{{route('products.show',[$product['product_id']])}}">{{$product['name']}}</a></td>
                                                <td class="text-nowrap text-center">{{$product['quantity']}}</td>
                                                <td class="text-nowrap text-center">{{$product['total']}}</td>
                                                <td class="text-nowrap text-center"><a href="{{route('orders.show',[$product['order']])}}" target="_blank">{{$product['order_number']}}</a></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @foreach($order->payments as $payment)
                            <div class="tab-pane fade" id="payment-tab-{{$payment->id}}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-striped table-bordered table-td-valign-middle m-b-15">
                                            <tbody>
                                            <tr>
                                                <th>@lang('order.table_payment_date')</th>
                                                <td>{{Carbon\Carbon::parse($payment->date_add)->format('d.m.Y i:h')}}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('order.table_payment_total')</th>
                                                <td>{{number_format($payment->payed,2,'.',' ')}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
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

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {
				var ajaxRouteBase = "{!! route('orders.all_ajax') !!}";

				$('#status').change(function () {
					var ajaxRoute = ajaxRouteBase+'?status_id='+$(this).val();
					window.table.ajax.url( ajaxRoute ).load();
				});

				window.table = $('#data-table-buttons').DataTable( {
					"language": {
						"url": "@lang('table.localization_link')",
					},
					"pageLength": 25,
					"autoWidth": true,
					"processing": true,
					"serverSide": true,
					"ajax": "{!! route('orders.all_ajax') !!}",
					"order": [[ 0, "desc" ]],
					"columns": [
						{
							className: 'text-center',
							data: 'number_html',
						},
						{
							data: 'date_html',
						},
						{
							data: 'status_html',
						},
						{
							data: 'payment_html',
							"visible": false,
						},
						{
							data: 'total_html',
						},
						{
							data: 'customer',
						},
						{
							data: 'author',
							"orderable":      false,
						},
						{
							data: 'actions',
						},
					],
				} );
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
					},
					templateSelection: function(container) {
						$(container.element).attr("data-min", container.min);
						$(container.element).attr("data-max", container.max);
						$(container.element).attr("data-storage_id", container.storage_id);
						return container.text;
					}
				});

				$('#product_select').change(function () {
					var curOption = $("#product_select option:selected");
					$('#storage_id').val(curOption.data('storage_id'));
					$('#product_count').val(curOption.data('min'));
					$('#product_count').attr('min',curOption.data('min'));
					$('#product_count').attr('step',curOption.data('min'));
					$('#product_count').attr('max',curOption.data('max'));
				});

				$('.delete-product').click(function (e) {
					e.preventDefault();

					var btn = $(this);
					var route = "{{route('orders')}}/remove-of-order/"+btn.data('id');

					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					$.ajax({
						method: "POST",
						url: route,
						success: function(resp)
						{
							if(resp == "ok"){
								btn.parent().parent().remove();
								window.location.reload();
							}
						},
						error:  function(xhr, str){
							console.log(xhr);
						}
					});

					return false;
				})

			});
		})(jQuery);
	</script>
@endpush
