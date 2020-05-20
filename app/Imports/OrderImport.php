<?php

namespace App\Imports;

use App\Models\Order\OrderProduct;
use App\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class OrderImport implements OnEachRow
{
	protected $order;
	protected $holdingId;

	public function __construct($order)
	{
		$this->holdingId = auth()->user()->getCompany->holding;
		$this->order = $order;
		session(['not_founds' => []]);
		session(['not_available' => []]);
	}

    /**
    * @param Collection $collection
    */
    public function onRow(Row $row)
    {
		$rowIndex = $row->getIndex();
		$row      = $row->toArray();

		if($rowIndex > 1 && ($row[0] || $row[1]) && $row[2]){
			$product = null;
			if($row[0]){
				$product = Product::with(['storages.storage'])
					->where('article_show',trim($row[0]))
					->first();
			}elseif( $row[1] ){
				$holdingId = $this->holdingId;
				$product = Product::with(['storages.storage'])
					->whereHas('holdingArticles', function($articles) use ($row,$holdingId){
						$articles->where([
							['holding_id', $holdingId],
							['article', $row[1]]
						]);
					})
					->first();
			}

			if(isset($product)){
				if($this->hasQuantity($row[2]))
				{
					if($product->storages){
						$storage = $product->storages->firstWhere('is_main',1);
						if($storage){
							if($storage->amount >= $row[2]){
								$quantity = $this->getQuantity($storage,$row[2]);


								$koef = 1;

								if($storage->limit_2 > 0 && $quantity >= $storage->limit_2 ){
									$koef = 0.93;
								}elseif($storage->limit_1 > 0 && $quantity >= $storage->limit_1 ){
									$koef = 0.97;
								}

								$price = abs(\App\Services\Product\Product::calcPrice($product)/(float)100) * $koef;
								$total = round($price * $quantity,2);

								$this->order->total += $total;
								$this->order->save();

								OrderProduct::create([
									'cart' => $this->order->id,
									'user' => auth()->user()->id,
									'active' => 1,
									'product_alias' => $product->wl_alias,
									'product_id' => $product->id,
									'storage_alias' => $storage->storage->id,
									'price' => $price,
									'price_in' => $product->price,
									'quantity' => $quantity,
									'quantity_wont' => $quantity,
									'date' => Carbon::now()->timestamp,
								]);

								return true;
							}
						}
					}
				}
					$notAvailable = session('not_available');
					$notAvailable[] = $row[0] ?? $row[1];
					session(['not_available' => $notAvailable]);

			}else{
				$notFound = session('not_founds');
				$notFound[] = $row[0] ?? $row[1];
				session(['not_founds' => $notFound]);
			}

		}
    }

    protected function hasQuantity($cell){
    	if(isset($cell)){
			if(is_int($cell)){
				return true;
			}
		}
		return false;
	}

	protected function getQuantity($storage, $quantity){
    	$eps = ceil  ($quantity/$storage->package);
		return $storage->package * $eps;
	}


}
