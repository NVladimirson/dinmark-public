<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Company\Client;
use App\Models\Order\Implementation;
use App\Models\Order\ImplementationProduct;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Services\Order\ImplementationServices;
use App\Services\TimeServices;
use App\User;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Carbon;
use PDF;

class ImplementationController extends Controller
{
    public function index(Request $request){
		SEOTools::setTitle(trans('implementation.page_list'));

        $sendersId =  ImplementationServices::getFilteredData($request)
            ->groupBy('sender_id')
            ->pluck('sender_id');
        $senders = User::whereIn('id',$sendersId)->pluck('id','name')->toArray();
        if($sendersId->has(0)){
            $senders = array_merge(['Dinmark'=>0],$senders);
        }

        $customersId =  ImplementationServices::getFilteredData($request)
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

		return view('order.implementation',compact('senders','customers'));
	}

	public function ajax(Request $request)
	{
		$implementations = ImplementationServices::getFilteredData($request)
			->orderBy('id','desc');

		return datatables()
			->eloquent($implementations)
			->addColumn('date',function (Implementation $implementation){

				return Carbon::parse($implementation->date_add)->format('d.m.Y');
			})
			->addColumn('sender',function (Implementation $implementation){
				if($implementation->sender_id == 0){
					return 'Dinmark';
				}else{
					return $implementation->sender->name;
				}
			})
			->addColumn('customer',function (Implementation $implementation){
				if($implementation->customer_id < 0){
                    $client = Client::find(-$implementation->customer_id);
                    if($client){
                        return '<i class="fas fa-users"></i> '.$client->name;
                    }else{
                        return trans('client.client_deleted');
                    }
				}else{
					return $implementation->customer->name;
				}
			})
			->addColumn('weight_html',function (Implementation $implementation){

				return number_format($implementation->weight,2,',',' ');
			})
			->addColumn('total',function (Implementation $implementation){

				return number_format($implementation->products->sum('total'),2,',',' ');
			})
			->addColumn('ttn_html',function (Implementation $implementation){
                if(strpos($implementation->ttn, '2045') === 0 || strpos($implementation->ttn, '5900') === 0){
                    return '<a href="https://novaposhta.ua/tracking/?cargo_number='.$implementation->ttn.'" target="_blank">'.$implementation->ttn.'</a>';
                }else{
                    return $implementation->ttn;
                }
			})
			->addColumn('actions_btn',function (Implementation $implementation){
                return view('order.include.implementation_action_buttons',compact('implementation'))->render();
			})
			->addColumn('products',function (Implementation $implementation){
				$products = [];
				foreach ($implementation->products as $implementationProduct){
				    if($implementationProduct->orderProduct){
                        $products[] = [
                            'product_id'	=> $implementationProduct->orderProduct->product->id,
                            'name'			=> \App\Services\Product\Product::getName($implementationProduct->orderProduct->product),
                            'quantity'		=> $implementationProduct->quantity,
                            'total'			=> number_format($implementationProduct->total,2,',',' '),
                            'order'			=> $implementationProduct->orderProduct->getCart?$implementationProduct->orderProduct->getCart->id:'?',
                            'order_number'	=> $implementationProduct->orderProduct->getCart?($implementationProduct->orderProduct->getCart->public_number ?? $implementationProduct->orderProduct->getCart->id):'?',
                        ];
                    }else{
                        $products[] = [
                            'product_id'	=> 0,
                            'name'			=> '?',
                            'quantity'		=> $implementationProduct->quantity,
                            'total'			=> number_format($implementationProduct->total,2,',',' '),
                            'order'			=> '?',
                            'order_number'	=> '?',
                        ];
                    }
				}
				return view('order.include.implementation_products',compact(['products']))->render();
			})
			->rawColumns(['products','actions_btn','ttn_html'])
			->toJson();
	}

    public function totalDataAjax(Request $request)
    {
        $implementations = ImplementationServices::getFilteredData($request);
        $implementations = $implementations->get();

        $total = 0;
        $weight = $implementations->sum('weight')/100;

        foreach ($implementations as $implementation){
            $total += $implementation->products->sum('total');
        }

        return response()->json([
            'status' => 'success',
            'pc'        => $implementations->count(),
            'total'     => number_format($total,2,'.',' '),
            'weight'  => number_format($weight,2,'.',' '),
        ]);
    }

	public function find(Request $request)
	{
		$search = $request->name;
		$formatted_data = [];

		$implementations = Implementation::whereHas('sender',function ($users){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['holding', auth()->user()->getCompany->holding],
						['holding', '<>', 0],
					])->orWhere([
						['id', auth()->user()->getCompany->id],
					]);
				});
			})
			->orWhereHas('customer',function ($users){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['holding', auth()->user()->getCompany->holding],
						['holding', '<>', 0],
					])->orWhere([
						['id', auth()->user()->getCompany->id],
					]);
				});
			})
			->where(function ($userImplementation) use ($search){
				$userImplementation
					->where('id','like',"%".$search."%")
					->orWhere('public_number','like',"%".$search."%");
			})
			->orderBy('id','desc')
			->limit(10)
			->get();

		foreach ($implementations as $implementation) {
			$formatted_data [] = [
				'id' => $implementation->id,
				'text' => $implementation->id.' ('.$implementation->public_number.')',
			];
		}

		return \Response::json($formatted_data);
	}

	public function getProductsAjax($id)
	{
		$implementationProducts = ImplementationProduct::with(['orderProduct.product'])
			->where('implementation_id',$id)
			->get();

		$formatted_data = [];

		foreach ($implementationProducts as $implementationProduct){
			$formatted_data[] = [
				'id'	=> $implementationProduct->id,
				'product_id'	=> $implementationProduct->orderProduct->product->id,
				'name'	=> \App\Services\Product\Product::getName($implementationProduct->orderProduct->product).'('.$implementationProduct->orderProduct->product->article_show.')',
				//'min'	=> $implementationProduct->quantity,
				'max'	=> $implementationProduct->quantity,
			];
		}

		return \Response::json($formatted_data);
	}

	public function generatePDF($id){
		$implementation = Implementation::with(['products.orderProduct.product','products.orderProduct.getCart'])->find($id);

		$products = [];
		$orderTotal = 0;

		foreach($implementation->products as $implementationProduct){
			$price = ($implementationProduct->total/ $implementationProduct->quantity)/120 * 100;

			$total = $implementationProduct->total/120 * 100;
			$orderTotal += $total;

			$products[] = [
				'id'	=> $implementationProduct->id,
				'name' => \App\Services\Product\Product::getName($implementationProduct->orderProduct->product,'uk'),
				'quantity' => $implementationProduct->quantity/100,
				'package' => 100,
				'price' => number_format($price*100,2,',', ' '),
				'total' => number_format($total,2,',', ' '),
			];
		}
		$user = null;
		$company = null;
		if($implementation->products->first()->orderProduct->getCart->user > 0){
			$user = $implementation->products->first()->orderProduct->getCart->getUser;
			$company = $user->getCompany;
		}else{
			$user = Client::find(-$implementation->products->first()->orderProduct->getCart->user);
			$company = $user->company;
		}



		$pdf = PDF::loadView('order.pdf_expense_invoice', [
			'implementation' => $implementation,
			'date' => TimeServices::getFromTime($implementation->date_add),
			'products' => $products,
			'total' => number_format($orderTotal, 2, ',', ' '),
			'pdv' => number_format($orderTotal*0.2, 2, ',', ' '),
			'totalPdv' => number_format($orderTotal * 1.2, 2, ',', ' '),
			'pdv_text' => \App\Services\Product\Product::getStringPrice($orderTotal*0.2),
			'totalPdv_text' => \App\Services\Product\Product::getStringPrice($orderTotal*1.2),
			'user'	=> $user
		]);
		$pdf->setOption('enable-smart-shrinking', true);
		$pdf->setOption('no-stop-slow-scripts', true);
		return $pdf->download(($company->prefix).'_'.$id.'.pdf');
	}
}
