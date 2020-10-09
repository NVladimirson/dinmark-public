<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 30.03.2020
 * Time: 17:27
 */

namespace App\Services\Product;


use App\Models\Product\ProductCategory;
use App\Models\WlImage;
use App\Models\Content;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use LaravelLocalization;
use Illuminate\Support\Arr;

class CategoryServices
{
	private  $lang;

	public static function getName($id){
		$instance =  static::getInstance();

		$content = Content::where('content',-$id)
			->where('language',$instance->lang)
			->where('alias', 8)
			->first();

		return $content?$content->name:'';
	}

	public static function getNames($parent){
		$instance =  static::getInstance();

		$categories_ids = ProductCategory::where([
			['parent',$parent],
			['active',1],
		])->pluck('id')->toArray();
		foreach ($categories_ids as $key => $categories_id){
			$categories_ids[$key] = '-'.$categories_id;
		}

		return Content::whereIn('content',$categories_ids)
			->where('language',$instance->lang)
			->where('alias', 8)
			->get();
	}

	public static function getFilters($options = 0){
        $language =  static::getInstance()->lang;
        if($language == 'ru'){
            $object = Cache::get('filters_ru');
        }
        else{
            $object = Cache::get('filters_uk');
        }

        if(!$options){
            return $object;
        }

    }
	public static function getTermsForSelect(){
		$instance =  static::getInstance();
		$storages = \DB::select('SELECT DISTINCT ss.id,ss.term FROM s_shopstorage ss WHERE ss.term != ""');
		if($instance->lang == 'uk'){
			foreach ($storages as $key => $term) {
				if(Str::length($term->term) == 1){
						if(intval($term->term) == 1){
							$days =  ' доба';
						}
						else if((intval($term->term) <= 4) && intval($term->term) >= 2){
							$days =  ' доби';
						}
						else{
							$days =  ' діб';
						}
				}
				else{
					$tens = substr($term->term,-2);
					$ones = substr($term->term,-1);
					if($tens == 1){
						$days =  ' діб';
					}
					else{
						if(intval($ones) == 1){
							$days =  ' доба';
						}
						else if((intval($term->term) <= 4) && intval($term->term) >= 2){
							$days =  ' доби';
						}
						else{
							$days =  ' діб';
						}
					}
				}
				$terms[$term->term] = $term->term . $days;
			}
		}else{
			foreach ($storages as $key => $term) {
				if(Str::length($term->term) == 1){
						if(intval($term->term) == 1){
							$days =  ' сутки';
						}
						else{
							$days =  ' суток';
						}
				}
				else{
					$tens = substr($term->term,-2);
					$ones = substr($term->term,-1);

					if($tens == 1){
						$days =  ' суток';
					}
					else{
						if(intval($ones) == 1){
							$days =  ' сутки';
						}
						else{
							$days =  ' суток';
						}
					}
				}
				$terms[$term->term] = $term->term . $days;
			}
		}
		return $terms;
	}

	public static function getAllChildrenCategoriesID($parent){
		$instance =  static::getInstance();
		$category_ids = [];
		return $instance->getChildren($category_ids,$parent);
	}

	public static function getChildrenRecursive($parent, $category_ids = []){
		$instance =  static::getInstance();
		$categories = ProductCategory::where([
			['parent',$parent],
			['active',1],
		])->pluck('id')->toArray();

		foreach ($categories as $key => $category) {

		}

		$category_ids = array_merge($category_ids,$categories);
		foreach ($categories as $category){
			$category_ids = $instance->getChildren($category_ids,$category);
		}

		return $category_ids;
	}

	public static function getChildren($category_ids,$parent){
		$instance =  static::getInstance();
		$categories = ProductCategory::where([
			['parent',$parent],
			['active',1],
		])->pluck('id')->toArray();
		$category_ids = array_merge($category_ids,$categories);
		foreach ($categories as $category){
			$category_ids = $instance->getChildren($category_ids,$category);
		}
		return $category_ids;
	}


    public static function getNodeAjax($id = 0){
        $instance =  static::getInstance();
        $categories = CategoryServices::getChilds($id);
        $result = array();
        foreach ($categories as $key => $value) {
            $data = [
                'text' => CategoryServices::getName($key),
                'position' => $value['position'],
                'id' => $key
            ];
            if(CategoryServices::anyChilds($key)){
                $data['children'] = ['Загрузка'];
            }
            $result[$key] = $data;
        }

        return collect($result)->sortBy('position')->toArray();
    }

	private static function getChilds($id){

	  $childs = ProductCategory::where([['parent', $id],['active',1]])->get()->keyBy('id')->toArray();
	  return $childs;
	}

	private static function anyChilds($id){
		$categories = ProductCategory::where([
					['parent',$id],
					['active',1],
				])->pluck('id')->toArray();
		$anychilds = (!empty($categories));
		return $anychilds;
	}

	private static function toJstreeJson($node){
		$instance =  static::getInstance();
		$response = '';
		foreach ($node as $key => $value) {
				$response .= json_encode($value);
		}
		return $response;
	}

	public static function getBreadcrumbs($id){
		$instance =  static::getInstance();
		$breadcrumbs = [];
		return static::getParent($id,$breadcrumbs);
	}

	public static function getParent($id,$breadcrumbs){
		$instance =  static::getInstance();

		$category = ProductCategory::find($id);
		if($category->parent != 0){
			$breadcrumbs = static::getParent($category->parent,$breadcrumbs);
		}
		$breadcrumbs[] = [
			'id' => $category->id,
			'name' => $instance->getName($category->id)
		];

		return $breadcrumbs;
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
