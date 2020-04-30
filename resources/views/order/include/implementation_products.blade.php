<table class="table table-striped table-bordered table-td-valign-middle m-b-15">
    <thead>
    <tr>
        <th class="text-nowrap">@lang('implementation.table_product_name')</th>
        <th class="text-nowrap text-center">@lang('implementation.table_product_quantity')</th>
        <th class="text-nowrap text-center">@lang('implementation.table_product_total')</th>
        <th class="text-nowrap text-center">@lang('implementation.table_product_order')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{$product['name']}}</td>
            <td class="text-nowrap text-center">{{$product['quantity']}}</td>
            <td class="text-nowrap text-center">{{$product['total']}}</td>
            <td class="text-nowrap text-center"><a href="{{route('orders.show',[$product['order']])}}" target="_blank">{{$product['order_number']}}</a></td>
        </tr>
    @endforeach
    </tbody>
</table>