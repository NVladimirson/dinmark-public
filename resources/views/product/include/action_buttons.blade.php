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
    <a href="#modal-order" class="btn btn-sm btn-primary source"
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
@else
    <a href="#modal-get_price" class="btn btn-sm btn-primary btn-get-price" data-toggle="modal"
        data-product_id="{{$product->id}}" ><i class="fas fa-question-circle"></i></a>
@endif
</div>
