<div class="modal fade" id="modal-order" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('product.modal_order_header') <br/><strong class="product-name"></strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="form_add_order">
                <div class="row" style="padding-left: 15px">
                    <div class="col-xl-4"><img class="image" src="'{{env('DINMARK_URL')}}'images/dinmark_nophoto.jpg" height="80"></div>
                    <div class="col-xl-8"><p class="name"></p></div>
                </div>
                <input type="hidden" class="product_id" name="product_id">
                <input type="hidden" class="storage_id" name="storage_id">
                {{--<input type="hidden" class="amount" name="amount">--}}
                {{--<input class="quantity" name="quantity" placeholder="@lang('product.quantity_order')" disabled>--}}
                {{--<input class="quantity_request" name="quantity_request" placeholder="@lang('product.quantity_order_request')" disabled>--}}
                <div class="modal-body">
                    <div class="form-group m-b-15">
                        <label>@lang('product.select_order')</label>
                        <select class="form-control selectpicker" id="order_id" data-live-search="true" data-style="btn-white">
                            <option value="0">@lang('product.new_order')</option>
                            @foreach($orders as $order)
                                <option value="{{$order->id}}">{{$order->id}} ({{Carbon\Carbon::parse($order->date_add)->format('d.m.Y')}})</option>
                            @endforeach
                        </select>
                    </div>
                    {{--<div class="form-group m-b-15 storage-limit-info">--}}
                    {{--<strong>@lang('product.modal_order_warning_1')<span class="order-storage-amount"></span>@lang('product.modal_order_warning_2')</strong>--}}
                    {{--</div>--}}
                    <div id="modal_order_inputs">

                    </div>
                    {{--<div class="form-group m-b-15">--}}
                    {{--<label>@lang('product.quantity_order')</label>--}}
                    {{--<input type="number" name="quantity" class="form-control m-b-5 quantity" placeholder="@lang('product.quantity_order')" disabled/>--}}
                    {{--</div>--}}
                    {{--<div class="form-group m-b-15 storage-limit-request">--}}
                    {{--<label>@lang('product.quantity_order_request')</label>--}}
                    {{--<input type="number" name="quantity_request" class="form-control m-b-5 quantity_request" placeholder="@lang('product.quantity_order_request')" disabled/>--}}
                    {{--</div>--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white" data-dismiss="modal">@lang('global.cancel')</a>
                    <button type="submit" class="btn btn-primary btn-add-order" data-btn_order="@lang('product.btn_order')" data-btn_order_request="@lang('product.btn_order_request')">@lang('global.add')</button>
                </div>
            </form>
        </div>
    </div>
</div>

