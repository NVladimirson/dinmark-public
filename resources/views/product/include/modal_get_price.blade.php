<div class="modal fade" id="modal-get_price" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('product.modal_get_price_header')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="form_get_price">
                <input type="hidden" id="get_price_product_id" class="product_id" name="product_id">
                <div class="modal-body">
                    <div class="form-group m-b-15">
                        <label>@lang('product.name_get_price')</label>
                        <input type="text" name="name" class="form-control m-b-5" placeholder="@lang('product.name_get_price')" required value="{{auth()->user()->name}}"/>
                    </div>
                    <div class="form-group m-b-15">
                        <label>@lang('product.phone_get_price')</label>
                        <input type="text" name="phone" class="form-control m-b-5" placeholder="@lang('product.phone_get_price')" required value="{{ (auth()->user()->info->firstWhere('field','phone'))? auth()->user()->info->firstWhere('field','phone')->value : '' }}"/>
                    </div>
                    <div class="form-group m-b-15">
                        <label>@lang('product.quantity_get_price')</label>
                        <input type="number" name="quantity" class="form-control m-b-5" placeholder="@lang('product.quantity_get_price')" value="100" step="100" required/>
                    </div>
                    <div class="form-group m-b-15">
                        <label>@lang('product.get_price_comment')</label>
                        <textarea name="comment" class="form-control m-b-5" cols="30" rows="5" placeholder="@lang('product.get_price_comment')"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white" data-dismiss="modal">@lang('global.cancel')</a>
                    <button type="submit" class="btn btn-primary">@lang('product.modal_get_price_submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

