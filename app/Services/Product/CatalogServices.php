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
            switch ($days) {
                case $days > 10000:
                    $days = '>10000';
                    break;
                case $days > 5000:
                    $days = '>5000';
                    break;
                case $days > 1500:
                    $days = '>1500';
                    break;
                case $days > 500:
                    $days = '>500';
                    break;
                case $days > 150:
                    $days = '>150';
                    break;
                case $days > 50:
                    $days = '>50';
                    break;
                case $days > 10:
                    $days = '>10';
                    break;
                case $days < 10:
                    $days = '<10';
                    break;
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