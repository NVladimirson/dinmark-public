<div class="modal fade" id="modal-order" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('product.modal_order_header')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="form_add_order">
                <input type="hidden" class="product_id" name="product_id">
                <input type="hidden" class="storage_id" name="storage_id">
                <div class="modal-body">
                    <div class="form-group m-b-15">
                        <label>@lang('product.select_order')</label>
                        <select class="form-control selectpicker" id="order_id" data-live-search="true" data-style="btn-white">
                            <option value="0">@lang('product.new_order')</option>
                            @foreach($orders as $order)
                                <option value="{{$order->id}}">{{$order->id}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group m-b-15">
                        <label>@lang('product.quantity_order')</label>
                        <input type="number" name="quantity" class="form-control m-b-5" placeholder="@lang('product.quantity_order')" value="100" min="100" step="100" max="1000"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white" data-dismiss="modal">@lang('global.cancel')</a>
                    <button type="submit" class="btn btn-primary">@lang('global.add')</button>
                </div>
            </form>
        </div>
    </div>
</div>

