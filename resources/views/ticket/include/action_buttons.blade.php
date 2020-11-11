@if($ticket->status == 'open')
    <a href="#" class="btn btn-sm btn-danger btn-change-status" title="@lang('ticket.btn_close')" data-id="{{$ticket->id}}"><i class="fas fa-trash-alt"></i></a>
@else
    <a href="#" class="btn btn-sm btn-green btn-change-status" title="@lang('ticket.btn_open')" data-id="{{$ticket->id}}"><i class="fas fa-check"></i></a>
@endif
