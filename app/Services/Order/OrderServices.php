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
    protected $status_classes = [
        1 => 'lime',
        2 => 'green',
        3 => 'primary',
        4 => 'warning',
        5 => 'success',
        6 => 'default',
        7 => 'danger',
        8 => 'yellow',
    ];
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

    public static function getFilteredData($request = null){
        $orders = Order::with(['payments'])->whereHas('getUser', function ($users){
            $users->whereHas('getCompany',function ($companies){
                $companies->where([
                    ['holding', auth()->user()->getCompany->holding],
                    ['holding', '<>', 0],
                ])->orWhere([
                    ['id', auth()->user()->getCompany->id],
                ]);
            });
        });

        if($request->has('tab')){
            if($request->tab == 'order'){
                $orders->where('status','<=',5);
            }
            if($request->tab == 'archive'){
                $orders->where([
                    ['status','>=',6],
                    ['status','<=',7],
                ]);
            }
            if($request->tab == 'request'){
                $orders->where('status',8);
            }
        }

        if($request->has('date_from')){
            $orders->where('date_add','>=',$request->date_from);
        }


        if($request->has('date_to')){
            $orders->where('date_add','<=',$request->date_to);
        }

        if($request->has('status_id')){
            $orders->where('status',$request->status_id);
        }

        if($request->has('payment')){
            if($request->payment == 'none'){
                $orders->doesntHave('payments');
            }elseif ($request->payment == 'partial'){
                $orders->has('payments')
                    ->whereRaw(' s_cart.total > ( 
                              SELECT SUM( b2b_payments.payed ) 
                              FROM b2b_payments
                              WHERE b2b_payments.cart_id = s_cart.id )');
            }elseif ($request->payment == 'success'){
                $orders->has('payments')
                    ->whereRaw(' s_cart.total <= ( 
                              SELECT SUM( b2b_payments.payed ) 
                              FROM b2b_payments
                              WHERE b2b_payments.cart_id = s_cart.id )');
            }
        }

        if($request->has('sender_id')){
            $orders->where('sender_id',$request->sender_id);
        }

        if($request->has('customer_id')){
            $orders->where('customer_id',$request->customer_id);
        }

        return $orders;
    }

    public static function getStatusClass($status_id)
    {
        $instance =  static::getInstance();
        return $instance->status_classes[$status_id];
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
