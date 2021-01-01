<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 05.11.2020
 * Time: 12:58
 */

namespace App\Services\Miscellenous;
use App\Models\Product\Product;
use App\Models\Product\ProductOptionName;
use App\Models\Product\ProductFilter;
// use App\Models\Content;
// use App\Models\Order\ImplementationProduct;
// use App\Models\Order\OrderProduct;
// use App\Models\Product\Product;
// use App\Models\Reclamation\Reclamation;
// use App\Models\Order\Implementation;
// use App\Models\Reclamation\ReclamationProduct;
 use LaravelLocalization;
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

    public static function getProductsByFilters($filters)
    {
      //dd($filters);
      $first_finded = true;
      foreach ($filters as $filter => $data) {
        if(!empty($data)){
          //if(in_array($filter,$convertable)){
            $translated = [];
            foreach ($data as $key => $optionvalue) {

              $translated[] = ExtendedSearchService::translateProductFilter($optionvalue,true);

            }
              $filters[$filter] = $translated;
                          //dd($translated);
              //dump($translated);
          //}
          if($first_finded){
            $products = Product::whereHas('productFilters', function($productsFilters) use($filter,$data){
                $productsFilters->whereIn($filter,$data);
            });
            $first_finded = false;

          }
          else{
            $products = $products->whereHas('productFilters', function($productsFilters) use($filter,$data){
                $productsFilters->whereIn($filter,$data);
            });
          }
        }
      }
    //  dd($products->get());
      return $products;
//      $first_finded = false;
      // foreach ($params as $filter => $options) {
      //   if(count($options)){
      //     if(in_array($filter,$convertable)){
      //       $translated = [];
      //       foreach ($options as $key => $option) {
      //         $translated[] = ExtendedSearchService::translateProductFilter($option,true);
      //       }
      //       $options = $translated;
      //     }
      //
      //     if(!$first_finded){
      //       // $products = Product::whereHas('productFilters', function($productsFilters) use($filter,$options){
      //       //     $productsFilters->whereIn($filter,$options);
      //       // });
      //       $product_filters = ProductFilter::whereIn($filter,$options);
      //       $first_finded = true;
      //     }
      //     else{
      //       // $products = $products->whereHas('productFilters', function($productsFilters) use($filter,$options){
      //       //     $productsFilters->whereIn($filter,$options);
      //       // });
      //       $product_filters = $product_filters->whereIn($filter,$options);
      //     }
      //   }
      //   else{
      //     unset($params[$filter]);
      //   }
      // }
      //
      // if(!count($params)){
      //   return '';
      // }
      // else{
      //   return $product_filters;
      // }
    }

    public static function translateProductFilter($strToTranslate,$nameToOption = false){
      $instance =  static::getInstance();
      if($nameToOption){
        $translate = ProductOptionName::where([['name',$strToTranslate],['language',$instance->lang]])->first();
        $translate?$translate=$translate->option:'';
      }else{
        $translate = ProductOptionName::where([['option',$strToTranslate],['language',$instance->lang]])->first();
        $translate?$translate=$translate->name:'';
      }
      return $translate;
    }

    public static function getProductFilters($product){
      $productfilters = ProductFilter::where('product_id',$product->id)->toArray();
      dd($productfilters);
      foreach ($variable as $key => $value) {
        // code...
      }
    }
}
