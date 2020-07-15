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
use App\Models\Product\Product;
use App\Services\Order\OrderServices;
use App\Services\TimeServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use Excel;
use PDF;

class OrderController extends Controller
{
	public function index(){
		SEOTools::setTitle(trans('order.page_list'));
		$statuses = OrderStatus::all();
		return view('order.index',compact('statuses'));
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
            'price_in' => $product->price,
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

	protected function changeQuantity($id, $quantity, $order){
		$orderProduct = OrderProduct::find($id);
		$orderProduct->quantity = $quantity;
		$orderProduct->save();

		OrderServices::calcTotal($order);
	}

	public function removeOfOrder($id){
		$orderProduct = OrderProduct::with(['getCart'])->find($id);
        $order = $orderProduct->getCart;
		$orderProduct->delete();

        OrderServices::calcTotal($order);

		return 'ok';
	}

	public function allAjax(Request $request){
		$orders = Order::whereHas('getUser', function ($users){
							$users->where('company',auth()->user()->company);
						});

		if($request->has('tab')){
		    if($request->tab == 'order'){
                $orders->where('status','<=',5);
            }
		    if($request->tab == 'archive'){
                $orders->where([
                    ['status','>=',6],
                    ['status','<=',7],
                ]);
            }
		    if($request->tab == 'request'){
                $orders->where('status',8);
            }
		}

		if($request->has('status_id')){
			$orders->where('status',$request->status_id);
		}

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
				return $order->getStatus->name;
			})
			->addColumn('payment_html', function (Order $order) {
				return $order->getStatus->name;
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
				return view('order.include.action_buttons',compact('order'));
			})
			->filterColumn('number_html', function($order, $keyword) {
				$order->where('id', 'like',["%{$keyword}%"])->orWhere('public_number', 'like',["%{$keyword}%"]);

			})
			->filter(function ($order) use ($request) {
				if(request()->has('name_html')){
					$order->whereHas('id', 'like',"%" . request('number_html') . "%")->orWhere()->whereHas('public_number', 'like',"%" . request('number_html') . "%");
				}
			}, true)
			->rawColumns(['number_html','article_show_html','image_html','author','customer','check_html','actions','article_holding'])
			->toJson();
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

	public function show($id){
		session()->forget('not_founds');
		session()->forget('not_available');

		$order = Order::with(['products.product.content','products.storage'])->find($id);
		SEOTools::setTitle(trans('order.page_update').$order->id);
		$companies = Company::with(['users'])
			->where([
				['holding',auth()->user()->getCompany->holding],
				['holding','<>',0]
			])->orWhere('id',auth()->user()->company)->get();
		$curent_company = Company::find(session('current_company_id'));
		$products = [];
		$koef = $order->is_pdv?1.2:1;

		foreach($order->products as $orderProduct){
			$price = $orderProduct->price;//\App\Services\Product\Product::calcPrice($orderProduct->product)/100 * 1;

			$total = $price * $orderProduct->quantity;

			$storageProduct = null;
			if($orderProduct->storage){
				if($orderProduct->storage->storageProducts->firstWhere('product_id',$orderProduct->product_id)){
					$storageProduct = $orderProduct->storage->storageProducts->firstWhere('product_id',$orderProduct->product_id);
				}
			}

			$products[] = [
				'id'	=> $orderProduct->id,
				'product_id'	=> $orderProduct->product->id,
				'name' => \App\Services\Product\Product::getName($orderProduct->product),
				'quantity' => $orderProduct->quantity,
				'min' => ($storageProduct)?$storageProduct->package:0,
				'max' => ($storageProduct)?$storageProduct->amount:0,
				'price' => number_format($price*100,2,'.', ' '),
				'price_raw' => $price,
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

		if($order->status == 8){
			return view('order.show',compact('order', 'companies', 'curent_company', 'products', 'koef', 'clients'));
		}else{
			return view('order.show_order',compact('order', 'companies', 'curent_company', 'products', 'koef', 'clients'));
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
				$user = $client->company->users->first();
				if($user){
					$order->user = $user->id;
				}else{
					$order->user = auth()->user()->id;
				}
			}
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

			if($request->sender_id > 0){
				$order->user = $request->sender_id;
			}else{
				$order->user = auth()->user()->id;
			}
		}

		$order->comment = $request->comment;
		if($request->has('product_quantity')){
			foreach ($request->product_quantity as $key => $quantity){
				$this->changeQuantity($key, $quantity, $order);
			}
		}

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
					'price' => number_format($price*100,2,',', ' '),
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
		$dateFromCarbon = Carbon::parse($request->act_date_from);
		$dateToCarbon = Carbon::parse($request->act_date_to)->addDay();
		$dateFrom = $dateFromCarbon->timestamp;
		$dateTo = $dateToCarbon->timestamp;

		$company = Company::find(session('current_company_id'));
		$implementations =  Implementation::where(function ($impl){
				$impl->whereHas('sender',function ($users){
					$users->whereHas('getCompany',function ($companies){
						$companies->where([
							['id', session('current_company_id')],
						]);
					});
				})
				->orWhereHas('customer',function ($users){
					$users->whereHas('getCompany',function ($companies){
						$companies->where([
							['id', session('current_company_id')],
						]);
					});
				});
			})
			->where('date_add','>=',$dateFrom)
			->where('date_add','<=',$dateTo)
			->get();
		$payments = Payment::whereHas('order',function ($orders) use ($user){
				$orders->whereHas('getUser',function ($users) use ($user){
					$users->whereHas('getCompany',function ($companies){
						$companies->where([
							['id', session('current_company_id')],
						]);
					});
				})->orWhereHas('sender',function ($users) use ($user){
					$users->whereHas('getCompany',function ($companies){
						$companies->where([
							['id', session('current_company_id')],
						]);
					});
				});
			})
			->where('date_add','>=',$dateFrom)
			->where('date_add','<=',$dateTo)
			->get();


		$actData = $implementations->concat($payments)->sortBy('date_add');

		$saldoStart = 0;
		$saldoEnd = 0;
		$implementations = Implementation::where(function ($impl){
			$impl->whereHas('sender',function ($users){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['id', session('current_company_id')],
					]);
				});
			})
				->orWhereHas('customer',function ($users){
					$users->whereHas('getCompany',function ($companies){
						$companies->where([
							['id', session('current_company_id')],
						]);
					});
				});
		})->where('date_add','<',$dateFrom)->get();

		$payments = Payment::whereHas('order',function ($orders) use ($user){
			$orders->whereHas('getUser',function ($users) use ($user){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['id', session('current_company_id')],
					]);
				});
			})->orWhereHas('sender',function ($users) use ($user){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['id', session('current_company_id')],
					]);
				});
			});
		})
			->where('date_add','<',$dateFrom)
			->get();

		foreach ($implementations as $implementation){
			$saldoStart += $implementation->products->sum('total');
		}
		foreach ($payments as $payment){
			$saldoStart -= $payment->payed;
		}

		$implementations = Implementation::where(function ($impl){
			$impl->whereHas('sender',function ($users){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['id', session('current_company_id')],
					]);
				});
			})
				->orWhereHas('customer',function ($users){
					$users->whereHas('getCompany',function ($companies){
						$companies->where([
							['id', session('current_company_id')],
						]);
					});
				});
		})->where('date_add','>',$dateTo)->get();

		$payments = Payment::whereHas('order',function ($orders) use ($user){
			$orders->whereHas('getUser',function ($users) use ($user){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['id', session('current_company_id')],
					]);
				});
			})->orWhereHas('sender',function ($users) use ($user){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['id', session('current_company_id')],
					]);
				});
			});
		})
			->where('date_add','>',$dateTo)
			->get();

		foreach ($implementations as $implementation){
			$saldoEnd += $implementation->products->sum('total');
		}
		foreach ($payments as $payment){
			$saldoEnd -= $payment->payed;
		}


		$pdf = PDF::loadView('order.pdf_act', [
			'user' => $user,
			'company' => $company,
			'actData'=> $actData,
			'implementtions' => $implementations,
			'payments' => $payments,
			'dateFromCarbon' => $dateFromCarbon,
			'dateToCarbon' => $dateToCarbon,
			'saldoStart' => $saldoStart,
			'saldoEnd' => $saldoEnd,
		]);
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

        if($order->status >= 7){
            $order->status = 1;
            $order->save();
        }

        return redirect()->route('orders.show',['id'=>$id]);
	}

    public function toCancel($id)
    {
        $order = Order::find($id);
        if(empty($order)){
            abort(404);
        }

        if($order->status == 1){
            $order->status = 7;
            $order->save();
        }

        return redirect()->route('orders.show',['id'=>$id]);
	}
}
