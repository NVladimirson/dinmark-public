<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Product\CategoryServices;
use Artesaos\SEOTools\Facades\SEOTools;
use Carbon\Carbon;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Services\Product\Product as ProductServices;

class PurchaseController extends Controller
{
    public function index(){
        SEOTools::setTitle(trans('purchases.title'));
        $filters = CategoryServices::getOptionFilters();
        $dinmark_url = \Config::get('values.dinmarkurl');
        return view('order.purchases',compact('filters','dinmark_url'));
    }

    public function tableDataAjax(Request $request){

      $products = Product::whereHas('orderProducts',function($order_products){
        $order_products->whereHas('getCart',function($orders){
          $orders->whereHas('getUser',function($users){
                $users->whereHas('getCompany',function ($companies){
                    $companies->where([
                        ['holding', auth()->user()->getCompany->holding],
                        ['holding', '<>', 0],
                    ])->orWhere([
                        ['id', auth()->user()->getCompany->id],
                    ]);
                });
          });
        });
      })->with('orderProducts.getCart');

      if($request->new){
          $products = $products->where('date_add','>',Carbon::now()->subDays(7)->timestamp);
      }

      if($request->hits){
          $order_products = \DB::select('
          SELECT s_cart_products.product_id AS `product_id`,COUNT(*)
          FROM s_cart
          INNER JOIN s_cart_products ON s_cart.id = s_cart_products.cart
          WHERE `status` NOT IN (6,7,8)
          GROUP BY `product_id`
          HAVING COUNT(*) >= 5
            ');

          $filtered = array();
          foreach ($order_products as $no => $order_product){
              $filtered[] = $order_product->product_id;
          }

          $products = $products->whereIn('id', $filtered);
      }

      if($request->discount){
          $products = $products->where('old_price','!=',0);
      }

      return datatables()
  			->eloquent($products)
  			->addColumn('code_name', function (Product $product) {
          $name = ProductServices::getName($product);
          return '<a class="data-product_name" href="'
          .route('products.show',[$product->id]).'">'.$name.'</a><br>'.
          '<span>'.$product->article_show.'</span>';
  			})
        ->addColumn('photo', function (Product $product) {
          $src = ProductServices::getImagePath($product);

          $spans = '';

          if($product->old_price != 0){
              $spans .= '<span class="bage-default bage-sale">SALE</span>';
          }

          if($product->date_add >= Carbon::now()->subDays(7)->timestamp){
              $spans .= '<span class="bage-default bage-new">NEW</span>';
          }

          if(\DB::select('SELECT COUNT(*) as count FROM s_cart_products WHERE product_id = '.$product->id.'')[0]->count >= 5){
              $spans .= '<span class="bage-default bage-hits">HITS</span>';
          }

          return '<div class="product-image"><img src="'.$src.'" alt="https://dinmark.com.ua/images/dinmark_nophoto.jpg" width="60">
                      <div class="wrap-label">
                      '.$spans.'
                      </div>
                  </div>';
        })
        ->addColumn('quantity_in_orders_sellings_returns', function (Product $product) {
          $order_count = 0;
          $order_products = $product->orderProducts->pluck('id');
          if($order_products){
            foreach ($order_products as $key => $order_product) {
              $order_count++;
            }
          }

          $implementaion_count = 0;
          $implementation_products = Product::where('id',$product->id)->with('orderProducts.implementationProduct')->get();
          if($implementation_products){
              $implementation_products = $implementation_products->pluck('orderProducts')->first()->pluck('implementationProduct');
              foreach ($implementation_products as $key => $implementation_product) {
                if(count($implementation_product)){
                  $implementaion_count++;
                }
              }
          }

          $reclamation_count = 0;
          $reclamation_products = Product::where('id',$product->id)->with('orderProducts.reclamationProduct')->get();
          if($reclamation_products){
              $reclamation_products = $reclamation_products->pluck('orderProducts')->first()->pluck('reclamationProduct');
              foreach ($reclamation_products as $key => $reclamation_product) {
                if($reclamation_product){
                  $reclamation_count++;
                }
              }
          }
          return '
          <p style="overflow-wrap: break-word">Заказы: '.$order_count.'</p>
          <p style="overflow-wrap: break-word">Реализации: '.$implementaion_count.'</p>
          <p style="overflow-wrap: break-word">Возвраты: '.$reclamation_count.'</p>
          ';
        })
        ->addColumn('sum_of_orders/sellings/reclamations', function (Product $product) {
          
        })
        ->addColumn('percentage_of_confirmed_orders', function (Product $product) {

        })
        ->addColumn('sellings_weight', function (Product $product) {

        })
        ->addColumn('CSV-export', function (Product $product) {

        })
        ->rawColumns([
          'code_name',
          'photo',
          'quantity_in_orders_sellings_returns',
          'sum_of_orders/sellings/reclamations',
          'percentage_of_confirmed_orders',
          'sellings_weight',
          'CSV-export'
        ])
        ->toJson();


    }
}
