<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 30.03.2020
 * Time: 17:27
 */

namespace App\Services\Order;

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