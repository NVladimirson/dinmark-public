<div class="modal fade" id="modal_explanation" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="form_explantion">
                <input type="hidden" name="explanation_subject">
                <div class="modal-body">
                    <div class="form-group m-b-15">
                        <label>@lang('order.explanation_message')</label>
                        <textarea class="form-control" name="explanation_message" id="explanation_message" cols="30" rows="10" placeholder="@lang('order.explanation_message')" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white" data-dismiss="modal">@lang('global.cancel')</a>
                    <button type="submit" class="btn btn-primary">@lang('order.explanation_submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
