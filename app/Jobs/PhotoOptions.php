<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PhotoOptions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        info('Job PhotoOptions fired at: '.Carbon::now()."\n");

        $s_shopshowcase_options_map = DB::select('
              SELECT s_shopshowcase_options.`id`,s_shopshowcase_options.`group`,s_shopshowcase_options.`alias`,s_shopshowcase_options.`photo`
              FROM s_shopshowcase_options
        ');

        $s_shopshowcase_options_map = (collect(json_decode(json_encode($s_shopshowcase_options_map),true))->keyBy('id'))->toArray();

        foreach ($s_shopshowcase_options_map as $id => $data){
            unset($s_shopshowcase_options_map[$id]['id']);
            if(!$data['alias']){
                $s_shopshowcase_options_map[$id]['alias'] = $s_shopshowcase_options_map[-($data['group'])]['alias'];
            }
            unset($s_shopshowcase_options_map[$id]['group']);
        }

        \Cache::put('photo_options',$s_shopshowcase_options_map);

        info('Job PhotoOptions fired succesfully');

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
