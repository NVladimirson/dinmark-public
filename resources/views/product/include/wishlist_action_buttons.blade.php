<div id="action_buttons_{!! $product->id !!}">
<a href="#modal-wishlist" class="btn btn-sm btn-primary m-r-5" data-toggle="modal" data-product="{{$product->id}}">
    <i class="fas fa-exchange-alt"></i></a>@if($hasStorage)
    <a href="#modal-order" class="btn btn-sm btn-primary m-r-5"
                                                              data-toggle="modal" data-product="{{$product->id}}"
                                                              data-product_name="{{$name}}"
                                                              data-storage="{{$storage->storage_id}}"
       data-storage_min="{{$storage->package}}"

       @if($storage->package == 0)
       data-storage_max="{{$storage->amount-($storage->amount%100)}}" >
        @else
            data-storage_max="{{$storage->amount-($storage->amount%$storage->package)}}" data-amount="0">
        @endif
        <i class="fas fa-cart-plus"></i></a>@else<a href="#modal-get_price" class="btn btn-sm btn-primary btn-get-price m-r-5"
                                                    data-toggle="modal" data-product_id="{{$product->id}}" >
        <i class="fas fa-question-circle"></i></a>@endif<a href="#" class="btn btn-sm btn-danger product-wishlist-remove"
                                                           data-product="{{$product->id}}"><i class="fas fa-trash-alt"></i></a>
                                                           </div>
