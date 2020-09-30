<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 30.03.2020
 * Time: 17:27
 */

namespace App\Services\Product;


use App\Models\Content;
use App\Models\Product\Currency;
use App\Models\Company\Company;
use App\Models\Product\GetPrice;
use App\Models\WlFile;
use App\Models\WlImage;
use App\Models\WlVideo;
use Carbon\Carbon;
use LaravelLocalization;

class Product
{
	private  $currencies;
	private  $lang;
	private  $company;

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

	public static function getImagePathThumb($product)
	{
		$ids[] = -$product->group;
		$photo = WlImage::where([
			['alias',$product->wl_alias],
			['content',$ids],
			['position',1],
		])->first();
		$src = env('DINMARK_URL').'images/dinmark_nophoto.jpg';
		if($photo){
			$src = 	env('DINMARK_URL').'images/shop/-'.$product->group.'/thumbnail_'.$photo->file_name;
		}

		return $src;
	}

	public static function getImagePathThumbs($product)
	{
		$ids[] = -$product->group;
		$photos = WlImage::where([
			['alias',$product->wl_alias],
			['content',$ids],
			['position','<>',1],
		])->get();

		$productPhotos = [];
		foreach ($photos as $photo){
            $productPhotos[] = 	env('DINMARK_URL').'images/shop/-'.$product->group.'/thumbnail_'.$photo->file_name;
        }

		return $productPhotos;
	}

	public static function getName($product, $lang = null){
		$instance =  static::getInstance();
		if(empty($lang)){
			$lang = $instance->lang;
		}
		$content = $product->content->where('language',$lang)->where('alias',$product->wl_alias)->first();
		$productName = $content?$content->name:'';
		return $productName;
	}

	public static function getText($product, $lang = null){
		$instance =  static::getInstance();
		if(empty($lang)){
			$lang = $instance->lang;
		}
        $content = Content::where([
            ['alias',$product->wl_alias],
            ['language',$lang],
            ['content',-$product->group],
        ])->first();
		$productText = $content?$content->text:'';
		return $productText;
	}

    public static function getVideo($product)
    {
        $content = WlVideo::where([
            ['alias',$product->wl_alias],
            ['content',-$product->group],
        ])->first();
        $productVideo = $content?$content->link:'';
        return $productVideo;

	}

    public static function getPDF($product)
    {
        $content = WlFile::where([
            ['alias',$product->wl_alias],
            ['content',-$product->group],
            ['extension','pdf'],
        ])->first();
        $productPDF = null;
        if($content){
            $productPDF = 'https://dinmark.com.ua/files/shop/-'.$product->group.'/'.$content->name;
        }
        return $productPDF;
	}

	public static function getBasePrice($product){
		$instance =  static::getInstance();

        $storage = $product->storages->firstWhere('is_main',1);

        $price = 0;
        if($storage){
            $currency = $instance->currencies->firstWhere('code',$storage->currency);
            $price = $storage->price;

            if($currency){
                $price *= $currency->currency;
            }
        }
		$price *= 1.2 * 2;

		return number_format($price,2,'.',' ');
	}

	public static function getPrice($product,$storage_id = null){
		$instance =  static::getInstance();
		$price = $instance->calcPrice($product,$storage_id );
		return number_format($price,2,'.',' ');
	}
	public static function calcPrice($product,$storage_id = null){
		$instance =  static::getInstance();

		$price = $instance->calcPriceWithoutPDV($product,$storage_id) * 1.2;

		return $price;
	}

	public static function getPriceLimit1($product){
		$instance =  static::getInstance();
		$price = $instance->calcPrice($product) * 0.97;
		return $price;
	}

	public static function getPriceLimit2($product){
		$instance =  static::getInstance();
		$price = $instance->calcPrice($product) * 0.93;
		return $price;
	}

	public static function calcPriceWithoutPDV($product,$storage_id = null){
		$instance =  static::getInstance();

        $company = $instance->company;

        $storage = null;
        if(isset($storage_id)){
            $storage = $product->storages->firstWhere('id',$storage_id);
        }else{
            $storage = $product->storages->firstWhere('is_main',1);
        }

        $price = 0;
        if($storage){
            $currency = $instance->currencies->firstWhere('code',$storage->currency);
            $price = $storage->price;

            if($currency){
                $price *= $currency->currency;
            }
        }

        $price *= 0.98; //Знижка 2% на ціни кабінету
		$priceCoef = auth()->user()->price->price;
		if($company){
			$priceCoef = $company->getPrice->price;
		}
		if($priceCoef > 0){
			$price *= $priceCoef;
		}

		$price = ((int)($price*100)/100);

		return $price;
	}

	public static function getUserPrice($product){
		$instance =  static::getInstance();
		$currency = $instance->currencies->firstWhere('code',$product->currency);
		$price = $product->price;
		if($currency){
			$price *= $currency->currency;
		}

		$priceCoef = auth()->user()->price->price;
		if($priceCoef > 0){
			$price *= $priceCoef;
		}

		return number_format($price,2,'.',' ');
	}

	public static function getPriceWithCoef($product, $coef){
		$instance =  static::getInstance();
		$price = $instance->calcPrice($product);
		return number_format($price * $coef,2,'.',' ');
	}

    public static function hasAmount($storages, $storage_id = null)
    {
        if($storages){
            $storage = null;
            if($storage_id){
                $storage = $storages->firstWhere('storage_id',$storage_id);
            }else{
                $storage = $storages->firstWhere('is_main',1);
            }

            if($storage){
                if($storage->amount > 0){
                    return true;
                }
            }
        }

        return false;
	}

    public static function getPriceRequest($id, $amount, $data = null)
    {
        $instance =  static::getInstance();
        $product = \App\Models\Product\Product::find($id);
        if(empty($data)){
            $data = [
                'name' => auth()->user()->name,
                'phone' => (auth()->user()->info->firstWhere('field','phone'))? auth()->user()->info->firstWhere('field','phone')->value : auth()->user()->email,
                'comment' => '',
            ];
        }
        $data = array_merge($data,[
            'product' => $product->article_show.' '.$instance->getName($product),
            'amount' => $amount,
            'date_add' =>Carbon::now()->timestamp,
            'language' =>LaravelLocalization::getCurrentLocale() == 'ua'?'uk':LaravelLocalization::getCurrentLocale(),
            'new' => 1,

        ]);
        GetPrice::create($data);
	}


	public static function getStringPrice($num){
		$instance =  static::getInstance();

		$nul='нуль';
		$ten=array(
			array('','один', 'два', 'три', 'чотири', "п'ять", "шість", "сім", "вісім", "дев'ять"),
			array('','одна', 'дві', 'три', 'чотири', "п'ять", "шість", "сім", 'вісім', "дев'ять"),
		);
		$a20=array('десять', 'одинадцять', 'дванадцять', 'тринадцять', 'чотирнадцять', "п'ятнадцять", 'шістнадцять', 'сімнадцять', 'вісімнадцять', "дев'ятнадцять");
		$tens=array(2=>'двадцять', 'тридцять', 'сорок', "п'ятдесят", 'шістдесят', 'сімдесят', 'вісімдесят', "дев'яносто");
		$hundred=array('','сто', 'двісті', 'триста', "чотириста", "п'ятсот", "шістсот", "сімсот", 'вісімсот', "дев'ятсот");
		$unit=array( // Units
			array('копійка' ,'копійки' ,'копійок',	 1),
			array('гривня'   ,'гривнi'   ,'гривень'    ,0),
			array('тисяча'  ,'тисячі'  ,'тисяч'     ,1),
			array('мільйон' ,'мільйони','мільйонів' ,0),
			array('мільярд','мільярди','мільярдів',0),
		);
		//
		list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
		$out = array();
		if (intval($rub)>0) {
			foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
				if (!intval($v)) continue;
				$uk = sizeof($unit)-$uk-1; // unit key
				$gender = $unit[$uk][3];
				list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
				// mega-logic
				$out[] = $hundred[$i1]; # 1xx-9xx
				if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
				else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
				// units without rub & kop
				if ($uk>1) $out[]= $instance->morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
			} //foreach
		}
		else $out[] = $nul;
		$out[] = $instance->morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
		$out[] = $kop.' '.$instance->morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
		return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
	}

	/**
	 * Склоняем словоформу
	 * @ author runcore
	 */
	public static function morph($n, $f1, $f2, $f5) {
		$n = abs(intval($n)) % 100;
		if ($n>10 && $n<20) return $f5;
		$n = $n % 10;
		if ($n>1 && $n<5) return $f2;
		if ($n==1) return $f1;
		return $f5;
	}

	public static function getIdsSearch($search){
		if(mb_strlen($search)>1){
			$instance =  static::getInstance();
			$ids = Content::where([
				['language',$instance->lang],
				['alias', 8],
				['name', 'like',"%" . $search . "%"]
			])->pluck('content')->toArray();

			return $ids;
		}else
		{
			return null;
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
		$this->currencies = Currency::all();
		$this->lang = LaravelLocalization::getCurrentLocale() == 'ua'?'uk':LaravelLocalization::getCurrentLocale();
		$this->company = null;
		if(session()->has('current_company_id')){
			$this->company = Company::find(session('current_company_id'));
		}
	}
	private function __clone(){}
	private function __wakeup(){}
}
