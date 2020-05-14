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

	<h1 class="page-header">@lang('order.order_number'){{$order->id.' / '.(($order->public_number)?$order->public_number:'-')}} @lang('order.from') {{Carbon\Carbon::parse($order->date_add)->format('d.m.Y')}}</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('order.all_tab_name')</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<form action="{{route('orders.update', [$order->id])}}" enctype="multipart/form-data" method="post">
						@csrf

						<input type="hidden" id="order_id" name="order_id" value="{{$order->id}}">
					<div class="row m-b-15">
						<div class="col-lg-3">
							<h3 class="m-b-5">
								@lang('order.order_number'){{$order->id.' / '.(($order->public_number)?$order->public_number:'-')}}
							</h3>
						</div>
						<div class="col-lg-9">
							<div class="pull-right">
								<button type="submit" name="submit" value="save" class="btn btn-sm btn-primary m-b-5 m-r-5" data-toggle="modal">@lang('order.btn_new_request')</button>
								<button type="submit" name="submit" value="order" class="btn btn-sm btn-primary m-b-5 m-r-5" data-toggle="modal">@lang('order.btn_new_order')</button>
								<button type="submit" name="submit" value="cancel" class="btn btn-sm btn-danger m-b-5" data-toggle="modal">@lang('order.btn_cancel_order')</button>
							</div>
						</div>
					</div>
					<div class="row m-b-15">
						<div class="col-md-6">
							<label>@lang('order.select_sender')</label>
							<select class="form-control selectpicker" id="sender_id" name="sender_id" data-live-search="true" data-style="btn-white">
								<option value="0" @if($order->sender_id == 0) selected="selected" @endif>@lang('order.sender_dinmark')</option>
								@foreach($companies as $company)
									@foreach($company->users as $user)
									<option value="{{$user->id}}" @if($user->id == $order->sender_id) selected="selected" @endif>{{$user->name}} ({{$company->name}})</option>
									@endforeach
								@endforeach
							</select>
						</div>
						<div class="col-md-6">
							<label>@lang('order.select_customer')</label>
							<select class="form-control selectpicker" id="customer_id" name="customer_id" data-live-search="true" data-style="btn-white">
								<optgroup label="@lang('order.select_customer_user')">
								@foreach($companies as $company)
									@foreach($company->users as $user)
										<option value="{{$user->id}}" @if($user->id == $order->user) selected="selected" @endif>{{$user->name}} ({{$company->name}})</option>
									@endforeach
								@endforeach
								</optgroup>
								<optgroup label="@lang('order.select_customer_client')">
									@foreach($clients as $client)
										<option value="{{-$client->id}}" @if($client->id == -$order->user) selected="selected" @endif>{{$client->name}} ({{$client->company_name}})</option>
									@endforeach
								</optgroup>
							</select>
						</div>
					</div>
						<div class="row">
							<div class="col-lg-8">
								<div class="m-b-15">
									<p class="m-b-5"><strong>@lang('wishlist.add_hand_message')</strong></p>
									<div class="row m-b-15">
										<div class="col-lg-8">
											<select class="form-control m-b-5" id="product_select" name="product_id">
											</select>
										</div>
										<div class="col-lg-2">
											<input type="number" id="product_count" name="quantity" class="form-control m-b-5" value="0">
											<input type="hidden" id="storage_id" name="storage_id" value="1">
										</div>
										<div class="col-lg-2">
											<button type="submit" name="submit" value="add_product" class="btn btn-sm btn-primary btn-block m-b-5">@lang('wishlist.add_product_btn')</button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="m-b-15">
									<p class="m-b-5"><strong>@lang('wishlist.add_import_message')</strong></p>
									<div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<div class="input-group mb-3 @error('import') is-invalid @enderror">
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
											<button type="submit" name="submit" value="import_product" class="btn btn-sm btn-primary btn-block m-b-5">@lang('wishlist.import_product_btn')</button>
										</div>
									</div>
									<p class="m-b-0">@lang('wishlist.import_file_note') <a href="{{asset('import/order_import.xlsx')}}" target="_blank">@lang('wishlist.import_file_example')</a></p>
								</div>
							</div>
						</div>

                        @if(session()->has('not_founds') || session()->has('not_available'))
                            <div class="row">
                                <div class="col-md-12">
                                    @if(session()->has('not_founds'))
                                        <div class="alert alert-danger fade show col-12">
                                            <h3>@lang('order.import_not_found')</h3>
                                            <ul class="m-b-0">
                                                @foreach(session('not_founds') as $article)
                                                    <li>{{$article}}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if(session()->has('not_available'))
                                        <div class="alert alert-danger fade show col-12">
                                            <h3>@lang('order.import_not_available')</h3>
                                            <ul class="m-b-0">
                                                @foreach(session('not_available') as $article)
                                                    <li>{{$article}}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif


					<table class="table table-striped table-bordered table-td-valign-middle m-b-15">
						<thead>
							<tr>
								<th class="text-nowrap">@lang('order.table_new_prodct')</th>
								<th class="text-nowrap text-center">@lang('order.table_new_quantity')</th>
								<th class="text-nowrap text-center">@lang('order.table_new_price')</th>
								<th class="text-nowrap text-center">@lang('order.table_new_total')</th>
								<th width="20"></th>
							</tr>
						</thead>
						<tbody>
							@foreach($products as $product)
								<tr>
									<td>{{$product['name']}}</td>
									<td class="text-nowrap text-center">{{$product['quantity']}}</td>
									<td class="text-nowrap text-center">{{$product['price']}}</td>
									<td class="text-nowrap text-center">{{$product['total']}}</td>
									<td><a href="#" data-id="{{$product['id']}}"  class="btn btn-sm btn-danger delete-product"><i class="fas fa-times"></i></a></td>
								</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3"></td>
								<th class="text-nowrap text-center">{{number_format($order->total*$koef,2,'.',' ')}}</th>
								<td></td>
							</tr>
						</tfoot>
					</table>
						<div class="row">
							<div class="col-lg-6">
                                <h3>@lang('order.cp_header')</h3>
                                <div class="form-group m-b-15">
                                    <label>@lang('order.cp_price')</label>
                                    <select class="form-control selectpicker" id="cp_price_id" name="cp_price_id" data-live-search="true" data-style="btn-white">
                                        @foreach(auth()->user()->getCompany->type_prices as $type_price)
                                            <option value="{{$type_price->id}}">{{$type_price->name}} ({{$type_price->koef}})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group m-b-15">
                                    <label>@lang('order.cp_user')</label>
                                    <select class="form-control selectpicker" id="cp_cliet_id" name="cp_client_id" data-live-search="true" data-style="btn-white">
										@foreach($clients as $client)
											<option value="{{$client->id}}" @if($client->id == -$order->user) selected="selected" @endif>{{$client->name}} ({{$client->company_name}})</option>
										@endforeach
                                        {{--@foreach($companies as $company)
                                            @foreach($company->users as $user)
                                                <option value="{{$user->id}}" @if($user->id == $order->user) selected="selected" @endif>{{$user->name}} ({{$company->name}})</option>
                                            @endforeach
                                        @endforeach--}}
                                    </select>
                                </div>
                                <button type="submit" name="submit" value="cp_generate" class="btn btn-lg btn-primary">@lang('order.cp_btn')</button>
							</div>
							<div class="col-lg-6">
								<div class="form-group m-b-15">
									<label>@lang('order.form_comment')</label>
									<textarea name="comment" id="comment" cols="30" rows="5" class="form-control m-b-5">{{$order->comment}}</textarea>
								</div>
							</div>
						</div>

					</form>
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