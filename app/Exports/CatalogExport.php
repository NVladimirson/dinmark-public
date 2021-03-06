<?php

namespace App\Exports;

use App\Models\Product\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Services\Product\Product as ProductServices;

class CatalogExport implements  WithTitle, FromQuery, WithMapping,WithHeadings
{
    use Exportable;
    protected $group;
    protected $holdingId;
    protected $coef;
    protected $heads;

    public function __construct($group)
    {
        $this->group = $group;
        $this->holdingId = auth()->user()->getCompany->holding;

        $this->coef = 1;
        if($group->price){
            $this->coef = $group->price->koef;
        }
        $this->headGenerator();
    }


    public function title(): string
    {
        return 'Price';
    }


    public function query()
    {
        $group = $this->group;
        $products = Product::with(['storages','holdingArticles','content'])->whereHas('likes',function($likes) use ($group){
            $likes->where([
                ['alias',8],
                ['group_id',$group->group_id],
                ['user',$group->user_id],
            ]);
        });

        return $products;
    }
    public function map($product): array
    {
        $row = [];
        $row[] = ProductServices::getName($product);
        $row[] = $product->article_show;

        $article = '';

        if($product->holdingArticles->firstWhere('holding_id',$this->holdingId)){
            $article = $product->holdingArticles->firstWhere('holding_id',$this->holdingId)->article;
        }

        $row[] = $article;

        $price = number_format(0,2,'.',' ');
        if(ProductServices::hasAmount($product->storages))
        {
            $price = ProductServices::getPrice($product);
        }

        $row[] = $price;

        $price = number_format(0,2,'.',' ');
        if(ProductServices::hasAmount($product->storages)){
            $price = ProductServices::getPriceWithCoef($product,$this->coef);
        }

        $row[] = $price;

        $value = trans('product.storage_empty');
        if($product->storages){
            $storage = $product->storages->firstWhere('is_main',1);
            if($storage){
                $value = $storage->amount.' / '.$storage->storage->term;
            }
        }
        $row[] = $value;

        return $row;
    }
    public function headings(): array
    {
        return $this->heads;
    }

    protected function headGenerator(){

        $this->heads = [];
        $this->heads[] = '??????????';
        $this->heads[] = '??????????????';
        $this->heads[] = '?????? ??????????????';
        $this->heads[] = '???????? (100????)';
        $this->heads[] = '???????? ?? ???????????????? x '.$this->coef;
        $this->heads[] = '??????????????/???????????? ????????????????';
    }
}
