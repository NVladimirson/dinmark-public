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

        if($limited){
          $products = json_decode(
            json_encode(
              \DB::select("
              SELECT p.`id`, p.`article_show`, c.`name`,
              POSITION(\"".$search."\" IN c.`name`) AS 'position_in_name',
              POSITION(\"".$search."\" IN p.`article_show`) AS 'position_in_article'
              FROM s_shopshowcase_products AS p
              JOIN wl_ntkd AS c ON p.id = c.content
              WHERE c.alias = 8 AND c.`language` = '".$language."'
              AND (c.`name` LIKE '%".$search."%' OR p.`article_show` LIKE '%".$search."%')
              ORDER BY position_in_name,c.`name`,position_in_article
              LIMIT 5
              "
              )
          )
        );
          foreach ($products as $key => $product) {
            $product_info[] = [
              'id' => $product->id,
              'text' => $product->name .' ('.$product->article_show.')',
              'category' => 'products'
            ];
          }
        return $product_info;
        }
        else{
          $products = Product::whereHas('content', function($content) use($search,$language){
            $content->where([
              ['language',$language],
              ['alias', 8],
              ['name', 'like',"%" . $search . "%"]
            ]);
          })
          ->orWhere([
          ['article_show', 'like', $search . "%"]
          ]);
          return $products;
        }

      }

      public static function getOrderProductsSearch($search,$limited = true){
        $product_info = [];
        $instance =  static::getInstance();
        $language = static::getInstance()->lang;
        $user_id = auth()->user()->id;

        if($limited){
          $products = json_decode(
            json_encode(
              \DB::select("
              SELECT cp.id, p.`id` AS 'product_id', p.`article_show`, c.`name`,
              POSITION(\"".$search."\" IN c.`name`) AS 'position_in_name',
              POSITION(\"".$search."\" IN p.`article_show`) AS 'position_in_article'
              FROM s_cart_products AS cp
              INNER JOIN s_shopshowcase_products AS p ON p.id = cp.product_id
              INNER JOIN wl_ntkd AS c ON p.id = c.content
              INNER JOIN s_cart AS cart ON cart.id = cp.cart
              WHERE c.alias = 8 AND c.`language` = '".$language."' AND cart.user = '".$user_id."'
              AND (c.`name` LIKE '%".$search."%' OR p.`article_show` LIKE '%".$search."%')
              ORDER BY position_in_name,c.`name`,position_in_article
              LIMIT 5
              "
              )
          )
        );
          foreach ($products as $key => $product) {
            $product_info[] = [
              'id' => $product->id,
              'product_id' => $product->product_id,
              'text' => $product->name .' ('.$product->article_show.')',
              'category' => 'orders'
            ];
          }
        return $product_info;
        }
        else{
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
          })
          ->orWhereIn('id',$allowed_products)
          ->where([
          ['article_show', 'like', $search . "%"]
          ]);
          return $products;
        }
      }

      public static function getImplementationProductsSearch($search, $limited = true){
          $product_info = [];
          $instance =  static::getInstance();
          $language = static::getInstance()->lang;
          $user_id = auth()->user()->id;

          if($limited){
            $products = json_decode(
              json_encode(
                \DB::select("
                SELECT i.id, p.`id` AS 'product_id', p.`article_show`, c.`name`,
                POSITION(\"".$search."\" IN c.`name`) AS 'position_in_name',
                POSITION(\"".$search."\" IN p.`article_show`) AS 'position_in_article'
                FROM b2b_implementation_products AS ip
                INNER JOIN s_cart_products AS cp ON ip.order_product_id = cp.id
                INNER JOIN s_shopshowcase_products AS p ON p.id = cp.product_id
                INNER JOIN wl_ntkd AS c ON p.id = c.content
                INNER JOIN b2b_implementations AS i ON ip.implementation_id = i.id
                WHERE c.alias = 8 AND c.`language` = '".$language."' AND i.customer_id = '".$user_id."'
                AND (c.`name` LIKE '%".$search."%' OR p.`article_show` LIKE '%".$search."%')
                ORDER BY position_in_name,c.`name`,position_in_article
                LIMIT 5
                "
                )
            )
          );
            foreach ($products as $key => $product) {
              $product_info[] = [
                'id' => $product->id,
                'product_id' => $product->product_id,
                'text' => $product->name .' ('.$product->article_show.')',
                'category' => 'implementations'
              ];
            }
          return $product_info;
          }
          else{
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

            $products = Product::with('orderProducts.implementationProduct.implementation')->whereIn('id',$allowed_order_products)->whereHas('content', function($content) use($search,$language){
              $content->where([
                ['language',$language],
                ['alias', 8],
                ['name', 'like',"%" . $search . "%"]
              ]);
            })
            ->orWhereIn('id',$allowed_order_products)
            ->where([
            ['article_show', 'like', $search . "%"]
            ]);
            return $products;
          }
      }

      public static function getReclamationProductsSearch($search,$limited = true){
        $product_info = [];
        $instance =  static::getInstance();
        $language = static::getInstance()->lang;
        $user_id = auth()->user()->id;

        if($limited){
          $products = json_decode(
            json_encode(
              \DB::select("
              SELECT r.id, p.`id` AS 'product_id', p.`article_show`, c.`name`,
              POSITION(\"".$search."\" IN c.`name`) AS 'position_in_name',
              POSITION(\"".$search."\" IN p.`article_show`) AS 'position_in_article'
              FROM b2b_reclamation_products AS rp
              INNER JOIN b2b_implementation_products AS ip ON rp.implementation_product_id = ip.id
              INNER JOIN s_cart_products AS cp ON ip.order_product_id = cp.id
              INNER JOIN s_shopshowcase_products AS p ON p.id = cp.product_id
              INNER JOIN wl_ntkd AS c ON p.id = c.content
              INNER JOIN b2b_reclamations AS r ON rp.reclamation_id = r.id
              WHERE c.alias = 8 AND c.`language` = '".$language."' AND r.author = '".$user_id."'
              AND (c.`name` LIKE '%".$search."%' OR p.`article_show` LIKE '%".$search."%')
              ORDER BY position_in_name,c.`name`,position_in_article
              LIMIT 5
              "
              )
          )
        );
          foreach ($products as $key => $product) {
            $product_info[] = [
              'id' => $product->id,
              'product_id' => $product->product_id,
              'text' => $product->name .' ('.$product->article_show.')',
              'category' => 'reclamations'
            ];
          }
        return $product_info;
        }
          else{
            $allowed_reclamations = \App\Models\Reclamation\Reclamation::whereHas('user',function ($users){
                    $users->whereHas('getCompany',function ($companies){
                        $companies->where([
                            ['id', session('current_company_id')],
                        ]);
                    });
            })->pluck('id');

            $allowed_implementation_products = ReclamationProduct::whereIn('reclamation_id',$allowed_reclamations)->pluck('implementation_product_id');

            $allowed_order_products = ImplementationProduct::whereIn('id',$allowed_implementation_products)->pluck('order_product_id');

            $allowed_products = OrderProduct::whereIn('id',$allowed_order_products)->pluck('product_id');

            $products = Product::whereIn('id',$allowed_products)->whereHas('content', function($content) use($search,$language){
              $content->where([
                ['language',$language],
                ['alias', 8],
                ['name', 'like',"%" . $search . "%"]
              ]);
            })
            ->orWhereIn('id',$allowed_products)
            ->where([
            ['article_show', 'like', $search . "%"]
            ]);
            return $products;
          }
      }


}
