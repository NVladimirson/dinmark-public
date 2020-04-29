<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 28.04.2020
 * Time: 10:28
 */

namespace App\Services;


use Carbon\Carbon;

class TimeServices
{
	protected $months = [
		1 => 'січня',
		2 => 'лютого',
		3 => 'березня',
		4 => 'квітня',
		5 => 'травня',
		6 => 'червня',
		7 => 'липня',
		8 => 'серпня',
		9 => 'вересня',
		10 => 'жовтня',
		11 => 'листопада',
		12 => 'грудня',
	];

	public static function getFromTime($unixtime = null){
		$instance =  static::getInstance();
		$time = null;
		if($unixtime){
			$time = Carbon::parse($unixtime);
		}else{
			$time = Carbon::now();
		}


		return $time->day.' '.$instance->months[$time->month].' '.$time->year;
	}

	private static $instance;
	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	private function __construct(){}
	private function __clone(){}
	private function __wakeup(){}
}