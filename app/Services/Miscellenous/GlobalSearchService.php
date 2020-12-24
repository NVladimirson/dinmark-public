<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 05.11.2020
 * Time: 12:58
 */

namespace App\Services\Miscellenous;


use App\Models\Content;
use App\Models\Order\ImplementationProduct;
use App\Models\Order\OrderProduct;
use App\Models\Product\Product;
use App\Models\Product\ProductOption;
use App\Models\Reclamation\Reclamation;
use App\Models\Order\Implementation;
use App\Models\Reclamation\ReclamationProduct;
use LaravelLocalization;
use App\Services\Product\Product as ProductServices;
use App\Models\Order\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;

class GlobalSearchService

{
    private  $lang;
    private static $instance;
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct(){
        $this->lang = LaravelLocalization::getCurrentLocale() == 'ua'?'uk':LaravelLocalization::getCurrentLocale();
    }

    public static function getLang(){
      return static::getInstance()->lang;
    }

    public static function getProductsSearch($search,$limited = true){
      $product_info = [];
      $language = static::getInstance()->lang;

        $products = Product::whereHas('content', function($content) use($search,$language){
          $content->where([
            ['language',$language],
            ['alias', 8],
            ['name', 'like',"%" . $search . "%"]
          ]);
        })->orwhereHas('options', function($options) use($search,$language){
          $options->whereIn('option',[23,30])->whereHas('val', function($option_name) use($search,$language){
            $option_name->where([
              ['language',$language],
              ['name', 'like',"%" . $search . "%"]
            ]);
          });
        })->orWhere([['article', 'like',"%" . $search . "%"]]);

        if($limited){
          $products = $products->limit(5);
          $products = $products->get();

          $product_info = [];
          foreach ($products as $key => $product) {
            $product_info[] = [
              'id' => $product->id,
              'text' => ProductServices::getName($product,$language).' ('.$product->article_show.')',
              'category' => 'products'
              // 'url' => route('products.show', ['id' => $product->id]),
              // 'min' => $min,
              // 'max' => $max,
              // 'storage_id' => $storage_id,
            ];
          }
        return $product_info;
        }
        else{
          return $products;
        }

      }

      public static function getOrderProductsSearch($search,$limited = true){
        $product_info = [];
        $instance =  static::getInstance();
        $language = static::getInstance()->lang;
        $allowed_orders = Order::whereHas('getUser',function ($users){
                $users->where('id',auth()->user()->id)->whereHas('getCompany',function ($companies){
                    $companies->where([
                        ['id', session('current_company_id')],
                    ]);
                });
        })->pluck('id');

        $allowed_products = OrderProduct::whereIn('cart',$allowed_orders)->pluck('product_id');
        // dd($allowed_products);
        $products = Product::whereIn('id',$allowed_products)->whereHas('content', function($content) use($search,$language){
          $content->where([
            ['language',$language],
            ['alias', 8],
            ['name', 'like',"%" . $search . "%"]
          ]);
        });

        if($limited){
          $products = $products->limit(5);
          $products = $products->get();

          $product_info = [];
          foreach ($products as $key => $product) {
            $product_info[] = [
              'id' => $product->orderProducts->whereIn('cart', $allowed_orders)->first()->getCart->id,
              'text' => ProductServices::getName($product,$language).' ('.$product->article_show.')',
              'category' => 'orders'
            ];
          }
        return $product_info;
        }
        else{
          return $products;
        }
      }

      public static function getReclamationProductsSearch($search,$limited = true){
        $product_info = [];
        $instance =  static::getInstance();
        $language = static::getInstance()->lang;
        $allowed_reclamations = Reclamation::whereHas('user',function ($users){
                $users->whereHas('getCompany',function ($companies){
                    $companies->where([
                        ['id', session('current_company_id')],
                    ]);
                });
        })->pluck('id');

        $allowed_products = ReclamationProduct::whereIn('reclamation_id',$allowed_reclamations)->pluck('implementation_product_id');

        $products = Product::whereIn('id',$allowed_products)->whereHas('content', function($content) use($search,$language){
          $content->where([
            ['language',$language],
            ['alias', 8],
            ['name', 'like',"%" . $search . "%"]
          ]);
        });

        if($limited){
          $products = $products->limit(5);
          $products = $products->get();

          $product_info = [];
          foreach ($products as $key => $product) {
            $product_info[] = [
              'id' => $product->orderProducts->first()->getCart->id,
              'text' => ProductServices::getName($product,$language).' ('.$product->article_show.')',
              'category' => 'reclamations'
            ];
          }
        return $product_info;
        }
        else{
          return $products;
        }
      }

      public static function getImplementationProductsSearch($search, $limited = true){
          $product_info = [];
          $instance =  static::getInstance();
          $language = static::getInstance()->lang;
          $allowed_implementations = Implementation::whereHas('sender',function ($users){
                  $users->whereHas('getCompany',function ($companies){
                      $companies->where([
                          ['id', session('current_company_id')],
                      ]);
                  });
          })->orwhereHas('customer',function ($users){
                  $users->whereHas('getCompany',function ($companies){
                      $companies->where([
                          ['id', session('current_company_id')],
                      ]);
                  });
          })->pluck('id');

          $allowed_implementation_products = ImplementationProduct::whereIn('implementation_id',$allowed_implementations)->pluck('order_product_id');

          $allowed_order_products = OrderProduct::whereIn('id', $allowed_implementation_products)->pluck('product_id');

          $products = Product::whereIn('id',$allowed_order_products)->whereHas('content', function($content) use($search,$language){
            $content->where([
              ['language',$language],
              ['alias', 8],
              ['name', 'like',"%" . $search . "%"]
            ]);
          });

          if($limited){
            $products = $products->limit(5);
            $products = $products->get();

            $product_info = [];
            foreach ($products as $key => $product) {
              $product_info[] = [
                'id' => $product->orderProducts->first()->getCart->id,
                'text' => ProductServices::getName($product,$language).' ('.$product->article_show.')',
                'category' => 'implementations'
              ];
            }
            $product_info = array_values(Arr::sort($product_info, function ($value) {
              return $value['text'];
            }));
          return $product_info;
          }
          else{
            return $products;
          }
      }

    // public static function getReclamationProductsSearch($search){
    //     $reclamations = ReclamationProduct::with('product')->get();
    //
    //     $implementation_map = [];
    //     foreach ($reclamations as $reclamation){
    //         if($reclamation->product){
    //             $implementation_map[] = $reclamation->product->id;
    //         }
    //     }
    //
    //     $res = GlobalSearchService::getImplementationProductsSearch($search,$implementation_map);
    //     return $res;
    //
    //
    // }

    // public static function getImplementationProductsSearch($search, $filter_implementation_products = []){
    //
    //     if(empty($filter_implementation_products)){
    //         $implementations = ImplementationProduct::with('orderProduct')->get();
    //     }
    //     else{
    //         $implementations = ImplementationProduct::with('orderProduct')->whereIn('id',$filter_implementation_products)->get();
    //     }
    //
    //     $ordered_product_map = [];
    //     foreach ($implementations as $implementation){
    //         if($implementation->orderProduct){
    //             $ordered_product_map[] = $implementation->orderProduct->id;
    //         }
    //     }
    //
    //     $res = GlobalSearchService::getOrderProductsSearch($search,$ordered_product_map);
    //     return $res;
    // }


    // public static function getOrderProductsSearch($search,$filter_order_products = []){
    //
    //     $instance =  static::getInstance();
    //     if(empty($filter_order_products)){
    //         $products_in_order = OrderProduct::with('product')->where('user',auth()->user()->id)->get();
    //     }else{
    //         $products_in_order = OrderProduct::with('product')->where('user',auth()->user()->id)->whereIn('id',$filter_order_products)->get();
    //     }
    //
    //     $content_map=[
    //     ];
    //     foreach ($products_in_order as $order_product) {
    //         $product_id = $order_product->product->id;
    //         $content = Product::with('content')->where('id',$product_id)->first()->content->filter(function ($value, $key) use (&$instance) {
    //             return $value->language == $language;
    //         })
    //             ->first();
    //         $content_map[] = $content->id;
    //     }
    //
    //     $names = Content::where('name', 'like',"%" . $search . "%")
    //         ->whereIn('id',$content_map)->pluck('name','content')->toArray();
    //     $res = [];
    //     foreach ($names as $content_id => $name){
    //         $res[] = [
    //             'id' => $content_id,
    //             'text' => $name
    //         ];
    //         if(count($res) >= 5){
    //             break;
    //         }
    //     }
    //
    //     if(empty($res)){
    //         $res = [
    //             [
    //                 'text' => __('global.global_search.nothing_to_show')
    //             ]
    //         ];
    //     }
    //
    //     return $res;
    // }
}
