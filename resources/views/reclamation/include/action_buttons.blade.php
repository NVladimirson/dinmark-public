<a href="#modal_explanation" class="btn btn-sm btn-green" title="@lang('order.btn_explanation_reclamation')"
data-toggle="modal" data-subject="@lang('order.explanation_subject_reclamation'){{$reclamation->id}}">
<i class="fa fa-envelope"></i></a>

<a href="{{route('reclamations.update', ['reclamation_id' => 1])}}" class="btn btn-sm btn-primary" title="@lang('order.explanation_edit_reclamation')"><i class="far fa-edit"></i></a>
