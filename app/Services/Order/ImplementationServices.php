<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 30.03.2020
 * Time: 17:27
 */

namespace App\Services\Order;

use App\Models\Order\Implementation;

class ImplementationServices
{
    public static function getByCompany()
    {
        $implementations = Implementation::with(['products.orderProduct.product.content','products.orderProduct.getCart'])
            ->where(function ($imp){
                $imp->whereHas('sender',function ($users){
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
                    });
            });

        return $implementations;
    }

    public static function getFilteredData($request){
        $implementations = Implementation::with(['products.orderProduct.product.content','products.orderProduct.getCart'])
            ->where(function ($imp){
                $imp->whereHas('sender',function ($users){
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
                    });
            });

        if($request->has('date_from')){
            $implementations->where('date_add','>=',$request->date_from);
        }

        if($request->has('date_to')){
            $implementations->where('date_add','<=',$request->date_to);
        }

        if($request->has('sender_id')){
            $implementations->where('sender_id',$request->sender_id);
        }

        if($request->has('customer_id')){
            $implementations->where('customer_id',$request->customer_id);
        }

        return $implementations;
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
