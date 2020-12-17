<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(){
        //dd(\Config::get('values.dinmarkurl'));
        return view('order.purchases');
    }

    public function tableDataAjax(Request $request){
        return [];
    }
}

