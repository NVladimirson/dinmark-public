<div class="modal fade" id="modal-wishlist_new" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('wishlist.modal_new_header')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="wishlist_new_form" action="{{route('catalogs.store')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group m-b-15">
                        <label>@lang('wishlist.name')</label>
                        <input type="text" name="name" class="form-control m-b-5 @error('name') is-invalid @enderror" placeholder="@lang('wishlist.name')" value="{{old('name')}}"/>
                        @error('name')
                        <span class="invalid-feedback " role="alert">
                                 <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white" data-dismiss="modal">@lang('global.cancel')</a>
                    <button type="submit" class="btn btn-primary">@lang('global.create')</button>
                </div>
            </form>
        </div>
    </div>
</div>

