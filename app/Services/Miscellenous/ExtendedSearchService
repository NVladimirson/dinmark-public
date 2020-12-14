<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 05.11.2020
 * Time: 12:58
 */

namespace App\Services\Miscellenous;
use App\Models\Product\Product;
use App\Models\Product\ProductFilter;


// use App\Models\Content;
// use App\Models\Order\ImplementationProduct;
// use App\Models\Order\OrderProduct;
// use App\Models\Product\Product;
// use App\Models\Reclamation\Reclamation;
// use App\Models\Order\Implementation;
// use App\Models\Reclamation\ReclamationProduct;
// use LaravelLocalization;
// use App\Services\Product\Product as ProductServices;
// use App\Models\Order\Order;

class ExtendedSearchService

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

    public static function getProductsByFilters($params)
    {
      // $params = [
      //   'standart' => [],
      //   'pokryttja' => [],
      //   'material' => [12,110],
      //   'dovzhyna' => [],
      //   'diametr' => []
      // ];

      $first_finded = false;
      foreach ($params as $filter => $options) {
        if(count($options)){
          if(!$first_finded){
            $products = Product::whereHas('productFilters', function($productsFilters) use($filter,$options){
                $productsFilters->whereIn($filter,$options);
            });
            $first_finded = true;
          }
          else{
            $products = $products->whereHas('productFilters', function($productsFilters) use($filter,$options){
                $productsFilters->whereIn($filter,$options);
            });
          }
        }
        else{
          unset($params[$filter]);
        }
      }
      if(!count($params)){
        return Product::paginate(24);
      }
      else{
        return $products->paginate(24);
      }
    }
}
