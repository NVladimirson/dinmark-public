<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 30.03.2020
 * Time: 17:27
 */

namespace App\Services\Product;


use App\Models\Product\Currency;
use App\Models\WlImage;
use LaravelLocalization;

class Product
{
	private  $currencies;
	private  $lang;

	public static function getImagePath($product)
	{
		$ids[] = -$product->group;
		$photo = WlImage::where([
			['alias',$product->wl_alias],
			['content',$ids],
			['position',1],
		])->first();
		$src = env('DINMARK_URL').'images/dinmark_nophoto.jpg';
		if($photo){
			$src = 	env('DINMARK_URL').'images/shop/-'.$product->group.'/group_'.$photo->file_name;
		}

		return $src;
	}

	public static function getName($product){
		$instance =  static::getInstance();
		$content = $product->content->where('language',$instance->lang)->where('alias',$product->wl_alias)->first();
		$productName = $content?$content->name:'';
		return $productName;
	}

	public static function getPrice($product){
		$instance =  static::getInstance();
		$currency = $instance->currencies->firstWhere('code',$product->currency);
		$price = $product->price;
		if($currency){
			$price *= $currency->currency;
		}

		$priceCoef = auth()->user()->price->price;
		if(auth()->user()->getCompany){
			$priceCoef = auth()->user()->getCompany->getPrice->price;
		}
		if($priceCoef > 0){
			$price *= $priceCoef;
		}
		return number_format($price,2,'.',' ');
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
		$this->currencies = Currency::all();
		$this->lang = LaravelLocalization::getCurrentLocale() == 'ua'?'uk':LaravelLocalization::getCurrentLocale();
	}
	private function __clone(){}
	private function __wakeup(){}
}