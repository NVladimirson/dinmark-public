<a href="{{route('products.show',[$product->id])}}" class="btn btn-sm btn-primary m-r-5"><i class="fas fa-eye"></i></a>@if($storage)<a href="#modal-order" class="btn btn-sm btn-primary m-r-5" data-toggle="modal" data-product="{{$product->id}}" data-storage="{{$storage->storage_id}}" data-storage_min="{{$storage->package}}" data-storage_max="{{$storage->amount}}" ><i class="fas fa-cart-plus"></i></a>@endif<a href="#" class="btn btn-sm btn-danger product-wishlist-remove" data-product="{{$product->id}}"><i class="fas fa-times"></i></a>
