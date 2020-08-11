@extends('layouts.default')

@section('title', 'Managed Tables - Buttons')

@push('css')
	<link href="/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
	<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <style>
        #warehouse-info{
            background-color: #f0f0f0;
            padding: 10px 15px;
        }
    </style>
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
					<h4 class="panel-title">@lang('order.show_tab_name')</h4>
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
						<div class="col-lg-12">
							<div class="pull-right">
								<button type="submit" name="submit" value="save" class="btn btn-sm btn-green m-b-5 m-r-5">@lang('order.btn_new_request')</button>
								<button type="submit" name="submit" value="order" class="btn btn-sm btn-green m-b-5 m-r-5">@lang('order.btn_new_order')</button>
                                <a href="{{route('orders.copy',['id'=>$order->id])}}" class="btn btn-sm btn-primary m-b-5 m-r-5" title="@lang('order.btn_copy')"><i class="far fa-copy"></i></a>
								<a href="{{route('orders')}}" class="btn btn-sm btn-danger m-b-5 m-r-5" title="@lang('order.btn_cancel_close')"><i class="fas fa-times"></i></a>
								<a href="{{ route('orders.to_cancel',[$order->id]) }}" class="btn btn-sm btn-danger m-b-5" title="@lang('order.btn_cancel_order')"><i class="fas fa-trash-alt"></i></a>
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
										<option value="{{$user->id}}" @if(($order->customer_id == $user->id) || ( !$order->customer_id && $user->id == $order->user)) selected="selected" @endif>{{$user->name}} ({{$company->name}})</option>
									@endforeach
								@endforeach
								</optgroup>
								<optgroup label="@lang('order.select_customer_client')">
									@foreach($clients as $client)
										<option value="{{-$client->id}}" @if($client->id == -$order->customer_id) selected="selected" @endif>{{$client->name}} ({{$client->company_name}})</option>
									@endforeach
										<option value="0">@lang('order.new_client')</option>
								</optgroup>
							</select>
							<div class="form-group" id="client_data" style="display: none">
								<input class="form-control m-t-15 m-b-5 client-required" type="text" id="client_name" name="client_name" placeholder="@lang('client.table_header_name')">
                                <input class="form-control m-b-5 client-required" type="tel" id="client_phone" name="client_phone" placeholder="@lang('client.table_header_phone')">
                                <input class="form-control m-b-5 client-required" type="email" id="client_email" name="client_email" placeholder="@lang('client.table_header_email')">
                                <input class="form-control m-b-5" type="text" id="client_company" name="client_company" placeholder="@lang('client.table_header_company')">
                                <input class="form-control m-b-5" type="text" id="client_edrpo" name="client_edrpo" placeholder="@lang('client.table_header_edrpo')">
                                <textarea class="form-control m-b-5 client-required" name="client_address" id="client_address" cols="30" rows="10" placeholder="@lang('client.table_header_address')"></textarea>
                            </div>
						</div>
					</div>
                        <div class="row m-b-15">
                            <div class="col-md-6">
                                <label>@lang('order.select_payment')</label>
                                <select class="form-control selectpicker" id="payment_id" name="payment_id" data-live-search="false" data-style="btn-white">
                                    <option value="2" selected="selected">@lang('order.select_payment_cashless')</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>@lang('order.select_address')</label>
                                <select class="form-control selectpicker m-b-5" id="address_id" name="address_id" data-live-search="false" data-style="btn-white">
                                    <option value="0" selected="selected">@lang('order.select_address_new')</option>
                                </select>
                                <div class="form-group" id="shipping_data" style="/*display: none*/">
                                    <label class="m-b-0">@lang('order.select_shipping')</label>
                                    <select class="form-control selectpicker m-b-5" id="shipping_id" name="shipping_id" data-live-search="false" data-style="btn-white">
                                        @foreach($shippings as $shipping)
                                            @if($shipping->id == 4)
                                                <option value="{{$shipping->id}}" @if(empty($order->shipping_id) || $order->shipping_id==4) selected="selected" @endif>@lang('order.select_shipping_nova_poshta')</option>
                                            @else
                                                <option value="{{$shipping->id}}" @if($order->shipping_id == $shipping->id) selected="selected" @endif>{{unserialize($shipping->name)[LaravelLocalization::getCurrentLocale() == 'ua'?'uk':LaravelLocalization::getCurrentLocale()]}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @php
                                        $shipping_info = null;
                                        if($order->shipping_info){
                                            if(@unserialize($order->shipping_info) !== false){
                                                $shipping_info = unserialize($order->shipping_info);
                                            }
                                        }
                                    @endphp
                                    <div class="shipping-data " id="nova_poshta" @if(!(empty($order->shipping_id) || $order->shipping_id==4)) style="display: none"  @endif>
                                        @if( $order->shipping_id==4 && $shipping_info)

                                            <ul id="nova_poshta_tab" class="nav nav-pills">
                                                <li class="nav-item col p-0 text-center">
                                                    <a href="#wherhouse-tab" data-toggle="tab" class="nav-link @if($shipping_info['method'] == 'warehouse') active @endif">
                                                        <span>@lang('order.select_shipping_np_wherhouse')</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item col p-0 text-center">
                                                    <a href="#curier-tab" data-toggle="tab" class="nav-link @if($shipping_info['method'] == 'courier') active @endif">
                                                        <span>@lang('order.select_shipping_np_curier')</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <input type="hidden" name="np_wherhouse_curier" value="#wherhouse-tab">
                                            <div class="tab-content">
                                                <div class="tab-pane fade @if($shipping_info['method'] == 'warehouse') active @endif show" id="wherhouse-tab">
                                                    <div class="m-b-5">
                                                        <input type="hidden" name="city_np"  @if($shipping_info['method'] == 'warehouse') value="{{$shipping_info['city']}}" @endif>
                                                        <label class="m-b-0">@lang('order.select_city')</label>
                                                        <select class="form-control m-b-5" id="city_np" >
                                                            @if($shipping_info['method'] == 'warehouse')<option value="{{$shipping_info['city']}}">{{$shipping_info['city']}}</option>@endif
                                                        </select>
                                                    </div>
                                                    <div class="m-b-0">
                                                        <label class="m-b-0">@lang('order.select_warehous')</label>
                                                        <select class="form-control " id="city_np_warehouses" name="warehous_np" style="width: 100%">
                                                            @if($shipping_info['method'] == 'warehouse')<option  value="{{$shipping_info['warehouse']}}">{{$shipping_info['warehouse']}}</option>@endif
                                                        </select>
                                                        <div id="warehouse-info" style="display: none">
                                                            <div class="row ">
                                                                <div class="col-md-6">
                                                                    <strong>@lang('order.warehouse_grafic')</strong>
                                                                    <table class="table table-striped table-bordered table-td-valign-middle">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td>@lang('order.date_monday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-monday"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>@lang('order.date_tuesday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-tuesday"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>@lang('order.date_wednesday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-wednesday"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>@lang('order.date_thursday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-thursday"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>@lang('order.date_friday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-friday"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>@lang('order.date_saturday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-saturday"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>@lang('order.date_sunday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-sunday"></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong>@lang('order.warehouse_max_weight')</strong>
                                                                    <p><span id="warehouse_max_weight"></span> @lang('order.warehouse_weight_kg')</p>
                                                                    <p><strong><i class="fas fa-phone-alt"></i></strong> <sapn id="warehouse_phone"></sapn></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade @if($shipping_info['method'] == 'courier') active @endif show" id="curier-tab">
                                                    <div class="m-b-5">
                                                        <label class="m-b-0">@lang('order.select_city_input')</label>
                                                        <input type="text" class="form-control m-b-5" name="city_np_curier" @if($shipping_info['method'] == 'courier') value="{{$shipping_info['city']}}" @endif placeholder="@lang('order.select_city_input')">
                                                    </div>
                                                    <div class="m-b-5">
                                                        <label class="m-b-0">@lang('order.select_adress_input')</label>
                                                        <input type="text" class="form-control m-b-5" name="adress_np_curier" @if($shipping_info['method'] == 'courier') value="{{$shipping_info['address']}}" @endif placeholder="@lang('order.select_adress_input')">
                                                    </div>
                                                    <div class="m-b-5">
                                                        <label class="m-b-0">@lang('order.select_house_float_input')</label>
                                                        <input type="text" class="form-control m-b-5" name="house_float_np_curier"  @if($shipping_info['method'] == 'courier') value="{{$shipping_info['house_float']}}" @endif placeholder="@lang('order.select_house_float_input')">
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                        <ul id="nova_poshta_tab" class="nav nav-pills">
                                            <li class="nav-item col p-0 text-center">
                                                <a href="#wherhouse-tab" data-toggle="tab" class="nav-link active">
                                                    <span>@lang('order.select_shipping_np_wherhouse')</span>
                                                </a>
                                            </li>
                                            <li class="nav-item col p-0 text-center">
                                                <a href="#curier-tab" data-toggle="tab" class="nav-link">
                                                    <span>@lang('order.select_shipping_np_curier')</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <input type="hidden" name="np_wherhouse_curier" value="#wherhouse-tab">
                                        <div class="tab-content">
                                            <div class="tab-pane fade active show" id="wherhouse-tab">
                                                <div class="m-b-5">
                                                    <input type="hidden" name="city_np">
                                                    <label class="m-b-0">@lang('order.select_city')</label>
                                                    <select class="form-control m-b-5" id="city_np" >
                                                    </select>
                                                </div>
                                                <div class="m-b-0">
                                                    <label class="m-b-0">@lang('order.select_warehous')</label>
                                                    <select class="form-control " id="city_np_warehouses" name="warehous_np" style="width: 100%">
                                                    </select>
                                                    <div id="warehouse-info" style="display: none">
                                                        <div class="row ">
                                                            <div class="col-md-6">
                                                                <strong>@lang('order.warehouse_grafic')</strong>
                                                                <table class="table table-striped table-bordered table-td-valign-middle">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>@lang('order.date_monday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-monday"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>@lang('order.date_tuesday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-tuesday"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>@lang('order.date_wednesday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-wednesday"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>@lang('order.date_thursday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-thursday"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>@lang('order.date_friday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-friday"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>@lang('order.date_saturday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-saturday"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>@lang('order.date_sunday')</td>
                                                                            <td class="text-nowrap text-center" id="warehouse-sunday"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>@lang('order.warehouse_max_weight')</strong>
                                                                <p><span id="warehouse_max_weight"></span> @lang('order.warehouse_weight_kg')</p>
                                                                <p><strong><i class="fas fa-phone-alt"></i></strong> <sapn id="warehouse_phone"></sapn></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade show" id="curier-tab">
                                                <div class="m-b-5">
                                                    <label class="m-b-0">@lang('order.select_city_input')</label>
                                                    <input type="text" class="form-control m-b-5" name="city_np_curier" placeholder="@lang('order.select_city_input')">
                                                </div>
                                                <div class="m-b-5">
                                                    <label class="m-b-0">@lang('order.select_adress_input')</label>
                                                    <input type="text" class="form-control m-b-5" name="adress_np_curier" placeholder="@lang('order.select_adress_input')">
                                                </div>
                                                <div class="m-b-5">
                                                    <label class="m-b-0">@lang('order.select_house_float_input')</label>
                                                    <input type="text" class="form-control m-b-5" name="house_float_np_curier" placeholder="@lang('order.select_house_float_input')">
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="shipping-data " id="mist_express" @if($order->shipping_id!=2) style="display: none" @endif>
                                        <div class="m-b-5">
                                            <label class="m-b-0">@lang('order.select_city_input')</label>
                                            <input type="text" class="form-control m-b-5" name="city_me" placeholder="@lang('order.select_city_input')" @if(isset($shipping_info['city'])) value="{{$shipping_info['city']}}" @endif>
                                        </div>
                                        <div class="m-b-5">
                                            <label class="m-b-0">@lang('order.select_adress_me_input')</label>
                                            <input type="text" class="form-control m-b-5" name="adress_me" placeholder="@lang('order.select_adress_me_input')" @if(isset($shipping_info['address'])) value="{{$shipping_info['address']}}" @endif>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="m-b-15">
                                    <p class="m-b-5"><strong>@lang('wishlist.add_hand_message')</strong></p>
                                    <div class="row m-b-15">
                                        <div class="col-xl-6 m-b-15">
                                            <select class="form-control m-b-5" id="product_select" name="product_id">
                                            </select>
                                        </div>
                                        <div class="col-xl-3 col-lg-6">
                                            <input type="number" id="product_count" name="quantity" class="form-control m-b-5" value="0">
                                            <input type="hidden" id="storage_id" name="storage_id" value="1">
                                        </div>
                                        <div class="col-xl-3 col-lg-6">
                                            <button type="submit" name="submit" value="add_product" class="btn btn-sm btn-primary btn-block m-b-5">@lang('wishlist.add_product_btn')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="m-b-15">
                                    <p class="m-b-5"><strong>@lang('wishlist.add_import_message')</strong></p>
                                    <div class="row">
                                        <div class="col-xl-8">
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
                                        <div class="col-xl-4">
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

                    <div class="table-scroll-container">
					<table class="table table-striped table-bordered table-td-valign-middle m-b-15">
						<thead>
							<tr>
								<th class="text-nowrap">@lang('order.table_new_prodct')</th>
                                <th class="text-nowrap text-center" width="100">@lang('order.table_new_price')</th>
								<th class="text-nowrap text-center" width="100">@lang('order.table_new_quantity')</th>
								<th class="text-nowrap text-center" width="250">@lang('order.table_new_storage')</th>
								<th class="text-nowrap text-center" width="100">@lang('order.table_new_package')</th>
								<th class="text-nowrap text-center" width="100">@lang('order.table_new_weight')</th>
								<th class="text-nowrap text-center" width="100">@lang('order.table_new_total')</th>
								<th width="20"></th>
							</tr>
						</thead>
						<tbody>
							@foreach($products as $product)
								<tr class="product-row">
									<td><a href="{{route('products.show',[$product['product_id']])}}">{{$product['name']}}</a></td>
                                    <td class="text-nowrap text-center order-product-price" data-price="{{$product['price_raw']}}">{{$product['price']}}</td>
                                    <td class="text-nowrap text-center"><input class="form-control order-product-counter" type="number" name="product_quantity[{{$product['id']}}]" step="{{$product['min']}}" min="{{$product['min']}}" max="{{$product['max']}}" value="{{$product['quantity']}}" data-old-quantity="{{$product['quantity']}}"></td>
									<td class="text-nowrap text-center order-product-storage">
                                        <select class="form-control selectpicker m-b-5" name="product_storage[{{$product['id']}}]" data-live-search="false" data-style="btn-white">
                                        @foreach($product['storages'] as $storage)
                                                <option value="{{$storage->storage_id}}" @if($storage->storage_id == $product['storage_id']) selected="selected" @endif data-storage_min="{{$storage->package}}" data-storage_max="{{$storage->amount-($storage->amount%$storage->package)}}" data-storage-price="{{$product['storage_prices'][$storage->id]}}" data-storage_limit_1="{{$storage->limit_1}}" data-storage_limit_2="{{$storage->limit_2}}">{{$storage->storage->name}} - {{$storage->amount-($storage->amount%$storage->package)}} - {{$storage->storage->term}}</option>
                                        @endforeach
                                        </select>
                                    </td>
                                    <td class="text-nowrap text-center order-product-package">{{$product['package']}}*{{$product['min']}} @lang('global.pieces')</td>
                                    <td class="text-nowrap text-center order-product-weight" data-weight="{{$product['weight']}}">{{$product['weight']*($product['quantity']/100)}} @lang('global.kg')</td>
                                    <td class="text-nowrap text-center order-product-total" data-price="{{$product['total_raw']}}">{{$product['total']}}</td>
									<td><a href="#" data-id="{{$product['id']}}"  class="btn btn-sm btn-danger delete-product"><i class="fas fa-times"></i></a></td>
								</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td colspan="5"></td>
                                <th class="text-nowrap text-center order-weight">{{$weight}} @lang('global.kg')</th>
								<th class="text-nowrap text-center order-total" data-price="{{$order->total*$koef}}">{{number_format($order->total*$koef,2,'.',' ')}}</th>
								<td></td>
							</tr>
						</tfoot>
					</table>
                    </div>
						<div class="row">
							<div class="col-lg-6">
                                <h3>@lang('order.cp_header')</h3>
                                <div class="form-group m-b-15">
                                    <label>@lang('order.cp_price')</label>
                                    <select class="form-control selectpicker" id="cp_price_id" name="cp_price_id" data-live-search="true" data-style="btn-white">
                                        @foreach($curent_company->type_prices as $type_price)
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
				});

				$('#customer_id').change(function () {
					if($(this).val() == 0){
						$('#client_data').show(0);
						$('.client-required').attr('required','required');
					}else{
						$('#client_data').hide(0);
						$('.client-required').removeAttr('required');
					}
				});
				function numberStringFormat(nStr)
				{
					nStr = nStr.toFixed(2);
					nStr += '';
					var x = nStr.split('.');
					var x1 = x[0];
					var x2 = x.length > 1 ? '.' + x[1] : '';
					var rgx = /(\d+)(\d{3})/;
					while (rgx.test(x1)) {
						x1 = x1.replace(rgx, '$1' + ' ' + '$2');
					}
					return x1 + x2;
				}

				$('.order-product-counter').on('input change',function () {
					var row = $(this).parent().parent();
					var count = $(this).val();
					var pack = $(this).attr('min');
					var weight = +$(row).find('.order-product-weight').data('weight');
					$(row).find('.order-product-package').text(count/pack + '*' + pack + ' @lang("global.pieces")');
					$(row).find('.order-product-weight').text((weight*count/100).toFixed(3) + ' @lang("global.kg")');

					calcTotalPrice();
					calcTotalWeight();
				});

				function calcTotalWeight(){
					var totalWeight = 0;
					$('.order-product-counter').each(function (e) {
						var row = $(this).parent().parent();
						var count = $(this).val();
						var weight = +$(row).find('.order-product-weight').data('weight');
						totalWeight += (weight*count/100);
					});
					$('.order-weight').text(totalWeight.toFixed(3) + ' @lang("global.kg")');
                }

				function calcTotalPrice(){
					var total = 0
                    $('.product-row').each(function(row){
                    	var count = +$(this).find('.order-product-counter').val();
                    	var price = +$(this).find('.order-product-price').data('price');
                    	if(count >= $(this).find('.order-product-storage select option:selected').data('storage_limit_2')){
							price *= 0.93;
                        }else if(count >= $(this).find('.order-product-storage select option:selected').data('storage_limit_1')){
							price *= 0.97;
                        }

                    	$(this).find('.order-product-total').text(numberStringFormat(price*count));
						total += (price*count);
                    });
					$('.order-total').data('price',total);
					$('.order-total').text(numberStringFormat(total));
                }

				$('.order-product-storage select').change(function(e){
                    var row = $(this).parent().parent().parent();
					var selectedStorage = $(this).find("option:selected");
					var min = +selectedStorage.data('storage_min');
					var max = +selectedStorage.data('storage_max');
                    row.find('.order-product-price').data('price',selectedStorage.data('storage-price')/100);
                    row.find('.order-product-price').text(numberStringFormat(+selectedStorage.data('storage-price')));
					var count = row.find('.order-product-counter').val();
					count = (count%min == 0)?count:(count - count%min + min);
					count = count <= max? count : max;
					row.find('.order-product-counter').val(count);
					row.find('.order-product-counter').attr('min',min);
					row.find('.order-product-counter').attr('step',min);
					row.find('.order-product-counter').attr('max',max);

					row.find('.order-product-counter').change();
                });


				$('#city_np').select2({
					placeholder: "@lang('order.select_city_input')",
					minimumInputLength: 3,
					ajax: {
						url: 'https://api.novaposhta.ua/v2.0/json/',
						dataType: 'json',
						type: 'POST',
						data: function (params) {
							var query = {
								"modelName": "Address",
								"calledMethod": "searchSettlements",
								"methodProperties": {
									"CityName":params.term,
									"Limit": 10
								},
								"apiKey": "f50ab08faaad28c3a612bf9e97fb1c8a"
							};
							return JSON.stringify(query);
						},

						processResults: function (data) {
							var items = [];
							if(data.success){
								var cities = data.data[0].Addresses;
								cities.forEach(function (e) {
									items.push({'id':e.DeliveryCity,'text':e.Present});
								})
                            }
							return {
								results: items,
							};
						},
						cache: false
					},
				});

			});

			$('#city_np').change(function(e){
				var curOption = $("#city_np option:selected");
				$('input[name="city_np"]').val(curOption.text());

				var query = {
					"modelName": "AddressGeneral",
					"calledMethod": "getWarehouses",
					"methodProperties": {
						"CityRef": $(this).val(),
						"Language": "{{LaravelLocalization::getCurrentLocale() == 'ua'?'uk':LaravelLocalization::getCurrentLocale()}}"
					},
					"apiKey": "f50ab08faaad28c3a612bf9e97fb1c8a"
				};
				var data =  JSON.stringify(query);

				$.ajax({
					method: "POST",
					url: "https://api.novaposhta.ua/v2.0/json/",
                    data: data,
					dataType: 'json',
					success: function(resp)
					{
						$('#city_np_warehouses').html('');
						$('#warehouse-info').hide();

						if(resp.success){
							resp.data.forEach(function(e){
								$('#city_np_warehouses').append('<option val="'+e.Description+'" ' +
                                    'data-monday="'+e.Schedule.Monday+'"' +
                                    'data-tuesday="'+e.Schedule.Tuesday+'"' +
                                    'data-wednesday="'+e.Schedule.Wednesday+'"' +
                                    'data-thursday="'+e.Schedule.Thursday+'"' +
                                    'data-friday="'+e.Schedule.Friday+'"' +
                                    'data-saturday="'+e.Schedule.Saturday+'"' +
                                    'data-sunday="'+e.Schedule.Sunday+'"' +
                                    'data-weight="'+(e.PlaceMaxWeightAllowed>e.TotalMaxWeightAllowed?e.PlaceMaxWeightAllowed:e.TotalMaxWeightAllowed)+'"' +
                                    'data-phone="'+e.Phone+'"' +
                                    '>'+e.Description+'</option>');
                            });
							$('#city_np_warehouses').change();
						}
					},
					error:  function(xhr, str){
						console.log(xhr);
					}
				});
            });

			$('#city_np_warehouses').select2();

			$('#city_np_warehouses').change(function (e) {

				var curOption = $("#city_np_warehouses option:selected");
				$('#warehouse-info').show();
				$('#warehouse-monday').text(curOption.data('monday'));
				$('#warehouse-tuesday').text(curOption.data('tuesday'));
				$('#warehouse-wednesday').text(curOption.data('wednesday'));
				$('#warehouse-thursday').text(curOption.data('thursday'));
				$('#warehouse-friday').text(curOption.data('friday'));
				$('#warehouse-saturday').text(curOption.data('saturday'));
				$('#warehouse-sunday').text(curOption.data('sunday'));
				$('#warehouse_max_weight').text(curOption.data('weight'));
				$('#warehouse_phone').text(curOption.data('phone'));

			});

			$('#nova_poshta_tab a').click(function(e){
				$('input[name="np_wherhouse_curier"]').val($(this).attr('href'));
            });

			$('#shipping_id').change(function(e){
				$('.shipping-data').hide();
				switch ($(this).val()) {
                    case "4":
                    	$('#nova_poshta').show();
                    	break;

                    case "2":
                    	$('#mist_express').show();
						break;
				}
            });

		})(jQuery);
	</script>
@endpush
