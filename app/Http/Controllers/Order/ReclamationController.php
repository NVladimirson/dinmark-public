<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Order\Implementation;
use App\Models\Reclamation\Reclamation;
use App\Models\Reclamation\ReclamationProduct;
use App\Services\Order\ReclamationServices;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class ReclamationController extends Controller
{
	public function index(){
		SEOTools::setTitle(trans('reclamation.page_list'));
		return view('reclamation.index');
	}

	public function ajax(Request $request){
		$reclamations = ReclamationServices::getFilteredData($request)
			->orderBy('id','desc');

		return datatables()
			->eloquent($reclamations)
            ->addColumn('file_html', function (Reclamation $reclamation){
                if($reclamation->file){
                    return '<a href="'.$reclamation->file.'" target="_blank"><i class="fas fa-file"></i></a>';
                }
                return '';
            })
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
			->addColumn('action_buttons',function (Reclamation $reclamation){
				return view('reclamation.include.action_buttons',compact('reclamation'))->render();
			})
			->rawColumns(['products','status_html','file_html','action_buttons'])
			->toJson();
	}

    public function totalDataAjax(Request $request)
    {
        $reclamations = ReclamationServices::getFilteredData($request);
        $reclamations = $reclamations->get();

        $total = 0;
        $weight = 0;

        foreach ($reclamations as $reclamation){
            foreach ($reclamation->products as $reclamationProduct){
                if($reclamationProduct->product){
                    $total += $reclamationProduct->product->total/$reclamationProduct->product->quantity * $reclamationProduct->quantity;
                    if($reclamationProduct->product->orderProduct){
                        if($reclamationProduct->product->orderProduct->product){
                            $weight += $reclamationProduct->product->orderProduct->product->weight * $reclamationProduct->quantity/100;
                        }
                    }
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'pc'        => $reclamations->count(),
            'total'     => number_format($total,2,'.',' '),
            'weight'  => number_format($weight,2,'.',' '),
        ]);
    }

	public function create(){
		SEOTools::setTitle(trans('reclamation.page_create'));
		return view('reclamation.create');
	}

	public function createByImplementation($implementation_id){
	    $implementation = Implementation::find($implementation_id);
		SEOTools::setTitle(trans('reclamation.page_create'));
		return view('reclamation.create',compact('implementation'));
	}

	public function store(Request $request)
	{
        $validatedData = $request->validate([
            'document'			=> 'nullable|file|mimes:jpeg,png,pdf,jpg,doc,docx,xls,xlsx',
        ]);

        if(!is_array($validatedData) ){
            if($validatedData->fails()) {
                return Redirect::back()->withErrors($validatedData);
            }
        }
        $document = '';

        if($request->hasFile('document')){
            $document = Uuid::uuid4().'.'.$request->file('document')->getClientOriginalExtension();
            Storage::disk('main_site')->putFileAs('documents/'.session('current_company_id').'/different', $request->file('document'), $document);
        }

		$reclamation = Reclamation::create([
			'ttn'		=> $request->ttn,
			'author'	=> auth()->user()->id,
            //'file'      => env('DINMARK_URL').'documents/'.session('current_company_id').'/different/'.$document
			'file'      => 'https://dinmark.com.ua/documents/'.session('current_company_id').'/different/'.$document
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
