<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\Reclamation;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class ReclamationController extends Controller
{
	public function index(){
		SEOTools::setTitle(trans('reclamation.page_list'));
		return view('reclamation.index');
	}

	public function ajax(Request $request){
		$reclamations = Reclamation::with(['user','product.orderProduct.product'])
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
				return $reclamation->product->implementation_id;
			})
			->addColumn('product',function (Reclamation $reclamation){
				return \App\Services\Product\Product::getName($reclamation->product->orderProduct->product);
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
			->addColumn('user',function (Reclamation $reclamation){
				return $reclamation->user->name;
			})
			->rawColumns(['product','status_html'])
			->toJson();
	}

	public function create(){
		SEOTools::setTitle(trans('reclamation.page_create'));
		return view('reclamation.create');
	}

	public function store(Request $request)
	{
		Reclamation::create([
			'implementation_product_id'	=> $request->product_id,
			'quantity'					=> $request->quantity_product,
			'note'						=> $request->comment,
			'ttn'						=> $request->ttn,
			'author'					=> auth()->user()->id,
		]);

		return redirect()->route('reclamations');
	}
}
