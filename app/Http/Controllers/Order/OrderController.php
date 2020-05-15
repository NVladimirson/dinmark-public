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
		$price = abs(\App\Services\Product\Product::calcPrice($product)/(float)100);
		$total = round($price * $request->quantity,2);
		if($id == 0){
			$order = Order::create([
				'user' => auth()->user()->id,
				'status' => 8,
				'total' => $total,
				'source' => 'b2b',
			]);
		}else{
			$order = Order::find($id);
			$order->total += $total;
			$order->save();
		}

		OrderProduct::create([
			'cart' => $order->id,
			'user' => auth()->user()->id,
			'active' => 1,
			'product_alias' => $product->wl_alias,
			'product_id' => $product->id,
			'storage_alias' => $request->storage_id,
			'price' => $price,
			'price_in' => $product->price,
			'quantity' => $request->quantity,
			'quantity_wont' => $request->quantity,
			'date' => Carbon::now()->timestamp,
		]);

		return 'ok';
	}

	protected function changeQuantity($id, $quantity, $order){
		$orderProduct = OrderProduct::find($id);
		$total  = round($orderProduct->price*$orderProduct->quantity, 2);
		$order->total -= $total;
		$orderProduct->quantity = $quantity;
		$orderProduct->save();
		$order->total += round($orderProduct->price*$orderProduct->quantity, 2);
		$order->save();
	}

	public function removeOfOrder($id){
		$orderProduct = OrderProduct::with(['getCart'])->find($id);

		$orderProduct->getCart->total -= round($orderProduct->price*$orderProduct->quantity, 2);
		$orderProduct->getCart->save();
		$orderProduct->delete();

		return 'ok';
	}

	public function allAjax(Request $request){
		$orders = Order::whereHas('getUser', function ($users){
							$users->where('company',auth()->user()->company);
						})
					->orWhereHas('sender', function ($users){
						$users->where('company',auth()->user()->company);
					})->orderBy('id','desc');

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
				return $number;
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
			->addColumn('customer', function (Order $order) {
				return $order->sender?$order->sender->name:'Dinmark';
			})
			->addColumn('author', function (Order $order) {
				if($order->user > 0){
					return $order->getUser->name;
				}else{
					return '<i class="fas fa-users"></i> '.Client::find(-$order->user)->name;
				}


			})
			->addColumn('actions', function (Order $order) {
				return view('order.include.action_buttons',compact('order'));
			})
			->rawColumns(['name_html','article_show_html','image_html','author','check_html','actions','article_holding'])
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

		$order = Order::with(['products.product','products.storageProduct'])->find($id);
		SEOTools::setTitle(trans('order.page_update').$order->id);
		$companies = Company::with(['users'])
			->where([
				['holding',auth()->user()->getCompany->holding],
				['holding','<>',0]
			])->orWhere('id',auth()->user()->company)->get();

		$products = [];
		$koef = $order->is_pdv?1.2:1;

		foreach($order->products as $orderProduct){
			$price = $orderProduct->price;//\App\Services\Product\Product::calcPrice($orderProduct->product)/100 * 1;

			$total = $price * $orderProduct->quantity;


			$products[] = [
				'id'	=> $orderProduct->id,
				'product_id'	=> $orderProduct->product->id,
				'name' => \App\Services\Product\Product::getName($orderProduct->product),
				'quantity' => $orderProduct->quantity,
				'min' => ($orderProduct->storageProduct)?$orderProduct->storageProduct->package:0,
				'max' => ($orderProduct->storageProduct)?$orderProduct->storageProduct->amount:0,
				'price' => number_format($price*100,2,'.', ' '),
				'total' => number_format($total,2,'.', ' '),
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
			return view('order.show',compact('order', 'companies', 'products', 'koef', 'clients'));
		}else{
			return view('order.show_order',compact('order', 'companies', 'products', 'koef', 'clients'));
		}

	}

	public function update($id, Request $request){
		session()->forget('not_founds');
		session()->forget('not_available');

		$order = Order::with(['products.product.storages','products.storageProduct.storage'])->find($id);
		$order->sender_id = $request->sender_id;
		$order->user = $request->customer_id;
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

			foreach($order->products as $orderProduct){
				$price = \App\Services\Product\Product::calcPriceWithoutPDV($orderProduct->product)/100 * (($companyPrice)?$companyPrice->koef:1);

				$total = $price * $orderProduct->quantity;
				$orderTotal += $total;

				$products[] = [
					'id'	=> $orderProduct->id,
					'name' => \App\Services\Product\Product::getName($orderProduct->product,'uk'),
					'quantity' => $orderProduct->quantity/100,//number_format($orderProduct->quantity/$orderProduct->storageProduct->package,1,',',' '),
					'package' => 100,//$orderProduct->storageProduct->package,
					'price' => number_format($price*100,2,',', ' '),
					'total' => number_format($total,2,',', ' '),
					'storage_termin' => $orderProduct->storageProduct->storage->term,
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
				'client'	=> $client
			]);
			$pdf->setOption('enable-smart-shrinking', true);
			$pdf->setOption('no-stop-slow-scripts', true);
			return $pdf->download(($order->sender?$order->sender->getCompany->prefix:'').'_'.$order->id.'.pdf');
		}

		return redirect()->route('orders.show',$id);
	}

	public function PDFBill($id)
	{
		$order = Order::with(['products.product.storages','products.storageProduct.storage'])->find($id);
		$products = [];
		$orderTotal = 0;

		foreach($order->products as $orderProduct){
			$price = \App\Services\Product\Product::calcPriceWithoutPDV($orderProduct->product)/100 ;

			$total = $price * $orderProduct->quantity;
			$orderTotal += $total;

			$products[] = [
				'id'	=> $orderProduct->id,
				'name' => \App\Services\Product\Product::getName($orderProduct->product,'uk'),
				'quantity' => $orderProduct->quantity/100,
				'package' => 100,
				'price' => number_format($price*100,2,',', ' '),
				'total' => number_format($total,2,',', ' '),
				//'storage_termin' => $orderProduct->storageProduct->storage->term,
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

	public function PDFAct()
	{
		$user = auth()->user();
		$implementations =  Implementation::whereHas('sender',function ($users){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['id', auth()->user()->getCompany->id],
					]);
				});
			})
			->orWhereHas('customer',function ($users){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['id', auth()->user()->getCompany->id],
					]);
				});
			})->get();
		$payments = Payment::whereHas('order',function ($orders) use ($user){
			$orders->whereHas('getUser',function ($users) use ($user){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['id', auth()->user()->getCompany->id],
					]);
				});
			})->orWhereHas('sender',function ($users) use ($user){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['id', auth()->user()->getCompany->id],
					]);
				});
			});
		})->get();

		$actData = $implementations->concat($payments)->sortBy('date_add');

		$pdf = PDF::loadView('order.pdf_act', [
			'user' => $user,
			'actData'=> $actData,
			'implementtions' => $implementations,
			'payments' => $payments,
		]);
		$pdf->setOption('enable-smart-shrinking', true);
		$pdf->setOption('no-stop-slow-scripts', true);
		return $pdf->download(($user->getCompany->prefix.'_').'act'.'.pdf');

	}

}
