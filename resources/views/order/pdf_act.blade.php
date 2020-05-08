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
<p class="t-c f8" style="width: 50%; margin: 0 auto;">взаємних розрахунків по стану за період: 01.10.2019 - 30.03.2020 між Товариство з обмеженою відповідальністю "Леомарк"
    і {{$user->getCompany->name}}
</p>

<p class="f8" style="text-align: justify;">Ми, що нижче підписалися, Менеджер із збуту Товариство з обмеженою відповідальністю "Леомарк" ЗУБАЧ ПАВЛО АНДРІЙОВИЧ, з одного
    боку, і ________________ {{$user->getCompany->name}} _______________________, з іншого боку, склали даний
    акт звірки у тому, що стан взаємних розрахунків за даними обліку наступний:
</p>

<table class="f8 border">
    <thead>
    <tr>
        <td colspan="4">За даними Товариство з обмеженою відповідальністю "Леомарк", грн </td>
        <td colspan="4">За даними {{$user->getCompany->name}}, грн </td>
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
            &nbsp;
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
    <tr>
        <td>
            07.10.19
        </td>
        <td>
            Прийнято (07.10.2019)
        </td>
        <td>
            850,80
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            07.10.19
        </td>
        <td>
            Прийнято (07.10.2019)
        </td>
        <td>
            850,80
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            07.10.19
        </td>
        <td>
            Прийнято (07.10.2019)
        </td>
        <td>
            850,80
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>

    <tr class="f-b">
        <td colspan="2">
            Обороти за період
        </td>
        <td>
            5 362,86
        </td>
        <td>
            5 158,76
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
            204,10
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
    <b>на 30.03.2020 заборгованість на користь  Товариство з обмеженою <br>
        відповідальністю "Леомарк" 204,10 грн</b>
</p>

<table class="f8-5" style="margin-top: 20px">
    <tfoot>
    <tr>
        <td>Від Товариство з обмеженою відповідальністю "Леомарк"</td>
        <td>Від {{$user->getCompany->name}}</td>
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