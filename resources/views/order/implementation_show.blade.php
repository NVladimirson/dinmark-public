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
	{{ Breadcrumbs::render('implementation.show',$implementation) }}

<h1 class="page-header">@lang('implementation.implementation') {{$products[0]['public_number']}}</h1>
<!-- begin row -->
<div class="row">
  <!-- begin col-10 -->
  <div class="col-xl-12">
    <!-- begin panel -->
    <div class="panel panel-primary">
      <!-- begin panel-heading -->
      <div class="panel-heading">
        <h4 class="panel-title">@lang('implementation.show_tab_name')</h4>
      </div>
      <div class="panel-body">
        <table class="table table-striped">
          <tr>
            <th>@lang('implementation.product_show_header_name')</th>
            <th>@lang('implementation.product_show_header_article')</th>
            <th>@lang('implementation.product_show_header_quantity')</th>
            <th>@lang('implementation.product_show_header_total')</th>
          </tr>
        @forelse($products as $key => $product)
				@if($key)
				<tr>
          <td><a href="{{route('products.show',['id' => $product['id']])}}"> {{$product['name']}}</a></td>
          <td>{{$product['article']}}</td>
          <td>{{$product['quantity']}}</td>
          <td>{{$product['total']}}</td>
        </tr>
				@else
				@if($product_focused)
				<tr>
          <td><b><a href="{{route('products.show',['id' => $product['id']])}}"> {{$product['name']}}</a></b></td>
          <td><b>{{$product['article']}}</b></td>
          <td><b>{{$product['quantity']}}</b></td>
          <td><b>{{$product['total']}}</b></td>
        </tr>
				@else
				<tr>
          <td><a href="{{route('products.show',['id' => $product['id']])}}"> {{$product['name']}}</a></td>
          <td>{{$product['article']}}</td>
          <td>{{$product['quantity']}}</td>
          <td>{{$product['total']}}</td>
        </tr>
				@endif
				@endif
        @empty
        @endforelse
        </table>
      </div>

    </div>

  </div>
  <!-- end col-10 -->
</div>

@endsection

@push('scripts')
<script src="/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="/assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="/assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="/assets/plugins/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="/assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js"></script>
<script src="/assets/plugins/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="/assets/plugins/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="/assets/plugins/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="/assets/plugins/pdfmake/build/pdfmake.min.js"></script>
<script src="/assets/plugins/pdfmake/build/vfs_fonts.js"></script>
<script src="/assets/plugins/jszip/dist/jszip.min.js"></script>
<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
@endpush
