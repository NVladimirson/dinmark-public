<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 30.03.2020
 * Time: 17:27
 */

namespace App\Services\Order;

use App\Models\Reclamation\Reclamation;

class ReclamationServices
{

    public static function getFilteredData($request){
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
            });

        return $reclamations;
    }

    private static $instance;
	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	private function __construct(){
	}
	private function __clone(){}
	private function __wakeup(){}
}
