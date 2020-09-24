<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 30.03.2020
 * Time: 17:27
 */

namespace App\Services\Finance;

use App\Models\Company\Client;
use App\Models\Company\Company;
use App\Models\Order\Implementation;
use App\Models\Order\Order;
use App\Models\Order\Payment;
use Carbon\Carbon;

class BalanceServices
{
    public static function getImplementations(){
        $implementations =  Implementation::where(function ($impl){
            $impl->whereHas('sender',function ($users){
                $users->whereHas('getCompany',function ($companies){
                    $companies->where([
                        ['id', session('current_company_id')],
                    ]);
                });
            })
                ->orWhereHas('customer',function ($users){
                    $users->whereHas('getCompany',function ($companies){
                        $companies->where([
                            ['id', session('current_company_id')],
                        ]);
                    });
                });
        })
            ->where('bukh','<>',0);;

        return $implementations;
    }

    public static function getFilteredImplementation($request){
        $dateFromCarbon = Carbon::createFromTimestamp($request->date_from);
        $dateToCarbon = Carbon::createFromTimestamp($request->date_to)->addDay();
        $dateFrom = $dateFromCarbon->timestamp;
        $dateTo = $dateToCarbon->timestamp;

        $instance =  static::getInstance();

        $implementations =  $instance->getImplementations();

        if($request->has('date_from')){
            $implementations->where('date_add','>=',$dateFrom);
        }

        if($request->has('date_to')){
            $implementations->where('date_add','<=',$dateTo);
        }

        return $implementations;
    }

    public static function getPayments(){
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
        });

        return $payments;
    }

    public static function getFilteredPayment($request){
        $dateFromCarbon = Carbon::createFromTimestamp($request->date_from);
        $dateToCarbon = Carbon::createFromTimestamp($request->date_to)->addDay();
        $dateFrom = $dateFromCarbon->timestamp;
        $dateTo = $dateToCarbon->timestamp;

        $instance =  static::getInstance();

        $payments =  $instance->getPayments();

        if($request->has('date_from')){
            $payments->where('date_add','>=',$dateFrom);
        }

        if($request->has('date_to')){
            $payments->where('date_add','<=',$dateTo);
        }

        return $payments;
    }

    public static function calcSaldo($request)
    {
        $instance =  static::getInstance();
        $dateFromCarbon = Carbon::parse(0);
        if($request->has('date_from')){
            $dateFromCarbon = Carbon::createFromTimestamp($request->date_from);
        }

        $dateToCarbon = Carbon::now();
        if($request->has('date_to'))
        {
            $dateToCarbon = Carbon::createFromTimestamp($request->date_to)->addDay();
        }
        $dateFrom = $dateFromCarbon->timestamp;
        $dateTo = $dateToCarbon->timestamp;

        $company = Company::find(session('current_company_id'));

        $saldoStart = 0;
        $saldoEnd = $company->balance;
        $implementations = $instance
            ->getImplementations()
            ->where('date_add','<',$dateFrom)
            ->get();

        $payments = $instance
            ->getPayments()
            ->where('date_add','<',$dateFrom)
            ->get();

        foreach ($implementations as $implementation){
            $saldoStart += $implementation->products->sum('total');
        }
        foreach ($payments as $payment){
            $saldoStart -= $payment->payed;
        }

        $implementations = BalanceServices::getImplementations()->where('date_add','>',$dateTo)->get();

        $payments = $instance
            ->getPayments()
            ->where('date_add','>',$dateTo)
            ->get();

        foreach ($implementations as $implementation){
            $saldoEnd += $implementation->products->sum('total');
        }
        foreach ($payments as $payment){
            $saldoEnd -= $payment->payed;
        }

        return [
            'saldoStart' => $saldoStart,
            'saldoEnd' => $saldoEnd,
        ];
    }

    public static function calcDebit($request)
    {
        $instance =  static::getInstance();
        $implementations = $instance->getFilteredImplementation($request)->get();

        $debit = 0;

        foreach ($implementations as $implementation){
            $debit += $implementation->products->sum('total');
        }

        return $debit;
    }

    public static function calcCredit($request)
    {
        $instance =  static::getInstance();
        $payments = $instance->getFilteredPayment($request)->get();

        $credit = 0;

        foreach ($payments as $payment){
            $credit += $payment->payed;
        }

        return $credit;
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
	}
	private function __clone(){}
	private function __wakeup(){}
}
