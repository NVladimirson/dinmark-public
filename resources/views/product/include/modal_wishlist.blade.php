<div class="modal fade" id="modal-wishlist" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('product.modal_wishlist_header')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="form_add_catalog">
                <input type="hidden" class="product_id" name="product_id">
                <input type="hidden" class="old_catalog_id" name="old_catalog_id">

                <div class="modal-body">
                    <p>
                        <select class="form-control selectpicker" id="wishlist" data-live-search="true" data-style="btn-white">
                            @foreach($wishlists as $wishlist)
                                <option value="{{$wishlist->id}}">{{$wishlist->name}}</option>
                            @endforeach
                        </select>
                    </p>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white" data-dismiss="modal">@lang('global.cancel')</a>
                    <button type="submit" class="btn btn-primary">@lang('global.add')</button>
                </div>
            </form>
        </div>
    </div>
</div>

