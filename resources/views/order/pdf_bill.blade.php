<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<head>

    <style>
        body {
            font-family: Arial;
        }

        table {
            width: 100%;
        }


        table thead td{
            background-color: #dbdbdb;
            padding:10px;
        }

        table.border, table.border td, table.border tr, .border{
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

        .f10 {
            font-size: 10pt;
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

        .td-border-t-1 {
            border-top: 1px solid black;
        }

        .td-border-l {
            border-left: 2px solid black;
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

<table class="border f8 m0">
    <tr>
        <td>Увага! Оплата цього рахунку означає погодження з умовами поставки товарів. Повідомлення про оплату є обов'язковим,
            в іншому випадку не гарантується наявність товарів на складі. Товар відпускається за фактом надходження коштів на
            п/р Постачальника, самовивозом, за наявності довіреності та паспорта.</td>
    </tr>
</table>
<Br>
<div class="border">
    <table style="border-spacing: 0;">
        <tbody><tr>
            <td colspan="4" class="f10 f-b" style="text-align: center">Зразок заповнення платіжного доручення</td>
        </tr>
        <tr class="f8-5">
            <td style="width: 20%">Одержувач</td>
            <td class="f-b" colspan="2">Товариство з обмеженою відповідальністю "Леомарк"</td>
            <td style="width:15%" class="td-border-l">&nbsp;</td>
        </tr>
        <tr class="f8-5">
            <td>Код</td>
            <td class="f-b"><span style="border: 2px solid black;padding:5px 20px;display: inline-block">23266835</span></td>
            <td>КРЕДИТ рах. N</td>
            <td class="td-border-l">&nbsp;</td>
        </tr>
        <tr class="f8-5">
            <td>
                Банк одержувача
            </td>
            <td>Код банку</td>
            <td style="border: 2px solid black;padding:5px 20px;width: 33%;border-bottom: 0;border-right: 0;">UA883253650000002600501445973</td>
            <td class="td-border-l">&nbsp;</td>
        </tr>
        <tr class="f8-5">
            <td class="f-b">
                Банк ПАТ КРЕДОБАНК
            </td>
            <td class="f-b">
                <span style="border: 2px solid black;padding:5px 20px;display: inline-block">325365</span>
            </td>
            <td style="border: 2px solid black;padding:5px 20px;width: 33%;border-right: 0;">
                &nbsp;
            </td>
            <td class="td-border-l">&nbsp;</td>
        </tr>
        </tbody></table>
</div>

<br>

<table cellpadding="5">
    <tr>
        <td class="td-border-b f12-5 f-b" colspan="3">Рахунок на оплату № {{$order->public_number}} від {{$date}} р.</td>
    </tr>
    <tr>
        <td class="f-u f8-5 td-v-b">Постачальник:</td>
        <td class="f8">
            <div class="f-b f8-5">
                Товариство з обмеженою відповідальністю "Леомарк"
            </div>
            <div style="margin-left: 15px; margin-bottom: 10px">
                П/р UA883253650000002600501445973, Банк Банк ПАТ КРЕДОБАНК,<br>
                МФО 325365<br>
                81032, Львівська обл., Яворівський р-н, с.Наконечне Перше, М.Лисенка,<br>
                будинок № 17,<br>
                код за ЄДРПОУ 23266835, ІПН 232668313332
            </div>
        </td>
        <td rowspan="2"><img align="logo" src=" {{asset('logo.png')}} " width="200"></td>
    </tr>
    <tr>
        <td class="f-u f8-5 td-v-b">Покупець:</td>
        <td class="f-b f8-5">{{$user->getCompany->name}}
            <div class="f-n" style="margin-left: 15px">
                {{--  Тел.: 380675791587 --}}
            </div>
        </td>
    </tr>
    <tr class="f8-5">
        <td>№ договору:</td>
        <td></td>
    </tr>
    <tr class="f8-5">
        <td>№ замовлення:</td>
        <td>{{$order->public_number}}</td>
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
        <td>Рахунок дійсний до сплати протягом 3-х банківських днів</td>
        <td class="t-r f-b">Виписав(ла):</td>
        <td class="f-n t-r td-border-b-1">Тетяна Шалан</td>
    </tr>
</table>
</body>
</html>