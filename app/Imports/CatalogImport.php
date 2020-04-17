<?php

namespace App\Imports;

use App\Models\Product\CompanyProductArticle;
use App\Models\Product\Product;
use App\Models\Wishlist\Like;
use App\Models\Wishlist\LikeGroup;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class CatalogImport implements OnEachRow
{
	protected $holdingId;
	protected $group;
	public function __construct()
	{
		$this->holdingId = auth()->user()->getCompany->holding;
		$this->group = LikeGroup::find(session('current_catalog'));
	}

	/**
    * @param Collection $collection
    */
	public function onRow(Row $row)
	{
		$rowIndex = $row->getIndex();
		$row      = $row->toArray();

		if($rowIndex > 1 && $row[0]){

			$product = Product::where('article_show',trim($row[0]))->first();

			if($product){
				Like::updateOrCreate([
					'user' => $this->group->user_id,
					'alias' => 8,
					'content' => $product->id,
					'group_id' => $this->group->group_id,
				],[
					'status' => 1
				]);

				if($row[1]){
					$article = CompanyProductArticle::where([
						['holding_id', $this->holdingId],
						['article', $row[1]]
					])->first();

					if($article){
					}else{
						CompanyProductArticle::updateOrCreate([
							'product_id' => $product->id,
							'holding_id' => $this->holdingId,
						],[
							'article' => $row[1]
						]);

						return 'ok';
					}
				}
			}
		}
    }
}
