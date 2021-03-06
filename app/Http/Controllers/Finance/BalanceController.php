<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Order\Implementation;
use App\Models\Order\Payment;
use App\Services\Finance\BalanceServices;
use App\Services\Product\CategoryServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class BalanceController extends Controller
{
    public function index()
    {
        SEOTools::setTitle(trans('finance.page_balance'));
        $locale = CategoryServices::getLang();
        return view('finance.balance',compact('locale'));
    }

    public function ajax(Request $request){

        $implementations = BalanceServices::getFilteredImplementation($request)->get();
        $payments = BalanceServices::getFilteredPayment($request)->get();

        $actData = $implementations->concat($payments)->sortBy('date_add');

        return datatables()
            ->collection($actData)
            ->addColumn('date_html',function ($data){
                return Carbon::parse($data->date_add)->format('d.m.Y h:i');
            })
            ->addColumn('document_html',function ($data){
                if($data instanceof Implementation){
                    return trans('finance.implementation').' '.$data->public_number;
                }else{
                    return trans('finance.payment').' '.$data->public_number;
                }
            })
            ->addColumn('debet_html',function ($data){
                if($data instanceof Implementation){
                    $sum = $data->products->sum('total');
                    return number_format($sum,2,',',' ');
                }
                return '';
            })
            ->addColumn('credit_html',function ($data){
                if($data instanceof Payment){
                    $sum = $data->payed;
                    return number_format($sum,2,',',' ');
                }
                return '';
            })
            ->addColumn('currency_html',function ($data){
                return 'UAH';
            })
            ->addColumn('action_buttons',function ($data){
                if($data instanceof Implementation){
                    return '<a href="'.route('implementations.pdf',[$data->id]).'" class="btn btn-sm btn-primary" title="'.trans('implementation.btn_generate_pdf',[$data->id]).'"><i class="fas fa-file-alt"></i></a>';
                }else{
                    return "";
                }
            })
            ->rawColumns(['action_buttons'])
            ->toJson();
    }

    public function totalDataAjax(Request $request)
    {
        $saldo = BalanceServices::calcSaldo($request);
        $debit = BalanceServices::calcDebit($request);
        $credit = BalanceServices::calcCredit($request);

        return response()->json([
            'status' => 'success',
            'saldo_start'        => number_format($saldo['saldoStart'],2,'.',' '),
            'total_debit'        => number_format($debit,2,'.',' '),
            'total_credit'       => number_format($credit,2,'.',' '),
            'saldo_end'          => number_format($saldo['saldoEnd'],2,'.',' '),
        ]);
    }
}
