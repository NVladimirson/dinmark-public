<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<head>

    <style>
        body {
            font-family: Arial;
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

        .td-border-t-1 {
            border-top: 1px solid black;
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
        <td class="td-border-b f12-5 f-b" colspan="3">Видаткова накладна № {{($implementation->public_number)?$implementation->public_number:$implementation->id}} від {{$date}} р.</td>
    </tr>
    <tr>
        <td class="f-u f8-5 td-v-b">Постачальник:</td>
        <td class="f8">
            <div class="f-b f8-5">
                Товариство з обмеженою відповідальністю "Леомарк"
            </div>
            <div style="margin-left: 15px; margin-bottom: 10px">
                П/р UA883253650000002600501445973, Банк ПАТ КРЕДОБАНК,<br>
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
                Тел.: 380675791587
            </div>
        </td>
    </tr>
    <tr class="f8-5">
        <td>№ договору:</td>
        <td>К-10/12 <br>
            Згідно рахунку №7221 від 25 березня 2020 р.</td>
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
<p class="td-border-b f8-5">
    Місце складання: с.Наконечне Перше
</p>
<table class="f8-5 f-b" style="margin-top: 20px">
    <tr>
        <td class="td-v-b">Від постачальника*</td>
        <td style="padding: 20px"></td>
        <td class="td-v-b">Отримав(ла)</td>
    </tr>
    <td class="td-border-t-1 f-n">
        <p class="f-b m0">Менеджер із збуту ЗУБАЧ ПАВЛО АНДРІЙОВИЧ</p>
        <p class="m0">* Відповідальний за здійснення господарської<br>
            операції і правильність її оформлення</p>
    </td>
    <td></td>
    <td class="td-border-t-1">
        <p class="f-b m0">Директор</p>
        <p class="m0 f-n">За довіреністю &emsp; &emsp; &emsp; &emsp; № &emsp; &emsp; &emsp; &emsp; від</p>
    </td>
</table>
</body>
</html>