@if($order->status >= 7)
<a href="{{route('orders.to_order',['id'=>$order->id])}}" class="btn btn-sm btn-green m-r-5"
  title="@lang('order.btn_new_order')"><i class="fas fa-clipboard-check"></i></a>
<a href="{{route('orders.copy',['id'=>$order->id])}}" class="btn btn-sm btn-primary m-r-5"
  title="@lang('order.btn_copy')"><i class="far fa-copy"></i></a>
  <a href="{{ route('orders.to_cancel',[$order->id]) }}"
  class="btn btn-sm btn-danger m-r-5" title="@lang('order.btn_cancel_order')"><i class="fas fa-trash-alt"></i></a>@endif
@if($order->status == 1)
<a href="{{ route('orders.pdf_bill',[$order->id]) }}" class="btn btn-sm btn-primary m-r-5"
  title="@lang('order.btn_pdf_bill')"><i class="fas fa-file-invoice-dollar"></i></a><a
  href="{{route('orders.copy',['id'=>$order->id])}}" class="btn btn-sm btn-primary m-r-5" title="@lang('order.btn_copy')">
  <i class="far fa-copy"></i></a><a href="#modal_explanation" class="btn btn-sm btn-green m-r-5"
  title="@lang('order.btn_explanation')" data-toggle="modal" data-subject="@lang('order.explanation_subject_order')
  {{$number}}"><i class="fa fa-envelope"></i></a>
  <a href="{{ route('orders.to_cancel',[$order->id]) }}"
    class="btn btn-sm btn-danger" title="@lang('order.btn_cancel_order')"><i class="fas fa-trash-alt"></i></a>@endif
@if($order->status == 2)<a href="{{ route('orders.pdf_bill',[$order->id]) }}" class="btn btn-sm btn-primary m-r-5" title="@lang('order.btn_pdf_bill')"><i class="fas fa-file-invoice-dollar"></i></a><a href="{{route('orders.copy',['id'=>$order->id])}}" class="btn btn-sm btn-primary m-r-5" title="@lang('order.btn_copy')"><i class="far fa-copy"></i></a><a href="#modal_explanation" class="btn btn-sm btn-green" title="@lang('order.btn_explanation')" data-toggle="modal" data-subject="@lang('order.explanation_subject_order'){{$number}}"><i class="fa fa-envelope"></i></a>@endif
