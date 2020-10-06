<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductOptions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $language;

    public function __construct($language)
    {
        $this->language = $language;
        info('LANGUAGE '.$this->language);
    }

    public function handle()
    {
        info('Job ProductOptions fired at: '.Carbon::now()."\n");
        $available_languages = ['uk','ru'];
        info('LANGUAGE2 '.$this->language);
        $language = $this->language;
        if(in_array($language,$available_languages)){
            $options = DB::select('
              SELECT s_shopshowcase_products.`id` as `product_id` ,s_shopshowcase_product_options.id as `option_id`,
              s_shopshowcase_options_name.id as `name_id`, s_shopshowcase_options_name.`name`
              FROM s_shopshowcase_products
              LEFT JOIN s_shopshowcase_product_options on s_shopshowcase_product_options.`product` = s_shopshowcase_products.`id`
              LEFT JOIN s_shopshowcase_options_name on s_shopshowcase_product_options.option = s_shopshowcase_options_name.`option`
              WHERE s_shopshowcase_options_name.`name`!=\'null\' AND s_shopshowcase_options_name.`language` = \''.$language.'\' 
            ');

            $vals = DB::select('
              SELECT s_shopshowcase_products.`id` as `product_id`,s_shopshowcase_product_options.id as `option_id`,
              s_shopshowcase_options_name.id as `value_id`, s_shopshowcase_options_name.`name` as `value`
              FROM s_shopshowcase_products
              LEFT JOIN s_shopshowcase_product_options on s_shopshowcase_product_options.`product` = s_shopshowcase_products.`id`
              LEFT JOIN s_shopshowcase_options_name on s_shopshowcase_product_options.value = s_shopshowcase_options_name.`option`
              WHERE s_shopshowcase_options_name.`name`!=\'null\' AND s_shopshowcase_options_name.`language` = \''.$language.'\'
            ');


            $options = json_decode(json_encode($options),true);

            $vals = json_decode(json_encode($vals),true);

            $option_map = array();
            foreach ($options as $no => $option){
                //dd($option);
                $option_map[$option['option_id']] = $option;
            }


            foreach ($vals as $no => $value){
                if(isset($option_map[$value['option_id']])){
                    $option_map[$value['option_id']]['value'] = $value['value'];
                    $option_map[$value['option_id']]['value_id'] = $value['value_id'];
                }
            }



            foreach ($option_map as $option){
                if(!isset($option['value'])){
                    continue;
                }
                $filter_map[$option['name']][$option['value']][$option['product_id']] = $option;
            }

            \Cache::put('filters_'.$language, $filter_map);
            info('Job ProductOptions have added language '.$language.' object (filters_'.$language.') 
            in Cache at: '.Carbon::now()."\n");
        }
        else{
            info('Job ProductOptions doesn\'t know language: '.$language.'');
        }

    }
}
