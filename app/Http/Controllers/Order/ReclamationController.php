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
            ->addColumn('status_html',function (Reclamation $reclamation){
                $class = '';
                switch ($reclamation->status){
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

               return '<span class="label '.$class.'">'.trans('reclamation.status_'.$reclamation->status).'</span>';

            })
            ->addColumn('products',function (Reclamation $reclamation){
                $products = [];
                foreach ($reclamation->products as $reclamationProduct){
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
                        'note'		=> $reclamationProduct->note,
                    ];

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
