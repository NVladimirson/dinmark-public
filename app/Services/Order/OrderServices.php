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

    public static function setAddressInfo($order,$request)
    {
        $instance =  static::getInstance();

        $order->shipping_id     = $request->shipping_id;
        $order->payment_alias   = $request->payment_id;
        $order->payment_id      = $request->payment_id;
        $order->shipping_info   = $instance->getSippingInfo($request);
        $order->save();
    }

    public static function getSippingInfo($request)
    {
        switch($request->shipping_id){
            case 1:
            case 3:
                return null;
            case 2:
                return serialize([
                    'city' => $request->city_me,
                    'address' => $request->adress_me,
                ]);
            case 4:
                if($request->np_wherhouse_curier == "#wherhouse-tab"){
                    return serialize([
                        'method' => 'warehouse',
                        'city' => $request->city_np,
                        'warehouse' => $request->warehous_np,
                    ]);
                }else{
                    return serialize([
                        'method' => 'courier',
                        'city' => $request->city_np_curier,
                        'address' => $request->adress_np_curier,
                        'house_float' => $request->house_float_np_curier,
                    ]);
                }
        }
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
