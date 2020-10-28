<div class="modal fade" id="modal-order_multiple" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('product.modal_order_header') <br/><strong class="product-name"></strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="form_add_order_multiple">
                {{--<input type="hidden" class="product_id" name="product_id" id="products_order">--}}
                {{--<input type="hidden" class="storage_id" name="storage_id" id="storages_order">--}}
                <div class="modal-body" id="append_to">

                        {{--<div class="col-xl-3"><img class="image" src="" width="80"></div>--}}
                        {{--<div class="col-xl-9"><p class="name"></p></div>--}}

                    <div class="form-group m-b-15">
                        <label>@lang('product.select_order')</label>
                        <select class="form-control selectpicker" id="order_multiple_id" data-live-search="true" data-style="btn-white">
                            <option value="0">@lang('product.new_order')</option>
                            @foreach($orders as $order)
                                <option value="{{$order->id}}">{{$order->id}} ({{Carbon\Carbon::parse($order->date_add)->format('d.m.Y')}})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group m-b-15">
                        <select class="browser-default custom-select" id="products_selector">

                        </select>
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

