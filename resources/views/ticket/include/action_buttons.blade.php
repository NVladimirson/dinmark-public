@if($ticket->status == 'open')
    <a href="#" class="btn btn-sm btn-danger btn-change-status" title="@lang('order.btn_pdf_bill')" data-id="{{$ticket->id}}"><i class="fas fa-times"></i></a>
@else
    <a href="#" class="btn btn-sm btn-green btn-change-status" title="@lang('order.btn_pdf_bill')" data-id="{{$ticket->id}}"><i class="fas fa-check"></i></a>
@endif
