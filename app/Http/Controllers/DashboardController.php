<?php

namespace App\Http\Controllers;

use App\Models\News\News;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Order\Payment;
use App\Models\Product\Product;
use App\Services\Miscellenous\GlobalSearchService;
use App\Services\Product\Product as ProductService;
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
                'name'      => \App\Services\Product\Product::getName($order_product->product),
                'article'   => $order_product->product->article_show,
                'image'     => \App\Services\Product\Product::getImagePathThumb($order_product->product),
                'price'     => \App\Services\Product\Product::getPrice($order_product->product),
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
                'name'      => \App\Services\Product\Product::getName($order_product->product),
                'article'   => $order_product->product->article_show,
                'image'     => \App\Services\Product\Product::getImagePathThumb($order_product->product),
                'price'     => \App\Services\Product\Product::getPrice($order_product->product),
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
                    'id'	=> $news_item->id,
                    'name'	=> $content->name,
                    'text'	=> $content->list,
                    'date'	=> Carbon::parse($news_item->date_add)->format('d.m.Y h:i'),
                    'image' => NewsServices::getImagePath($news_item)
                ];
            }else{
                $newsData[] = [
                    'id'	=> $news_item->id,
                    'name'	=> '',
                    'text'	=> '',
                    'date'	=> Carbon::parse($news_item->date_add)->format('d.m.Y h:i'),
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
      'products' =>
      $product_search,
//          [
//              'text' => '...empty',
//          ],

      'orders' =>
      $order_search,
//                  [
//                      'text' => '...empty',
//                  ],

      'reclamations' =>
      $reclamation_search,
//          [
//              'text' => '...empty',
//          ],
      'implementations' =>
        $implementation_search,
//          [
//              'text' => '...empty',
//          ],

  ];
        return \Response::json($res);
    }
}
