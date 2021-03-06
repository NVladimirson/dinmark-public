<div class="modal fade" id="modal-get_price" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('product.modal_get_price_header')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="form_get_price">
              <div class="row" style="padding-left: 15px">
                  <div class="col-xl-4"><img class="image" src="https://dinmark.com.ua/images/dinmark_nophoto.jpg" height="80"></div>
                  <div class="col-xl-8"><p class="name"></p></div>
              </div>
                <input type="hidden" id="get_price_product_id" class="product_id" name="product_id">
                <div class="modal-body">
                    <div class="form-group m-b-15">
                        <!-- <label>@lang('product.name_get_price')</label> -->
                        <input type="hidden" name="name" class="form-control m-b-5" placeholder="@lang('product.name_get_price')" required value="{{auth()->user()->name}}"/>
                    </div>
                    <div class="form-group m-b-15">
                        <!-- <label>@lang('product.phone_get_price')</label> -->
                        <input type="hidden" name="phone" class="form-control m-b-5" placeholder="@lang('product.phone_get_price')" required value="{{ (auth()->user()->info->firstWhere('field','phone'))? auth()->user()->info->firstWhere('field','phone')->value : '' }}"/>
                    </div>
                    <div class="form-group m-b-15">
                        <label>@lang('product.quantity_get_price')</label>
                        <input type="number" name="quantity" class="form-control m-b-5 quantity" placeholder="@lang('product.quantity_get_price')" min="1" value="1" step="1" required/>
                    </div>
                    <div class="form-group m-b-15">
                        <label>@lang('product.get_price_comment')</label>
                        <textarea name="comment" class="form-control m-b-5 comment" cols="30" rows="5" placeholder="@lang('product.get_price_comment')"></textarea>
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
