<div class="modal fade" id="modal-wishlist_delete" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('wishlist.confirm_delete_header')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{route('catalogs.store')}}" id="wishlist_delete_form" method="post">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger m-b-0">
                        <h5><i class="fa fa-info-circle"></i>@lang('wishlist.confirm_delete_header')</h5>
                        <p>@lang('wishlist.confirm_delete_text')</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white" data-dismiss="modal">@lang('global.cancel')</a>
                    <button type="submit" class="btn btn-danger">@lang('global.confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>

