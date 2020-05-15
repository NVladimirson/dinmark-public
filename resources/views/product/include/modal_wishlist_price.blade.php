<div class="modal fade" id="modal-wishlist_price" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('wishlist.modal_rename_header')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="wishlist_price_form" action="{{route('catalogs.store')}}" method="post">
                @csrf
                <div class="modal-body">
                    <select class="form-control selectpicker" name="price" data-live-search="true" data-style="btn-white">
                        <option value="">@lang('wishlist.empty_price')</option>
                        @foreach($curent_company->type_prices as $type_price)
                            <option value="{{$type_price->id}}">{{$type_price->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white" data-dismiss="modal">@lang('global.cancel')</a>
                    <button type="submit" class="btn btn-primary">@lang('global.apply')</button>
                </div>
            </form>
        </div>
    </div>
</div>

