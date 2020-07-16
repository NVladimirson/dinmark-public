<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 30.03.2020
 * Time: 17:27
 */

namespace App\Services\Order;

use App\Models\Company\Client;
use App\Models\Order\Order;

class OrderServices
{

	public static function getByCompany(){
		$orders = Order::whereHas('getUser', function ($users){
				$users->where('company',auth()->user()->company);
			})
			->where('status',8)
			->get();

		return $orders;
	}

	public static function calcTotal($order){
	    $total = 0;

        foreach ($order->products as $orderProduct){
            $koef = 1;
            if($orderProduct->product->storages){
                $storage = $orderProduct->product->storages->firstWhere('storage_id',$orderProduct->storage_alias);
                if($storage->limit_2 > 0 && $orderProduct->quantity >= $storage->limit_2 ){
                    $koef = 0.93;
                }elseif($storage->limit_1 > 0 && $orderProduct->quantity >= $storage->limit_1 ){
                    $koef = 0.97;
                }
                $orderProduct->price = abs(\App\Services\Product\Product::calcPrice($orderProduct->product,$storage->id)/(float)100) * $koef;
                $orderProduct->price_in = $storage->price;
                $orderProduct->save();
                $total += round($orderProduct->price*$orderProduct->quantity, 2);
            }
        }
        $order->total = $total;
        $order->save();
    }

    public static function setClientInfo($order,$client_id)
    {
        $client = Client::find($client_id);
        $order->shipping_info = $client->name.','.$client->company_name.','.$client->phone.','.$client->address;
        $order->save();
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
