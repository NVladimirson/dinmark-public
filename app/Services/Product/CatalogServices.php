<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 30.03.2020
 * Time: 17:27
 */

namespace App\Services\Product;

use App\Models\Wishlist\LikeGroup;

class CatalogServices
{

	public static function getByCompany(){
		if(auth()->user()->wishlists->count() == 0){
			$likeGroup = LikeGroup::create([
				'name' => trans('wishlist.name_standart').auth()->user()->name,
				'is_main' => 1,
				'user_id' => auth()->user()->id,
				'group_id' => 0
			]);
			session(['current_catalog' => $likeGroup->id]);
		}
		$wishlists = LikeGroup::whereHas('user',function ($users){
			$users->where('company',auth()->user()->company);
		})->get();

		return $wishlists;
	}

    public static function dayrounder($days){
        if($days) {
					if($days>10000){
						return '>10000';
					}
					else if($days>5000){
						return '>5000';
					}
					else if($days>1500){
						return '>1500';
					}
					else if($days>500){
						return '>500';
					}
					else if($days>150){
						return '>150';
					}
					else if($days>50){
						return '>50';
					}
					else if($days>10){
						return '>10';
					}
					else if($days<10){
						return '<10';
					}
        }
        return $days;
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
