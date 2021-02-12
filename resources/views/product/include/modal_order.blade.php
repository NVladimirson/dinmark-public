<div class="modal fade" id="modal-order" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('product.modal_order_header') <br/><strong class="product-name"></strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="form_add_order">
                <input type="hidden" name="product_id">
                <input type="hidden" name="storage_id">
                <div class="modal-body">
                    <div class="row" style="padding-left: 15px">
                    <div class="col-sm-4"><img class="image" src="https://dinmark.com.ua/images/dinmark_nophoto.jpg" height="80"></div>
                    <div class="col-sm-8">
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

                        <div class="form-group m-b-15">
                            <label>@lang('product.quantity_order')</label>
                            <input type="number" name="quantity" product_id="0" onchange="modalUpdateAmount(this)" class="form-control m-b-5" placeholder="@lang('product.quantity_order')"/>
                        </div>
                        <div class="form-group m-b-15 storage-limit-request">
                            <label>@lang('product.quantity_order_request')</label>
                            <input type="number" name="quantity_request" class="form-control m-b-5" placeholder="@lang('product.quantity_order_request')"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white" data-dismiss="modal">@lang('global.cancel')</a>
                    <button type="submit" class="btn btn-primary btn-add-order" data-btn_order="@lang('product.btn_order')" data-btn_order_request="@lang('product.btn_order_request')">@lang('global.add')</button>
                </div>
            </form>
        </div>
    </div>
</div>
