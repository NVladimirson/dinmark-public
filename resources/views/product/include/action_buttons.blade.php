<a href="{{route('products.show',[$product->id])}}" class="btn btn-sm btn-primary m-r-5"><i class="fas fa-eye"></i></a><a href="#modal-wishlist" class="btn btn-sm btn-primary m-r-5" data-toggle="modal" data-product="{{$product->id}}"><i class="fas fa-star"></i></a><a href="{{route('products.show',[$product->id])}}" class="btn btn-sm btn-primary"><i class="fas fa-cart-plus"></i></a>