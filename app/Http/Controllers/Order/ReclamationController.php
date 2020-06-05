<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Reclamation\Reclamation;
use App\Models\Reclamation\ReclamationProduct;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class ReclamationController extends Controller
{
	public function index(){
		SEOTools::setTitle(trans('reclamation.page_list'));
		return view('reclamation.index');
	}

	public function ajax(Request $request){
		$reclamations = Reclamation::with(['user','products.product.orderProduct.product'])
			->whereHas('user',function ($users){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['holding', auth()->user()->getCompany->holding],
						['holding', '<>', 0],
					])->orWhere([
						['id', auth()->user()->getCompany->id],
					]);
				});
			})
			->orderBy('id','desc');

		return datatables()
			->eloquent($reclamations)
			->addColumn('implementation',function (Reclamation $reclamation){


				return $reclamation->products->first()->product->implementation->public_number;
			})

            ->addColumn('products',function (Reclamation $reclamation){
                $products = [];
                foreach ($reclamation->products as $reclamationProduct){
                    $class = '';
                    switch ($reclamationProduct->status){
                        case 'wait':
                            $class = 'label-default';
                            break;
                        case 'consideration':
                            $class = 'label-yellow';
                            break;
                        case 'return':
                            $class = 'label-green';
                            break;
                        case 'change':
                            $class = 'label-green';
                            break;
                        case 'fail':
                            $class = 'label-danger';
                            break;
                    }

                    $status = '<span class="label '.$class.'">'.trans('reclamation.status_'.$reclamationProduct->status).'</span>';
                    $name = '?';
                    $id = 0;

                    if($reclamationProduct->product){
                        if($reclamationProduct->product->orderProduct){
                            if($reclamationProduct->product->orderProduct->product){
                                $id = $reclamationProduct->product->orderProduct->product->id;
                                $name = \App\Services\Product\Product::getName($reclamationProduct->product->orderProduct->product);

                            }
                        }
                    }

                    $products[] = [
                        'product_id'	=> $id,
                        'name'			=> $name,
                        'quantity'		=> $reclamationProduct->quantity,
                        'status'		=> $status,
                        'note'		=> $reclamationProduct->note,
                    ];

                    /*if($reclamationProduct->product){
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
                    }*/

                }
                return view('reclamation.include.products',compact(['products']))->render();
            })
			->addColumn('user',function (Reclamation $reclamation){
				return $reclamation->user->name;
			})
			->rawColumns(['products','status_html'])
			->toJson();
	}

	public function create(){
		SEOTools::setTitle(trans('reclamation.page_create'));
		return view('reclamation.create');
	}

	public function store(Request $request)
	{
		$reclamation = Reclamation::create([
			'ttn'						=> $request->ttn,
			'author'					=> auth()->user()->id,
		]);

		foreach ($request->product_id as $key => $product){
            ReclamationProduct::create([
                'reclamation_id'	=> $reclamation->id,
                'implementation_product_id'	=> $product,
                'quantity'					=> $request->quantity_product[$key],
                'note'						=> $request->comment[$key],
            ]);
        }


		return redirect()->route('reclamations');
	}
}
