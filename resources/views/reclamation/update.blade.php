@extends('layouts.default')

@section('title', 'Managed Tables - Buttons')

@push('css')
	<link href="/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
	<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
@endpush

@section('content')
	{{ Breadcrumbs::render('reclamations.update',$reclamation) }}

	<h1 class="page-header">@lang('reclamation.page_update')</h1>
	<!-- begin row -->
	<div class="row">
		<!-- begin col-10 -->
		<div class="col-xl-12">
			<!-- begin panel -->
			<div class="panel panel-primary">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">@lang('reclamation.create_tab_name')</h4>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<form action="{{route('reclamations.store')}}" enctype="multipart/form-data" method="post">
						@csrf
					<div class="row m-b-15">
						<div class="col-md-12  m-b-15">
							<label for="implementation_id">@lang('reclamation.select_implementation')</label>
							<select class="form-control m-b-5" id="implementation_id" name="implementation_id">
                                @if(isset($implementation))
                                <option value="{{$implementation->id}}" selected="selected">{{$implementation->public_number}}</option>
                                @endif
							</select>
						</div>
                        <div class="col-md-9">
                            <label for="product_id">@lang('reclamation.select_product')</label>
                            <select class="form-control m-b-5" id="product_id">
                            </select>
                        </div>
                        <div class="col-md-3">
                            <a href="#" id="add_product_btn" class="btn btn-mb btn-block btn-green m-t-25 m-b-5" title="@lang('reclamation.add_product')"><i class="fas fa-plus"></i></a>
                        </div>
                        <div class="col-md-12 m-t-15">
                            <div class="table-scroll-container">
                            <table id="reclamation_product_table" class="table table-striped table-bordered table-td-valign-middle m-b-15">
                                <thead>
                                <tr>
                                    <th class="text-nowrap">@lang('reclamation.product')</th>
                                    <th class="text-nowrap text-center">@lang('reclamation.quantity_product')</th>
                                    <th class="text-nowrap text-center">@lang('reclamation.comment')</th>
                                    <th width="20"></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            </div>
                        </div>

						<div class="col-md-12 m-b-15">
							<label for="ttn">@lang('reclamation.ttn')</label>
							<input class="form-control m-b-5" type="text" id="ttn" name="ttn">
						</div>
                        <div class="col-md-6">

                            <div class="form-group ">
                                <div class="input-group mb-3 @error('document') is-invalid @enderror">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="uploadDocumentAddon">@lang('company.document')</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="document" class="custom-file-input @error('document') is-invalid @enderror" id="uploadPhoto" aria-describedby="uploadDocumentAddon">
                                        <label class="custom-file-label" for="uploadPhoto">@lang('reclamation.document_type')</label>
                                    </div>
                                </div>
                                @error('document')
                                <span class="invalid-feedback " role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
					</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="pull-right">
									<button type="submit" id="form_submit" class="btn btn-sm btn-primary m-b-5 m-r-5" disabled="disabled">@lang('reclamation.btn_submit')</button>
								</div>
							</div>
						</div>

					</form>
				</div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
		</div>
		<!-- end col-10 -->
	</div>
	<!-- end row -->


@endsection

@push('scripts')
	<script src="/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="/assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
	<script src="/assets/plugins/pdfmake/build/vfs_fonts.js"></script>
	<script src="/assets/plugins/jszip/dist/jszip.min.js"></script>
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
	<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>

	<script>
		(function ($) {
			"use strict";
			$(document).ready(function() {
				$('#implementation_id').select2({
					placeholder: "@lang('reclamation.select_implementation')",
					minimumInputLength: 3,
					ajax: {
						url: function () {
							return '{{route('implementations.find')}}'
						},
						dataType: 'json',
						data: function (params) {
							return {
								name: params.term
							};
						},
						processResults: function (data) {
							return {
								results: data
							};
						},
						cache: true
					},
				});
				$('#implementation_id').change(function (e) {
					e.preventDefault();
					var implementation_id = $(this).val();
					if(implementation_id){
						$('#product_id').attr('disabled', false);
					}
					var route = '{{route('implementations')}}/products/' + implementation_id;

					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					$.ajax({
						method: "GET",
						url: route,
						success: function (resp) {
							$('#product_id').html('');
							$('#reclamation_product_table tbody').html('');
							resp.forEach(function (element) {
								$('#product_id').append('<option value="'+element['id']+'" id="option_'+element['id']+'" data-max="'+element['max']+'">'+element['name']+'</option>');
							});
							$('#product_id').trigger('change');
						},
						error: function (xhr, str) {
							console.log(xhr);
						}
					});
				});
                @if(isset($implementation))
				$('#implementation_id').change();
                @endif

				$('#add_product_btn').click(function (e) {
					if($('#product_id option').length !== 0){
                    e.preventDefault();
					var id = $("#product_id").val();
                    var name = $("#product_id option:selected").text();
					var max = $("#product_id option:selected").data('max');
					$('#quantity_product').val(1);
					$("#product_id option:selected").remove();
					if($('#product_id option').length === 0){
						$('#product_id').attr('disabled', true);
					}
                    $('#form_submit').removeAttr('disabled');

					$('#reclamation_product_table tbody').append(
                    	'<tr id="row_'+id+'">' +
                        '<td><input type="hidden" name="product_id['+id+']" value="'+id+'">'+name+'</td>'+
                        '<td><input class="form-control m-b-5" type="number" name="quantity_product['+id+']" min="0" max="'+max+'" required value="1"></td>'+
                        '<td><textarea class="form-control m-b-5" name="comment['+id+']" cols="30" rows="2" required></textarea></td>'+
                        '<td><a href="#" data-id="'+id+'" data-name="'+name+'"  data-max="'+max+'"  class="btn btn-sm btn-danger delete-product"><i class="fas fa-times"></i></a></td>'+
                        '</tr>');
					$('.delete-product').unbind();
                    $('.delete-product').click(function (e) {
						e.preventDefault();

						var tr_id = $(this).data('id');
						var tr_name = $(this).data('name');
						var tr_max = $(this).data('max');
						$('#row_'+tr_id).remove();
                        $('#product_id').append('<option value="'+tr_id+'" id="option_'+tr_id+'" data-max="'+tr_max+'">'+tr_name+'</option>');
                        if($('#reclamation_product_table tbody tr').length == 0){
							$('#form_submit').attr('disabled','disabled');
                        }
					})
				}
				})

				$('#product_id').change(function (e) {
					e.preventDefault();
					$('#quantity_product').attr('max',$("#product_id option:selected").data('max'));
					$('#quantity_product').val(1);
				});

				$('#product_id').attr('disabled', true);
				//$('#add_product_btn').attr('disabled', 'disabled');
			});
		})(jQuery);
	</script>
@endpush
