<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Product\CategoryServices;

class PurchaseController extends Controller
{
    public function index(){
        //dd(\Config::get('values.dinmarkurl'));
        $filters = CategoryServices::getOptionFilters();
        $dinmark_url = \Config::get('values.dinmarkurl');
        return view('order.purchases',compact('filters','dinmark_url'));
    }

    public function tableDataAjax(Request $request){
        return [];
    }
}
