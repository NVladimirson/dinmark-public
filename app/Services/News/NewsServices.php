<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 13.05.2020
 * Time: 11:38
 */

namespace App\Services\News;

use App\Models\Content;
use App\Models\WlImage;
use LaravelLocalization;

class NewsServices
{
	private  $lang;

	public static function getContent($news, $lang = null){
		$instance =  static::getInstance();
		if(empty($lang)){
			$lang = $instance->lang;
		}
		$content = $news->content->where('language',$lang)->where('alias',$news->wl_alias)->first();

		return $content;
	}

	public static function getImagePath($news)
	{
		$photo = WlImage::where([
			['alias',$news->wl_alias],
			['content',$news->id],
			['position',1],
		])->first();
		$src = env('DINMARK_URL').'images/dinmark_nophoto.jpg';
		if($photo){
			$src = 	env('DINMARK_URL').'images/'.($photo->alias == 13? 'blog' : 'industry').'/'.$news->id.'/'.$photo->file_name;
		}

		return $src;
	}

	public static function getImage($news, $lang = null){
		$instance =  static::getInstance();
		if(empty($lang)){
			$lang = $instance->lang;
		}
		$content = $news->content->where('language',$lang)->where('alias',$news->wl_alias)->first();

		return $content;
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
		$this->lang = LaravelLocalization::getCurrentLocale() == 'ua'?'uk':LaravelLocalization::getCurrentLocale();
	}
	private function __clone(){}
	private function __wakeup(){}
}