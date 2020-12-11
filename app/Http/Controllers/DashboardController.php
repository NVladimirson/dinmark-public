<?php

namespace App\Http\Controllers;

use App\Models\News\News;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Order\Payment;
use App\Models\Product\Product;
use App\Services\Miscellenous\GlobalSearchService;
use App\Services\Product\Product as ProductServices;
use App\Models\Ticket\TicketMessage;
use App\Services\News\NewsServices;
use Artesaos\SEOTools\Facades\SEOTools;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index()
  {
    $orders = Order::with(['products.product'])
      ->whereHas('getUser', function ($users){
        $users->where('company',session('current_company_id'))
          ->orderBy('id','desc');
      })->get()->groupBy(function($val) {
        return Carbon::parse($val->date_add)->format('m Y');
      });

    $ordersWithoutRequest = Order::with(['products.product'])
      ->whereHas('getUser', function ($users){
        $users->where('company',session('current_company_id'))
          ->orderBy('id','desc');
      })
      ->where('status','<>',8)
      ->get();
    $ordersSuccess = Order::with(['products.product'])
      ->whereHas('getUser', function ($users){
        $users->where('company',session('current_company_id'))
          ->orderBy('id','desc');
      })
      ->where([
        ['status','<>',8],
        ['status','<>',1],
        ['status','<>',7],
      ])
      ->get();
    $order_counts = $ordersWithoutRequest->count();
    $success_procent = 0;
    if($order_counts  != 0){
      $success_procent = $ordersSuccess->count() / $order_counts * 100;
    }

    $success_total = $ordersSuccess->sum('total');

    $success_weight = 0;
    foreach ($ordersSuccess as $orderSuccess){
      foreach ($orderSuccess->products as $orderProduct){
        $success_weight += ($orderProduct->product->weight/100) * $orderProduct->quantity;
      }
    }

    $user = auth()->user();
    $last_orders = Order::where('user', $user->id)
            ->orderBy('date_add','desc')
            ->limit(5)
            ->get();

    $last_payment = Payment::whereHas('order', function ($order) use ($user){
                $order->where('user', $user->id);
            })
            ->orderBy('date_add','desc')
            ->first();

    $last_messages = TicketMessage::whereHas('chat',function ($chat) use ($user){
            $chat->where(function($q){
                $q->where('user_id',auth()->user()->id)
                    ->orWhere('manager_id',auth()->user()->id);
            });
        })
            ->where('user_id', '<>', $user->id)
            ->orderBy('created_at','desc')
            ->limit(5)
            ->get();

        $top_price_order_products = OrderProduct::with('product')->whereHas('getCart',function ($order){
                $order->whereHas('getUser', function ($users){
                    $users->where('company',auth()->user()->company);
                });
            })
            ->groupBy('product_id')
            ->selectRaw('(price*quantity) as total, product_id')
            ->orderByRaw('price*quantity desc')
            ->limit(5)
            ->get();

        $topOrderProducts = [];
        foreach ($top_price_order_products as $order_product){
            $topOrderProducts [] = [
                'id'        => $order_product->product->id,
                'name'      => ProductServices::getName($order_product->product),
                'article'   => $order_product->product->article_show,
                'image'     => ProductServices::getImagePathThumb($order_product->product),
                //'price'     => ProductServices::getPrice($order_product->product),
                'price' => number_format($order_product->total,2,'.',' ')
            ];
        }

$most_popular_order_products = OrderProduct::with('product')->whereHas('getCart',function ($order){
                $order->whereHas('getUser', function ($users){
                    $users->where('company',auth()->user()->company);
                });
            })
            ->groupBy('product_id')
            ->selectRaw('count(product_id) as product_count, product_id')
            ->orderByRaw('product_count desc')
            ->limit(5)
            ->get();

        $mostPopularOrderProducts = [];
        foreach ($most_popular_order_products as $order_product){
            $mostPopularOrderProducts [] = [
                'id'        => $order_product->product->id,
                'name'      => ProductServices::getName($order_product->product),
                'article'   => $order_product->product->article_show,
                'image'     => ProductServices::getImagePathThumb($order_product->product),
                //'price'     => ProductServices::getPrice($order_product->product),
                'product_count' => $order_product->product_count
            ];
        }

        $newsData = [];
        $news = News::with(['content'])
            // ->where([
            //     ['target','<>','site'],
            //     ['active',1],
            // ])
            ->where([
              //['target','<>','site'],
              ['target','=','b2b'],
              ['active',1],
            ])->orWhere([
              ['target','=','both'],
              ['active',1],
            ])
            ->orderBy('date_add','desc')
            ->limit(5)
            ->get();

        foreach ($news as $news_item){
            $content = NewsServices::getContent($news_item);
            if($content){
                $newsData[] = [
                    'id'  => $news_item->id,
                    'name'  => $content->name,
                    'text'  => $content->list,
                    'date'  => Carbon::parse($news_item->date_add)->format('d.m.Y h:i'),
                    'image' => NewsServices::getImagePath($news_item)
                ];
            }else{
                $newsData[] = [
                    'id'  => $news_item->id,
                    'name'  => '',
                    'text'  => '',
                    'date'  => Carbon::parse($news_item->date_add)->format('d.m.Y h:i'),
                    'image' => NewsServices::getImagePath($news_item)
                ];
            }
        }

    SEOTools::setTitle(trans('dashboard.page_name'));
    return view('dashboard',compact(
        'order_counts',
            'success_procent',
            'success_total',
            'success_weight',
            'orders',
            'last_orders',
            'last_payment',
            'last_messages',
            'topOrderProducts',
            'mostPopularOrderProducts',
            'newsData'
        ));
    }

    public function globalSearch(Request $request)
    {
        $search = $request->name;

        $product_search = GlobalSearchService::getProductsSearch($search);

        $order_search = GlobalSearchService::getOrderProductsSearch($search);

        $implementation_search = GlobalSearchService::getImplementationProductsSearch($search);

        $reclamation_search = GlobalSearchService::getReclamationProductsSearch($search);

        $res = [
          'products' => $product_search,
          'orders' => $order_search,
          'reclamations' => $reclamation_search,
          'implementations' => $implementation_search,
         ];
        return \Response::json($res);
    }

    public function extendedSearch(Request $request){

      $data = $request->toArray();
    //   $data = [
    // 'standart' => NULL,
    // 'diametr' => NULL,
    // 'dovzhyna' => '27',
    // 'material' => NULL,
    // 'klas_micnosti' => NULL,
    // 'pokryttja' => NULL,
    // 'active' => 'standart'
    // ];

$activefilter = $data['active'];
    $allowed_filters = ['standart','diametr','dovzhyna','material',
    'klas_micnosti','pokryttja','active'];
    if(!in_array($activefilter,$allowed_filters)){
      return [];
    }
    unset($data['active']);

    $notemptyoptionrequest = 0;
    foreach ($data as $filter => $value) {

      if($value == null || $value == ''){
        $data[$filter] = [];
      }
      else{
        $data[$filter] = explode(",",$data[$filter]);
        $notemptyoptionrequest++;
      }
    }

    $query = 'SELECT DISTINCT products_filter.'.$activefilter.' FROM products_filter';

    if($notemptyoptionrequest){
      $whereIn = false;
      foreach ($data as $filter => $value) {
        if(count($value) == 0){
          continue;
        }
        if(!$whereIn){
          $query .= ' WHERE '.$filter.' IN (';
          $query .= implode(",", $value);
          $query .= ')';
          $whereIn = true;
        }else{
          $query .= ' AND '.$filter.' IN (';
          $query .= implode(",", $value);
          $query .= ')';
        }
      }
    }
    $result = json_decode(json_encode(\DB::select($query)),true);
    $response = [];
    foreach ($result as $key => $value) {
      $response[] = $value[$activefilter];
    }
    if($response){
          foreach ($response as $value) {
            if(in_array($value,$data[$activefilter])){
              unset($response[array_search($value, $response)]);
            }
          }
          sort($response);
          return $response;
    }else{
      return [];
    }
    }
}