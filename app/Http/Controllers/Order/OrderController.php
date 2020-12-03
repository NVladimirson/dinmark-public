<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Imports\OrderImport;
use App\Models\Company\Client;
use App\Models\Company\Company;
use App\Models\Company\CompanyPrice;
use App\Models\Order\Implementation;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Order\OrderStatus;
use App\Models\Order\Payment;
use App\Models\Order\Shipping;
use App\Models\Product\Product;
use App\Services\Finance\BalanceServices;
use App\Services\Order\OrderServices;
use App\Services\TimeServices;
use App\Services\Product\Product as ProductServices;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use Excel;
use Illuminate\Support\Facades\DB;
use PDF;

class OrderController extends Controller
{
	public function index(){
		SEOTools::setTitle(trans('order.page_list'));
		$statuses = OrderStatus::all();

        $sendersId =  Order::whereHas('getUser', function ($users){
                $users->where('company',auth()->user()->company);
            })
            ->groupBy('sender_id')
            ->pluck('sender_id');
						//[0=>0]
        $senders = User::whereIn('id',$sendersId)->pluck('id','name')->toArray();
        if($sendersId->has(0)){
            $senders = array_merge(['Dinmark'=>0],$senders);
        }
        $customersId =  Order::whereHas('getUser', function ($users){
                $users->where('company',auth()->user()->company);
            })
            ->groupBy('customer_id')
            ->pluck('customer_id');
        $customers = User::whereIn('id',$customersId)->pluck('id','name')->toArray();
        foreach ($customersId as $id){
            if($id < 0){
                $client = Client::find(-$id);
                if($client){
                    $customers[$client->name] = $id;
                }
            }
        }

		return view('order.index',compact('statuses', 'senders','customers'));
	}

	public function addToOrder($id, Request $request){
		$order = null;
		$product = Product::with(['storages'])->find($request->product_id);

        if($id == 0){
            $order = Order::create([
                'user' => auth()->user()->id,
                'customer_id' => auth()->user()->id,
                'status' => 8,
                'total' => 0,
                'source' => 'b2b',
            ]);
        }else{
            $order = Order::find($id);
            $order->save();
        }

        OrderProduct::create([
            'cart' => $order->id,
            'user' => auth()->user()->id,
            'active' => 1,
            'product_alias' => $product->wl_alias,
            'product_id' => $product->id,
            'storage_alias' => $request->storage_id,
            'price' => 0,
            'price_in' => 0,
            'quantity' => $request->quantity,
            'quantity_wont' => $request->quantity,
            'date' => Carbon::now()->timestamp,
        ]);

        OrderServices::calcTotal($order);

        if($request->quantity_request > 0){
            \App\Services\Product\Product::getPriceRequest($request->product_id, $request->quantity_request);
        }

		return 'ok';
	}

	public function addToOrderMultiple(Request $request){


//        foreach ($request->input() as $productinfo => $quantity){
//            $product_id = substr(explode(':',$productinfo)[0],3);
//            $storage_id = substr(explode(':',$productinfo)[1],10);
//            $res[] = ['product_id' => $product_id, 'storage_id' => $storage_id, 'quantity' => $quantity];
//        }

        foreach($request->input() as $product_id => $product_info){
            $quantity =  explode(':',explode(',', $product_info)[0])[1];
            $storage =  explode(':',explode(',', $product_info)[1])[1];
            $quantity_request =  explode(':',explode(',', $product_info)[2])[1];
            $res[] = ['product_id' => $product_id, 'storage_id' => $storage, 'quantity' => $quantity,'quantity_request' => $quantity_request];
        }
        $order = null;
        if(!($request->storage_id)){
            $order = Order::create([
                'user' => auth()->user()->id,
                'customer_id' => auth()->user()->id,
                'status' => 8,
                'total' => 0,
                'source' => 'b2b',
            ]);
        }else{
            $order = Order::find($request->storage_id);
            $order->save();
        }

        foreach ($res as $no => $data){

            $product = Product::with(['storages'])->find($data['product_id']);
						// info('orderid:'.$order->id);
            OrderProduct::create([
                'cart' => $order->id,
                'user' => auth()->user()->id,
                'active' => 1,
                'product_alias' => $product->wl_alias,
                'product_id' => $product->id,
                'storage_alias' => $data['storage_id'],
                'price' => 0,
                'price_in' => 0,
                'quantity' => $data['quantity'],
                'quantity_wont' => $data['quantity'],
                'date' => Carbon::now()->timestamp,
            ]);


        }

				OrderServices::calcTotal($order);

        return 'ok';
    }

	protected function changeQuantity($id, $quantity, $order){
		$orderProduct = OrderProduct::find($id);
		$orderProduct->quantity = $quantity;
		$orderProduct->save();
	}

	public function removeOfOrder($id){
		$orderProduct = OrderProduct::with(['getCart'])->find($id);
        $order = $orderProduct->getCart;
		$orderProduct->delete();

        OrderServices::calcTotal($order);

		return 'ok';
	}

	public function allAjax(Request $request){
        $orders = OrderServices::getFilteredData($request);

		return datatables()
			->eloquent($orders)
			->addColumn('number_html', function (Order $order) {
				$number = $order->id;
				if($order->public_number){
					$number .= ' / '. $order->public_number;
				}else{
					$number .= ' / -';
				}
				return '<a href="'.route('orders.show',[$order->id]).'">'.$number.'</a>';
			})
			->addColumn('date_html', function (Order $order) {
				$date = Carbon::parse($order->date_add)->format('d.m.Y h:i');
				return $date;
			})
			->addColumn('status_html', function (Order $order) {
			    return '<div class="badge badge-'.OrderServices::getStatusClass($order->getStatus->id).' badge-status">'.$order->getStatus->name.'</div>';
			})
			->addColumn('payment_html', function (Order $order) {
                if($order->payments->count() > 0){
                    if($order->payments->sum('payed') < $order->total){
                        return '<div class="badge badge-'.OrderServices::getStatusClass(1).' badge-status">'.trans('order.payment_status_partial').'</div>';
                    }else{
                        return '<div class="badge badge-'.OrderServices::getStatusClass(2).' badge-status">'.trans('order.payment_status_success').'</div>';
                    }
                }else{
                    return '<div class="badge badge-'.OrderServices::getStatusClass(7).' badge-status">'.trans('order.payment_status_none').'</div>';
                }
			})
			->addColumn('total_html', function (Order $order) {
				return number_format($order->total,2,'.',' ');
			})
			->addColumn('sender', function (Order $order) {
				return $order->sender?$order->sender->name:'Dinmark';
			})
			->addColumn('customer', function (Order $order) {
				if($order->customer_id){
					if($order->customer_id > 0){
						return $order->customer->name;
					}else{
						$client = Client::find(-$order->customer_id);
						if($client){
							return '<i class="fas fa-users"></i> '.Client::find(-$order->customer_id)->name;
						}else{
							return trans('client.client_deleted');
						}
					}
				}else{
					return $order->getUser->name;
				}
			})
			->addColumn('actions', function (Order $order) {
                $number = $order->id;
                if($order->public_number){
                    $number .= ' / '. $order->public_number;
                }else{
                    $number .= ' / -';
                }
				return view('order.include.action_buttons',compact('order','number'));
			})
			->filterColumn('number_html', function($order, $keyword) {
				$order->where('id', 'like',["%{$keyword}%"])->orWhere('public_number', 'like',["%{$keyword}%"]);

			})
			->filter(function ($order) use ($request) {
				if(request()->has('name_html')){
					$order->whereHas('id', 'like',"%" . request('number_html') . "%")->orWhere()->whereHas('public_number', 'like',"%" . request('number_html') . "%");
				}
			}, true)
			->rawColumns(['number_html','article_show_html','image_html','author','customer','check_html','actions','article_holding','status_html','payment_html'])
			->toJson();
	}

    public function totalDataAjax(Request $request)
    {
        $orders = OrderServices::getFilteredData($request);
        $orders = $orders->get();
        $payed = 0;
        foreach ($orders as $order){
            $payed += $order->payments->sum('payed');
        }

        return response()->json([
            'status' => 'success',
            'pc'        => $orders->count(),
            'total'     => number_format($orders->sum('total'),2,'.',' '),
            'discount'  => number_format($orders->sum('discount'),2,'.',' '),
            'payed'     => number_format($payed,2,'.',' '),
            'not_payed'     => number_format($orders->sum('total') - $payed,2,'.',' '),
        ]);
	}

    public function find(Request $request)
    {
        $search = $request->name;
        $formatted_data = [];
        $orders = Order::with(['payments'])->whereHas('getUser', function ($users){
                $users->whereHas('getCompany',function ($companies){
                    $companies->where([
                        ['holding', auth()->user()->getCompany->holding],
                        ['holding', '<>', 0],
                    ])->orWhere([
                        ['id', auth()->user()->getCompany->id],
                    ]);
                });
            })
            ->where(function($orders) use ($search){
                    $orders->where('id','like',"%".$search."%")
                        ->orWhere('public_number','like',"%".$search."%");
                }
            )
            ->where('status','<=',2)
            ->orderBy('id','desc')
            ->limit(10)
            ->get();

        foreach ($orders as $order) {
            $number = $order->id;
            if($order->public_number){
                $number .= ' / '. $order->public_number;
            }else{
                $number .= ' / -';
            }
            $formatted_data [] = [
                'id' => $order->id,
                'text' => $number,
            ];
        }

        return \Response::json($formatted_data);

	}

	public function create(){
		SEOTools::setTitle(trans('order.page_create'));
		$order = Order::firstOrCreate([
			'user' => auth()->user()->id,
			'status' => 8,
			'total' => 0,
			'source' => 'b2b',
		]);

		$companies = Company::with(['users'])
		->where([
			['holding',auth()->user()->getCompany->holding],
			['holding','<>',0]
		])->orWhere('id',auth()->user()->company)->get();

		$clients = Client::with(['company'])
			->whereHas('company',function ($companies){
				$companies->where([
					['holding', auth()->user()->getCompany->holding],
					['holding', '<>', 0],
				])->orWhere([
					['id', auth()->user()->getCompany->id],
				]);
			})->get();


		return view('order.create',compact('order', 'companies', 'clients'));
	}

    public function copy($id)
    {
        $order = Order::find($id);

        $newOrder = Order::create([
            'user' => $order->user,
            'sender_id' => $order->sender_id,
            'customer_id' => $order->customer_id,
            'status' => 8,
            'shipping_id' => $order->shipping_id,
            'shipping_info' => $order->shipping_info,
            'payment_alias' => $order->payment_alias,
            'payment_id' => $order->payment_id,
            'total' => $order->total,
            'comment' => $order->comment,
            'source' => 'b2b',
        ]);

        foreach ($order->products as $orderProduct){
            OrderProduct::create([
                'cart' => $newOrder->id,
                'user' => $orderProduct->user,
                'active' => 1,
                'product_alias' => $orderProduct->product_alias,
                'product_id' => $orderProduct->product_id,
                'storage_alias' => $orderProduct->storage_alias,
                'price' => $orderProduct->price,
                'price_in' => $orderProduct->price_in,
                'quantity' => $orderProduct->quantity,
                'quantity_wont' => $orderProduct->quantity_wont,
                'date' => Carbon::now()->timestamp,
            ]);
        }

        OrderServices::calcTotal($newOrder);

        return redirect()->route('orders.show',[$newOrder->id]);
	}

	public function show($id){
		session()->forget('not_founds');
		session()->forget('not_available');

		$order = Order::with(['products.product.content','products.storage','payments'])->find($id);
		SEOTools::setTitle(trans('order.page_update').$order->id);
		$companies = Company::with(['users'])
			->where([
				['holding',auth()->user()->getCompany->holding],
				['holding','<>',0]
			])->orWhere('id',auth()->user()->company)->get();
		$curent_company = Company::find(session('current_company_id'));
		$products = [];
		$koef = $order->is_pdv?1.2:1;
		$weight = 0;

		foreach($order->products as $orderProduct){
			// $price = $orderProduct->price;//\App\Services\Product\Product::calcPrice($orderProduct->product)/100 * 1;
			//dd(Product::with('storages')->find($orderProduct->product_id)->storages);
			//$package = Product::with('storages')->find($orderProduct->product_id)->storages->where('storage_id',$orderProduct->storage_alias)->first()->package;
			 //$total = $price * $orderProduct->quantity/$package;

			 //counting total with discounts
			$productinfo = $orderProduct->product;
			$quantity = $orderProduct->quantity;
			$storageinfo = Product::with('storages')->find($orderProduct->product_id)->storages->where('storage_id',$orderProduct->storage_alias)
			->first();
			if($storageinfo){
				$package = $storageinfo->package;
			}
			else{
				continue;
			}
			$three_percent_discount_limit = $storageinfo->limit_1;
			$seven_percent_discount_limit = $storageinfo->limit_2;
			if (($quantity >= $seven_percent_discount_limit) && $seven_percent_discount_limit){
					$price = ProductServices::getPriceWithCoefUnformatted($productinfo,$storageinfo->id,0.93);
			}
			else if(($quantity >= $three_percent_discount_limit) && $three_percent_discount_limit){
					$price = ProductServices::getPriceWithCoefUnformatted($productinfo,$storageinfo->id,0.97);
			}
			else{
					$price = ProductServices::getPriceUnformatted($productinfo,$storageinfo->id);
			}
			$total = $price/100*$quantity;
 		//counting total with discounts

			//$price_without_discount = ProductServices::getPriceUnformatted($productinfo,$storageinfo->id);

			$storageProduct = null;
			if($orderProduct->storage){
				if($orderProduct->storage->storageProducts->firstWhere('product_id',$orderProduct->product_id)){
					$storageProduct = $orderProduct->storage->storageProducts->firstWhere('product_id',$orderProduct->product_id);
				}
			}

            $storage_prices = [];
            foreach ($orderProduct->product->storages as $storage){
								if($storage->amount == 0 || ($storage->amount < $storage->package)){
									continue;
								}
                $storage_prices[$storage->id]['price'] = ProductServices::getPriceUnformatted($productinfo,$storage->id);
								$storage_prices[$storage->id]['limit1'] = $storage->limit_1 ? $storage->limit_1 : 0;
								$storage_prices[$storage->id]['limit2'] = $storage->limit_2 ? $storage->limit_2 : 0;
								$storage_prices[$storage->id]['discount3'] = $storage->limit_1 ? ProductServices::getPriceWithCoefUnformatted($productinfo,$storageinfo->id,0.97) : 0;
								$storage_prices[$storage->id]['discount7'] = $storage->limit_2 ? ProductServices::getPriceWithCoefUnformatted($productinfo,$storageinfo->id,0.93) : 0;
								//$storage_prices[$storage->id]['discount0'] = ProductServices::getPriceUnformatted($productinfo,$storageinfo->id);

								// if($three_percent_discount_limit){
								// 	$storage_prices[$storage->id]['discount3'] = ProductServices::getPriceWithCoefUnformatted($productinfo,$storageinfo->id,0.97);
								// }else{
								// 	$storage_prices[$storage->id]['discount3'] = ProductServices::getPriceUnformatted($productinfo,$storageinfo->id);
								// }
								// if($seven_percent_discount_limit){
								// 	$storage_prices[$storage->id]['discount7'] = ProductServices::getPriceWithCoefUnformatted($productinfo,$storageinfo->id,0.93);
								// }else{
								// 	$storage_prices[$storage->id]['discount7'] = ProductServices::getPriceUnformatted($productinfo,$storageinfo->id);
								// }
								// ->where([['target','=','b2b'],['active',1]])
            }
            $weight += $orderProduct->product->weight * $orderProduct->quantity/100;


			$products[] = [
				'id'	=> $orderProduct->id,
				'product_id'	=> $orderProduct->product->id,
				'name' => \App\Services\Product\Product::getName($orderProduct->product),
				'quantity' => $orderProduct->quantity,
				'min' => ($storageProduct)?$storageProduct->package:0,
				'max' => ($storageProduct)?$storageProduct->amount:0,
				'package' => ($storageProduct)?$orderProduct->quantity/$storageProduct->package:0,
				'weight' => $orderProduct->product->weight,
				'price' => number_format($price,2,'.', ' '),
				// 'price_without_discount' => number_format($price_without_discount,2,'.',' '),
				// 'price_without_discount_raw' => $price_without_discount,
				'price_raw' => $price,
                'storages'  => $orderProduct->product->storages->filter(function ($value, $key) {
										if($value->amount > 0 && $value->amount - $value->package > 0){
											return $value;
										}
									}),
                'storage_prices' => $storage_prices,
                'storage_id' => $orderProduct->storage_alias,
				'total' => number_format($total,2,'.', ' '),
				'total_raw' => $total,
			];
		}

		$clients = Client::with(['company'])
			->whereHas('company',function ($companies){
				$companies->where([
					['holding', auth()->user()->getCompany->holding],
					['holding', '<>', 0],
				])->orWhere([
					['id', auth()->user()->getCompany->id],
				]);
			})->get();

		$shippings = Shipping::where('active',1)
            ->orderBy('position','ASC')
            ->get();

		if($order->status == 8){
			return view('order.show',compact('order', 'companies', 'curent_company', 'products', 'koef', 'clients', 'shippings', 'weight'));
		}else{
            $implementations = Implementation::with(['products.orderProduct.product.content','products.orderProduct.getCart'])
                ->whereHas('products',function ($products) use ($order){
                    $products->whereHas('orderProduct',function ($orderProduct) use ($order){
                        $orderProduct->where('cart',$order->id);
                    });
                })->get();
            $implementationsData = [];
            foreach ($implementations as $implementation){
                $implProducts = [];
                foreach ($implementation->products as $implementationProduct){
                    if($implementationProduct->orderProduct){
                        if($implementationProduct->orderProduct->cart == $order->id)
                        {
                            $implProducts[] = [
                                'product_id' => $implementationProduct->orderProduct->product->id,
                                'name' => \App\Services\Product\Product::getName($implementationProduct->orderProduct->product),
                                'quantity' => $implementationProduct->quantity,
                                'total' => number_format($implementationProduct->total, 2, ',', ' '),
                                'order' => $implementationProduct->orderProduct->getCart ? $implementationProduct->orderProduct->getCart->id : '?',
                                'order_number' => $implementationProduct->orderProduct->getCart ? ($implementationProduct->orderProduct->getCart->public_number ?? $implementationProduct->orderProduct->getCart->id) : '?',
                            ];
                        }
                    }
                }


                $implementationsData[] = [
                    'id' =>   $implementation->id,
                    'public_number' =>   $implementation->public_number,
                    'date' =>   Carbon::parse($implementation->date_add)->format('d.m.Y'),
                    'sender' =>   $implementation->sender_id == 0? 'Dinmark':$implementation->sender->name,
                    'customer' =>  $implementation->customer_id < 0? 'Клиент':$implementation->customer->name,
                    'ttn' =>  $implementation->ttn,
                    'weight' =>  number_format($implementation->weight,2,',',' '),
                    'status' =>  $implementation->status,
                    'products' => $implProducts
                ];
            }


			return view('order.show_order',compact('order', 'companies', 'curent_company', 'products', 'koef', 'weight', 'clients', 'shippings','implementationsData'));
		}

	}

	public function update($id, Request $request){
		session()->forget('not_founds');
		session()->forget('not_available');

		$newClient = null;

		$order = Order::with(['products.product.storages','products.storage'])->find($id);
		$order->sender_id = $request->sender_id;
		$order->customer_id = $request->customer_id;

		if($request->customer_id > 0){
			$order->user = $request->customer_id;
		}elseif($request->customer_id < 0){
			if($request->sender_id > 0){
				$order->user = $request->sender_id;
			}else{
				$client = Client::find(-$request->customer_id);
				OrderServices::setClientInfo($order,$client->id);
				$user = $client->company->users->first();
				if($user){
					$order->user = $user->id;
				}else{
					$order->user = auth()->user()->id;
				}
			}

            OrderServices::setClientInfo($order,-$request->customer_id);
		}else{
			$newClient = Client::create([
				'name' => $request->client_name,
				'company_name'  => $request->client_company,
				'company_edrpo'  => $request->client_edrpo,
				'email'  => $request->client_email,
				'phone'  => $request->client_phone,
				'address'  => $request->client_address,
				'company_id'  => auth()->user()->company,
			]);
			$order->customer_id = -$newClient->id;
            OrderServices::setClientInfo($order,$newClient);

			if($request->sender_id > 0){
				$order->user = $request->sender_id;
			}else{
				$order->user = auth()->user()->id;
			}
		}

		$order->comment = $request->comment;

        foreach ($order->products as $orderProduct){
            if($request->has('product_storage')){

                $orderProduct->storage_alias = $request->product_storage[$orderProduct->id];
            }
            if($request->has('product_quantity')){
                $orderProduct->quantity = $request->product_quantity[$orderProduct->id];
            }
            $orderProduct->save();
        }

        OrderServices::calcTotal($order);

        OrderServices::setAddressInfo($order,$request);
		$order->save();

		if($request->submit == 'add_product'){
			$this->addToOrder($id, $request);
		}

		if($request->submit == 'import_product'){
			$validatedData = $request->validate([
				'import' => 'required|mimes:xls,xlsx,csv'
			]);

			if(!is_array($validatedData) ){
				if($validatedData->fails()) {
					return Redirect::back()->withErrors($validatedData);
				}
			}

			Excel::import(new OrderImport($order), request()->file('import'));

		}

		if($request->submit == 'order'){
			$order->status = 1;
		}

		$order->save();

		if($request->submit == 'cp_generate'){
			$products = [];
			$companyPrice = CompanyPrice::find($request->has('cp_price_id')?$request->cp_price_id:0);
			$orderTotal = 0;
			$client = Client::find($request->cp_client_id);
            $company = Company::where('id',session('current_company_id'))->first();
			foreach($order->products as $orderProduct){
				//$price = \App\Services\Product\Product::calcPriceWithoutPDV($orderProduct->product)/100 * (($companyPrice)?$companyPrice->koef:1);
				$price = $orderProduct->price/120 * 100;
				$total = round($price * $orderProduct->quantity,2);
				$orderTotal += $total;

				$products[] = [
					'id'	=> $orderProduct->id,
					'name' => \App\Services\Product\Product::getName($orderProduct->product,'uk'),
					'quantity' => $orderProduct->quantity/100,
					'package' => 100,
					//'price' => number_format($price*100,2,',', ' '),
					'price' => number_format($price,2,',', ' '),
					'total' => number_format($total,2,',', ' '),
					'storage_termin' => $orderProduct->storage->term,
				];
			}


			$pdf = PDF::loadView('order.pdf', [
				'order' => $order,
				'date' => TimeServices::getFromTime($order->date_add),
				'products' => $products,
				'total' => number_format($orderTotal, 2, ',', ' '),
				'pdv' => number_format($orderTotal*0.2, 2, ',', ' '),
				'totalPdv' => number_format($orderTotal * 1.2, 2, ',', ' '),
				'pdv_text' => \App\Services\Product\Product::getStringPrice($orderTotal*0.2),
				'totalPdv_text' => \App\Services\Product\Product::getStringPrice($orderTotal*1.2),
				'client'	=> $client,
				'company'	=> $company,
			]);
			$pdf->setOption('enable-smart-shrinking', true);
			$pdf->setOption('no-stop-slow-scripts', true);
			return $pdf->download(($order->sender?$order->sender->getCompany->prefix:'').'_'.$order->id.'.pdf');
		}


        return redirect()->route('orders.show',$id);
	}

	public function PDFBill($id)
	{
		$order = Order::with(['products.product.storages','products.storage'])->find($id);
		$products = [];
		$orderTotal = 0;

		foreach($order->products as $orderProduct){
			$price = $orderProduct->price/120 * 100;
			$total = round($price * $orderProduct->quantity,2);
			$orderTotal += $total;

			$products[] = [
				'id'	=> $orderProduct->id,
				'name' => \App\Services\Product\Product::getName($orderProduct->product,'uk'),
				'quantity' => $orderProduct->quantity/100,
				'package' => 100,
				'price' => number_format($price*100,2,',', ' '),
				'total' => number_format($total,2,',', ' '),
			];
		}

		$pdf = PDF::loadView('order.pdf_bill', [
			'order' => $order,
			'user' => $order->getUser,
			'date' => TimeServices::getFromTime($order->date_add),
			'products' => $products,
			'total' => number_format($orderTotal, 2, ',', ' '),
			'pdv' => number_format($orderTotal*0.2, 2, ',', ' '),
			'totalPdv' => number_format($orderTotal * 1.2, 2, ',', ' '),
			'pdv_text' => \App\Services\Product\Product::getStringPrice($orderTotal*0.2),
			'totalPdv_text' => \App\Services\Product\Product::getStringPrice($orderTotal*1.2),
		]);
		$pdf->setOption('enable-smart-shrinking', true);
		$pdf->setOption('no-stop-slow-scripts', true);
		return $pdf->download(($order->sender?$order->sender->getCompany->prefix.'_':'').'bill_'.$order->id.'.pdf');
	}

	public function PDFAct(Request $request)
	{
		$user = auth()->user();
		$dateFromCarbon = Carbon::parse(0);
        if($request->has('date_from')){
            $dateFromCarbon = Carbon::createFromTimestamp($request->date_from);
        }

        $dateToCarbon = Carbon::now();
        if($request->has('date_to'))
        {
            if($request->date_to != ''){
                $dateToCarbon = Carbon::createFromTimestamp($request->date_to)->addDay();
            }
        }
        $dateFrom = $dateFromCarbon->timestamp;
        $dateTo = $dateToCarbon->timestamp;

		$company = Company::find(session('current_company_id'));

        $implementations = BalanceServices::getFilteredImplementation($request)->get();
        $payments = BalanceServices::getFilteredPayment($request)->get();

		$actData = $implementations->concat($payments)->sortBy('date_add');
		if($actData->count() > 0){
            $dateFromCarbon = Carbon::parse($actData->first()->date_add);
        }


		$saldo = BalanceServices::calcSaldo($request);

		$pdf = PDF::loadView('order.pdf_act', array_merge ([
			'user' => $user,
			'company' => $company,
			'actData'=> $actData,
			'implementtions' => $implementations,
			'payments' => $payments,
			'dateFromCarbon' => $dateFromCarbon,
			'dateToCarbon' => $dateToCarbon,
		],$saldo));

		$pdf->setOption('enable-smart-shrinking', true);
		$pdf->setOption('no-stop-slow-scripts', true);
		return $pdf->download(($company->prefix.'_').'act'.'.pdf');

	}

    public function toOrder($id)
    {
        $order = Order::find($id);
        if(empty($order)){
            abort(404);
        }

        if($order->status == 8){
            $order->status = 1;
            $order->save();
        }
				//Заявка->Замовлення

				if($order->status == 7){
					$order->status = 8;
					$order->save();
				}
				//Архів->Заявка


        return redirect()->route('orders.show',['id'=>$id]);
	}

    public function toCancel($id)
    {
        $order = Order::find($id);
        if(empty($order)){
            abort(404);
        }

        if($order->status == 1 || $order->status == 8){
            $order->status = 7;
            $order->save();
        }

        return redirect()->route('orders.show',['id'=>$id]);
	}
}
