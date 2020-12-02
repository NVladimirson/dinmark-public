<div id="action_buttons_{!! $product->id !!}">
<a href="{{route('products.show',[$product->id])}}" class="btn btn-sm btn-primary m-r-5">
    <i class="fas fa-eye"></i></a>
<a href="#modal-wishlist" class="btn btn-sm btn-primary m-r-5" data-toggle="modal" data-product="{{$product->id}}">
    <i class="fas fa-star"></i></a>
    {{--@if(!$hasStorage)--}}
        {{--<a href="#modal-get_price" class="btn btn-sm btn-primary btn-get-price" data-toggle="modal"--}}
           {{--data-product_id="{{$product->id}}" ><i class="fas fa-question-circle"></i></a>--}}
    {{--@else--}}
        {{----}}
    {{--@endif--}}
@if($hasStorage)
    <a id="to_order_button_{{$product->id}}" href="#modal-order" class="btn btn-sm btn-primary source"
    data-price="{{\App\Services\Product\Product::getPriceUnformatted($product)}}"
             data-toggle="modal"
       data-product="{{$product->id}}"
             data-product_name="{{$name}}"
       data-storage="{{$storage->storage_id}}"
             data-storage_min="{{$storage->package}}"
       @if($storage->package == 0)
       data-storage_max="{{$storage->amount-($storage->amount%100)}}"
       @else
       data-storage_max="{{$storage->amount-($storage->amount%$storage->package)}}"
       @endif
       data-amount="{{$storage->package}}"
       data-image="{{$src}}"
       data-name="{{$name}}"
    >
        <i class="fas fa-cart-plus"></i></a>
        <a id="get_price_button_{{$product->id}}" href="#modal-get_price" class="btn btn-sm btn-primary btn-get-price" data-toggle="modal" data-amount="1" data-min="1"
            data-product_id="{{$product->id}}" data-image="{{$src}}" data-name="{{$name}}" style="width: auto;display:none"><i style="width: 13.5px;" class="fas fa-question-circle"></i></a>
@else
    <a id="get_price_button_{{$product->id}}" href="#modal-get_price" class="btn btn-sm btn-primary btn-get-price" data-toggle="modal" data-amount="1" data-min="1" data-step="1"
        data-product_id="{{$product->id}}" data-image="{{$src}}" data-name="{{$name}}" style="width: auto"><i style="width: 13.5px;" class="fas fa-question-circle"></i></a>
@endif
</div>
