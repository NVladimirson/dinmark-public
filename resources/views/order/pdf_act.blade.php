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

        table thead td, table tfoot td{
            background-color: #ffffff;
        }

        table tbody td{
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

<p class="f12-5 f-b t-c">Акт звірки взаєморозрахунків</p>
<p class="t-c f8" style="width: 50%; margin: 0 auto;">взаємних розрахунків по стану за період: {{$dateFromCarbon->format('d.m.Y')}} - {{$dateToCarbon->format('d.m.Y')}} між Товариство з обмеженою відповідальністю "Леомарк"
    і {{$company->name}}
</p>

<p class="f8" style="text-align: justify;">Ми, що нижче підписалися, Менеджер із збуту Товариство з обмеженою відповідальністю "Леомарк" ЗУБАЧ ПАВЛО АНДРІЙОВИЧ, з одного
    боку, і ________________ {{$company->name}} _______________________, з іншого боку, склали даний
    акт звірки у тому, що стан взаємних розрахунків за даними обліку наступний:
</p>

<table class="f8 border">
    <thead>
    <tr>
        <td colspan="4">За даними Товариство з обмеженою відповідальністю "Леомарк", грн </td>
        <td colspan="4">За даними {{$company->name}}, грн </td>
    </tr>
    </thead>
    <tbody>
    <tr class="f-b t-c">
        <td>Дата</td>
        <td>Документ</td>
        <td>Дебет</td>
        <td>Кредит</td>
        <td>Дата</td>
        <td>Документ</td>
        <td>Дебет</td>
        <td>Кредит</td>
    </tr>
    </tbody>
    <tfoot>
    <tr class="f-b">
        <td colspan="2">
            Сальдо початкове
        </td>
        <td>
            {{number_format($saldoStart,2,',',' ')}}
        </td>
        <td>
            &nbsp;
        </td>
        <td colspan="2">
            Сальдо початкове
        </td>
        <td>
            &nbsp;
        </td>
        <td>
            &nbsp;
        </td>
    </tr>
    @php
        $outPaid = 0;
        $inPaid = 0;
        $saldo = 0;
    @endphp
    @foreach($actData as $data)
    <tr>
        <td>
            {{\Carbon\Carbon::parse($data->date_add)->format('d.m.Y')}}
        </td>
        @if($data instanceof \App\Models\Order\Implementation)
            <td>
                Прийнято ({{\Carbon\Carbon::parse($data->date_add)->format('d.m.Y')}})
            </td>
            <td>
                @php
                    $sum = $data->products->sum('total');
                    $outPaid += $sum;
                @endphp
                {{number_format($sum,2,',',' ')}}
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        @else
            <td>
                Передано ({{$data->public_number}} від {{\Carbon\Carbon::parse($data->date_add)->format('d.m.Y')}})
            </td>
            <td>

            </td>
            <td>
                @php
                    $sum = $data->payed;
                    $inPaid += $sum;
                @endphp
                {{number_format($sum,2,',',' ')}}
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        @endif
    </tr>
    @endforeach

    <tr class="f-b">
        <td colspan="2">
            Обороти за період
        </td>
        <td>
            {{number_format($outPaid,2,',',' ')}}
        </td>
        <td>
            {{number_format($inPaid,2,',',' ')}}
        </td>
        <td colspan="2">
            Обороти за період
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="f-b">
        <td colspan="2">
            Сальдо кінцеве
        </td>
        <td>
            @php
                $saldo = $outPaid - $inPaid;
            @endphp
            {{-- number_format($saldo,2,',',' ') --}}
            {{number_format($saldoEnd,2,',',' ')}}
        </td>
        <td>&nbsp;</td>
        <td colspan="2">
            Сальдо кінцеве
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    </tfoot>
</table>

<p class="f7">
    За даними Товариство з обмеженою відповідальністю "Леомарк" <br>
    @if($saldo > 0)
    <b>на {{$dateToCarbon->format('d.m.Y')}} заборгованість на користь  Товариство з обмеженою <br>
        відповідальністю "Леомарк" {{number_format($saldo,2,',',' ')}} грн</b>
    @elseif($saldo < 0)
        <b>на {{$dateToCarbon->format('d.m.Y')}} заборгованість на користь <br/> {{$company->name}} {{number_format(-$saldo,2,',',' ')}} грн</b>
    @else
        <b>на {{$dateToCarbon->format('d.m.Y')}} заборгованість відсутня</b>
    @endif
</p>

<table class="f8-5" style="margin-top: 20px">
    <tfoot>
    <tr>
        <td>Від Товариство з обмеженою відповідальністю "Леомарк"</td>
        <td>Від {{$company->name}}</td>
    </tr>
    <tr>
        <td style="padding: 20px 0">Менеджер із збуту"</td>
        <td style="padding: 20px 0">___________________</td>
    </tr>
    <tr>
        <td>
            ___________________________(ЗУБАЧ П.А.)
        </td>
        <td>
            ___________________________ (_______________)
        </td>
    </tr>
    <tr>
        <td>
            М.П.
        </td>
        <td>
            М.П.
        </td>
    </tr>
    </tfoot>
</table>

</body>
</html>