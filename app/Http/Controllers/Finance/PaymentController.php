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

        // if($request->has('search')){
        //     $searchstr = request('search')['value'];
        //     // $payment = collect();
        //     $search = explode('/',$searchstr);
        //     info('SEARCH');
        //     info($search);
        //     foreach ($search as $key => $value) {
        //       $search[$key] = trim($value);
        //     }
        //     info('SEARCHTR');
        //     info($search);
        //     if(count($search)>1){
        //       //search payment or number
        //
        //       if(isset($search[0])){
        //         $search_number = $search[0];
        //       }else{
        //         $search_number = '';
        //       }
        //
        //       if(isset($search[1])){
        //         $search_payment = $search[1];
        //       }else{
        //         $search_payment = '';
        //       }
        //
        //       $found_number = false;
        //       if($search_number){
        //         $payments->whereHas('order', function($orders) use($search_number){
        //           $orders->where('id','like',"%" . $search_number . "%");
        //         });
        //         // $found_number = true;
        //       }
        //
        //       if($search_payment){
        //             $payments->whereHas('order', function($orders) use($search_payment){
        //               $orders->where('public_number','like',"%" . $search_payment . "%");
        //             });
        //       //   if($found_number){
        //       //     $payment = $payment->whereHas('order', function($orders) use($search_payment){
        //       //       $orders->where('public_number','like',"%" . $search_payment . "%");
        //       //     });
        //       //     $payment = $payment->get();
        //       //   }else{
        //       //     $payment = \App\Models\Order\Payment::whereHas('order', function($orders) use($search_payment){
        //       //       $orders->where('public_number','like',"%" . $search_payment . "%");
        //       //     });
        //       //   }
        //       // }
        //     }
        //   }
        //     else{
        //       //search payment and number
        //       $searchstr = $search[0];
        //       if(trim($searchstr)){
        //         $payments->whereHas('order', function($orders) use($searchstr){
        //           $orders->where('id','like',"%" . $searchstr . "%")
        //           ->orWhere('public_number','like',"%" . $searchstr . "%");
        //         });
        //       }
        //     }
        //     info('SO');
        //     info(count($payments->get()));
        // }

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
