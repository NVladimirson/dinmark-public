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
use App\Models\Reclamation\ReclamationProduct;
use LaravelLocalization;
use App\Services\Product\Product as ProductServices;

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


    public static function getProductsSearch($search){

        $ids = ProductService::getIdsSearch($search);

        $products = Product::whereIn('id',$ids)
            ->orWhere([
                ['article','like',"%".$search."%"],
            ])->orWhere([
                ['article_show','like',"%".$search."%"],
            ])
            ->orderBy('article')
            ->limit(5)
            ->get();

        $product_info = [];

        foreach ($products as $product) {
            $name = ProductService::getName($product);
            $storage = $product->storages->firstWhere('is_main',1);
            $min = 0;
            $max = 0;
            $storage_id = 1;
            if($storage){
                $min = $storage->package;
                $max = $storage->amount;
                $storage_id = $storage->storage_id;
            }

            $product_info[] = [
                'id' => $product->id,
                'text' => $name.' ('.$product->article_show.')',
                'min' => $min,
                'max' => $max,
                'storage_id' => $storage_id,
            ];
        }
        return $product_info;
    }

    public static function getReclamationProductsSearch($search){
        $reclamations = ReclamationProduct::with('product')->get();

        $implementation_map = [];
        foreach ($reclamations as $reclamation){
            if($reclamation->product){
                $implementation_map[] = $reclamation->product->id;
            }
        }

        $res = GlobalSearchService::getImplementationProductsSearch($search,$implementation_map);
        return $res;


    }

    public static function getImplementationProductsSearch($search, $filter_implementation_products = []){

        if(empty($filter_implementation_products)){
            $implementations = ImplementationProduct::with('orderProduct')->get();
        }
        else{
            $implementations = ImplementationProduct::with('orderProduct')->whereIn('id',$filter_implementation_products)->get();
        }

        $ordered_product_map = [];
        foreach ($implementations as $implementation){
            if($implementation->orderProduct){
                $ordered_product_map[] = $implementation->orderProduct->id;
            }
        }

        $res = GlobalSearchService::getOrderProductsSearch($search,$ordered_product_map);
        return $res;
    }


    public static function getOrderProductsSearch($search,$filter_order_products = []){

        $instance =  static::getInstance();
        if(empty($filter_order_products)){
            $products_in_order = OrderProduct::with('product')->where('user',auth()->user()->id)->get();
        }else{
            $products_in_order = OrderProduct::with('product')->where('user',auth()->user()->id)->whereIn('id',$filter_order_products)->get();
        }

        $content_map=[
        ];
        foreach ($products_in_order as $order_product) {
            $product_id = $order_product->product->id;
            $content = Product::with('content')->where('id',$product_id)->first()->content->filter(function ($value, $key) use (&$instance) {
                return $value->language == $instance->lang;
            })
                ->first();
            $content_map[] = $content->id;
        }

        $names = Content::where('name', 'like',"%" . $search . "%")
            ->whereIn('id',$content_map)->pluck('name','content')->toArray();
        $res = [];
        foreach ($names as $content_id => $name){
            $res[] = [
                'id' => $content_id,
                'text' => $name
            ];
            if(count($res) >= 5){
                break;
            }
        }

        if(empty($res)){
            $res = [
                [
                    'text' => __('global.global_search.nothing_to_show')
                ]
            ];
        }

        return $res;
    }
}
