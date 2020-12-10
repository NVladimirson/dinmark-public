<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Order\Payment;
use Carbon\Carbon;
use App\Services\Product\CategoryServices;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class PaymentController extends Controller
{
    public function index()
    {
        SEOTools::setTitle(trans('finance.page_payment'));

        $locale = CategoryServices::getLang();

        return view('finance.payment', compact('locale'));
    }

    public function ajax(Request $request)
    {
        $payments = Payment::whereHas('order',function ($orders){
            $orders->whereHas('getUser',function ($users){
                $users->whereHas('getCompany',function ($companies){
                    $companies->where([
                        ['id', session('current_company_id')],
                    ]);
                });
            })->orWhereHas('sender',function ($users){
                $users->whereHas('getCompany',function ($companies){
                    $companies->where([
                        ['id', session('current_company_id')],
                    ]);
                });
            });
        })->orderBy('date_add', 'desc');

        if($request->has('date_from')){
            $payments->where('date_add','>=',$request->date_from);
        }


        if($request->has('date_to')){
            $payments->where('date_add','<=',$request->date_to);
        }



        return datatables()
            ->eloquent($payments)
            ->addColumn('date_html',function (Payment $payment){
                return Carbon::parse($payment->date_add)->format('d.m.Y h:i');
            })
            ->addColumn('order_html',function (Payment $payment){
                $order = $payment->order;
                $number = $order->id;
                if($order->public_number){
                    $number .= ' / '. $order->public_number;
                }else{
                    $number .= ' / -';
                }
                return '<a href="'.route('orders.show',[$order->id]).'">'.$number.'</a>';
            })
            ->addColumn('sum_html',function (Payment $payment){

                return number_format($payment->payed,2,'.', ' ');
            })
            ->rawColumns(['date_html','order_html'])
            ->toJson();
    }
}
