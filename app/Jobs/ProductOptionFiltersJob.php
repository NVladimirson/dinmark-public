<?php

namespace App\Jobs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductOptionFiltersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $optionfilters = json_decode(json_encode(DB::select('
          SELECT s_shopshowcase_options_name.`id`,s_shopshowcase_options_name.`option`,s_shopshowcase_options_name.`language`,s_shopshowcase_options_name.`name`,
          s_shopshowcase_options.`alias`,s_shopshowcase_options.`photo`
          FROM s_shopshowcase_options
          LEFT JOIN s_shopshowcase_options_name on s_shopshowcase_options_name.`option` = s_shopshowcase_options.id')),true);

        $product_options = json_decode(json_encode(DB::select('
          SELECT `product`,`option`,`value`,`language`
          FROM s_shopshowcase_product_options
          ORDER BY `option`')),true);

        $optionfiltermap = Array();
        foreach ($optionfilters as $options){
            $optionfiltermap[$options['option']][$options['language']] = $options;
        }

        // dd($optionfiltermap);

        $product_option_map = Array();
        $filter_option_map = Array();
        foreach ($product_options as $no => $data){

            $filter_option_map[$data['option']]['data'] = $optionfiltermap[$data['option']];
            $filter_option_map[$data['option']]['options'][$data['value']]['products'][] = $data['product'];
            if(isset($optionfiltermap[$data['value']])){
                //148
                $filter_option_map[$data['option']]['options'][$data['value']]['data'] = $optionfiltermap[$data['value']];
                $product_option_map[$data['product']][$data['value']] = $optionfiltermap[$data['value']];
            }
            else{
                unset($filter_option_map[$data['option']]['options'][$data['value']]);
            }
        }

        $sortable = [7,9,26,28,33,266];
        //$filter_option_map = Cache::get('optionfilters');
        foreach($filter_option_map as $id => $filtername){

            if(in_array($id,$sortable)){
                $names_id_map = [];
                foreach ($filtername["options"] as $option_id => $optiondata){
                    foreach ($optiondata["data"] as $lang => $data){
                        $names_id_map[$option_id] = $data['name'];
                    }
                }
                asort ($names_id_map);

                $rekeyd_options = [];
                foreach ($names_id_map as $rekeyd_id => $name){
                    $rekeyd_options[$rekeyd_id] = $filter_option_map[$id]['options'][$rekeyd_id];
                }
                $filter_option_map[$id]['options'] = $rekeyd_options;
            }

        }

        Cache::put('optionfilters',$filter_option_map);
        Cache::put('productoptions',$product_option_map);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
