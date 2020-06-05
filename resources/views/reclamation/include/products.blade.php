<table class="table table-striped table-bordered table-td-valign-middle m-b-15">
    <thead>
    <tr>
        <th class="text-nowrap">@lang('reclamation.product')</th>
        <th class="text-nowrap text-center">@lang('reclamation.quantity_product')</th>
        <th class="text-nowrap text-center">@lang('reclamation.comment')</th>
        <th class="text-nowrap text-center">@lang('reclamation.table_header_status')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td><a href="{{route('products.show',[$product['product_id']])}}">{{$product['name']}}</a></td>
            <td class="text-nowrap text-center">{{$product['quantity']}}</td>
            <td class="text-nowrap text-center">{{$product['note']}}</td>
            <td class="text-nowrap text-center">{!!  $product['status'] !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>
