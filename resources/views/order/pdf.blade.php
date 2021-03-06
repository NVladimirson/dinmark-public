<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<head>

    <style>
        body {
            font-family: DejaVu Sans;
            /* A4 ФОРМАТ */
           /* width: 210mm;
            height: 297mm;*/
        }

        table {
            width: 100%;
        }


        table thead td{
            background-color: #dbdbdb;
            padding:10px;
        }

        table.border, .border td, .border tr{
            border: 1px solid black;
            border-collapse: collapse;
        }
        .f-n {
            font-weight: normal;
        }

        .f-b {
            font-weight: bold;
        }

        .f-u {
            text-decoration: underline;
        }

        .t-c{
            text-align: center;
        }
        .t-r{
            text-align: right;
        }
        .f12-5 {
            font-size: 12.5pt;
        }

        .f8 {
            font-size: 8pt;
        }

        .f8-5 {
            font-size: 8.5pt;
        }

        .f7 {
            font-size: 7pt;
        }

        .td-border-b {
            border-bottom: 2px solid black;
            padding-bottom: 5px;
        }

        .td-border-b-1 {
            border-bottom: 1px solid black;
        }

        .td-v-b {
            vertical-align: baseline;
        }

        .m0{
            margin: 3px 0;
        }

    </style>
    <title>PDF</title>
</head>
<body>
<table cellpadding="5">
    <tr>
        <td class="td-border-b f12-5 f-b" colspan="3">Замовлення покупця № {{($order->public_number)?$order->public_number:$order->id}} від {{$date}} р.</td>
    </tr>
    <tr>
        <td class="f-u f8-5 td-v-b">Постачальник:</td>
        <td class="f8">
            <div class="f-b f8-5">

                    {{$company->name}}

               {{--@if($order->sender_id != 0) {{$order->sender->getCompany->name}} @else Товариство з обмеженою відповідальністю "Леомарк" @endif--}}
            </div>
            <div style="margin-left: 15px; margin-bottom: 20px">
                @if($company->pp)
                П/р {{$company->pp}}, @endif @if($company->bank)Банк {{$company->bank}}, @endif<br>
                    @if($company->mfo)МФО {{$company->mfo}}  <br>@endif
                    @if($company->address){{$company->address}}<br>@endif
                    @if($company->edrpo)код за ЄДРПОУ {{$company->edrpo}}, @endif @if($company->inn) ІПН {{$company->inn}}@endif
                @if($client)
                    {{--$client->company--}}
                @endif
                {{--@if($order->sender_id != 0)

                @else

                @endif--}}
            </div>
        </td>
        &nbsp;
            @if($company->full_logo && $order->sender_id != null)
                <!-- <td rowspan="2"><img align="logo" src=" {{env('DINMARK_URL')}}images/company/{{$company->full_logo}}" width="200"></td> -->
                <td rowspan="2"><img align="logo" src="https://dinmark.com.ua/images/company/{{$company->full_logo}}" width="200"></td>
                @else
                <td rowspan="2"><img align="logo" src="{{asset('logo.png')}}" width="200"></td>
            @endif
        {{--
        @if($order->sender_id != 0)
            @if($order->sender->getCompany->full_logo)
            <!-- <td rowspan="2"><img align="logo" src=" {{env('DINMARK_URL')}}images/company/{{$order->sender->getCompany->full_logo}}" width="200"></td> -->
            <td rowspan="2"><img align="logo" src="https://dinmark.com.ua/images/company/{{$order->sender->getCompany->full_logo}}" width="200"></td>
            @endif
        @else
            <td rowspan="2"><img align="logo" src=" {{asset('logo.png')}} " width="200"></td>
        @endif
        --}}
    </tr>
    <tr>
        <td class="f-u f8-5">Покупець:</td>
        <td class="f-b f8-5">{{$client?($client->company_name??$client->name):''}}</td>
    </tr>
</table>
<table class="border" style="margin-top: 25px">
    <thead class="f8-5 f-b t-c">
    <tr>
        <td>№</td>
        <td>Товар</td>
        <td colspan="2">Кількість</td>
        <td>Ціна без ПДВ</td>
        <td>Сума без ПДВ</td>
        <td>Термін доставки</td>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $i => $product)
    <tr class="f7">
        <td class="t-c">{{$i+1}}</td>
        <td>{{$product['name']}}</td>
        <td>{{$product['quantity']}}</td>
        <td>{{$product['package']}} шт</td>
        <td>{{$product['price']}}</td>
        <td>{{$product['total']}}</td>
        <td class="t-c">{{$product['storage_termin']}}</td>
    </tr>
        @endforeach
    </tbody>
</table>
<div style="width: 50%; margin-left: auto; margin-top: 10px">
    <table cellpadding="3" class="f-b f8-5">
        <tr>
            <td class="t-r">Разом:</td>
            <td class="t-c">{{$total}}</td>
        </tr>
        <tr>
            <td class="t-r">Сума ПДВ:</td>
            <td class="t-c">{{$pdv}}</td>
        </tr>
        <tr>
            <td class="t-r">Усього з ПДВ:</td>
            <td class="t-c">{{$totalPdv}}</td>
        </tr>
    </table>
</div>
<p class="m0 f7">Всього найменувань {{count($products)}}, на суму {{$totalPdv}} грн.</p>
<p class="f-b m0 f8-5 td-border-b">{{$totalPdv_text}}<br>
    т.ч. ПДВ: {{$pdv_text}}</p>
<table class="f8-5 f-b" style="margin-top: 20px">
    <tr>
        <td>Виконавець</td>
        <td class="f7 f-n">{{auth()->user()->name}}</td>
        <td class="t-r">Замовник</td>
        <td class="f-n t-r td-border-b-1">{{$client?($client->company_name??$client->name):''}}<</td>
    </tr>
    <tr>
        <td colspan="4" style="padding-top: 15px">Ціни дійсні протягом 3-х банківських днів</td>
    </tr>
</table>
</body>
</html>
