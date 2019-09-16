<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 8pt;
    }
    h3{
        font-size: 12pt;
    }

    img{
        width: 150px;
        margin: 10px;
    }

    .bordered, .bordered td{
        border: 1px solid #000;
    }

    table{
        width: 500px;
        border-collapse: collapse;
    }
    table td{
        padding: 5px;
    }
    table.unbordered td{
        border: 0px solid #000;
    }
</style>
<body>
<h3 style="text-align: center;">ТОО "Ломбард Аванс Плюс Кз"</h3>

<p>
Адрес: г.Алматы, мкрн. Алмагуль, дом 9, кв. 144<br/>
Телефоны: 8 (776) 222-55-66
</p>

<table style="width: 100%;">
    <tr>
        <td rowspan="2"><b>Залоговый билет</b></td>
        <td class="bordered">Номер документа</td>
        <td class="bordered">Дата составления</td>
    </tr>
    <tr>
        <td><b>{{$loan->loan_id}}</b></td>
        <td><b>{{$loan->issue_date}}</b></td>
    </tr>
    <tr>
        <td>Заёмщик:</td>
        <td colspan="2"><b>{{$loan->borrower->full_name}}</b></td>
    </tr>
    <tr>
        <td>ИИН:</td>
        <td colspan="2">{{$loan->borrower->borrowerIdentificationCard->IIN}}</td>
    </tr>
    <tr>
        <td>Паспортные данные:</td>
        <td colspan="2">Уд.Личности №{{$loan->borrower->borrowerIdentificationCard->number}} выдано {{$loan->borrower->borrowerIdentificationCard->issue_date}}</td>
    </tr>
    <tr>
        <td>Место жительства:</td>
        <td colspan="2">Республика Казахстан, город {{$loan->borrower->borrowerAddress->raCity->name}}, улица {{$loan->borrower->borrowerAddress->ra_street_name}}, дом № {{$loan->borrower->borrowerAddress->ra_house_number}}{{ (!empty($loan->borrower->borrowerAddress->ra_apartment_number) ? ", квартира №{$loan->borrower->borrowerAddress->ra_apartment_number}" : "") }}</td>
    </tr>
</table>

<table style="width: 100%;">
    <tr>
        <td><b>Залог</b></td>
        <td><b>Сумма займа</b></td>
    </tr>
    <tr>
        <td>По договору залога {{$loan->loan_id}} от {{$loan->issue_date}}</td>
        <td>{{$loan->sum}}</td>
    </tr>
    <tr>
        <td style="text-align: right"><b>ИТОГО</b></td>
        <td>{{$loan->sum}}</td>
    </tr>
</table>

<p><b>ТОО "Ломбард Аванс Плюс Кз" уведомляет, что срок займа истекает: {{$loan->expiration_date}}</b></p>
<br/>
<p>
    Итого сумма займа: {{$loan->sum}}<br/>
    Срок займа: <b>{{$loan->duration_agreement}} дней</b><br/>
    Дата выдачи займа: {{$loan->issue_date}}<br/>
    Дата выдачи займа: <b>{{$loan->expiration_date}}</b><br/>
    Сумма вознаграждения(%)  а 1 день:: {{$loan->sum * $loan->interest_rate}} тенге, по процентной ставке:
     <b>{{\App\Helpers\AppHelper::getConfig("interest_rate") * 100}}</b> в день<br/>
    Сумма начисленного вознаграждения(%)  за 30 дней: <b>{{$loan->sum * $loan->interest_rate * $loan->duration_agreement}}</b> тенге<br/>
    <br/>
    "Заемщик обязуется возвратить Сумму займа и оплатить Сумму начисленного вознаграждения (процентов) за весь Срок займа, установленный настоящим залоговым билетом, по истечении Срока займа.
    В случае невозвращения в установленный залоговым билетом срок Суммы займа, Заемщик признает требования Ломбарда по взысканию Суммы займа и Суммы начисленного вознаграждения бесспорными, так же Заемщик признает бесспорными требования Ломбарда по взысканию с Заемщика суммы судебных издержек, которые складываются из суммы (10 МРП) и суммы государственной пошлины и других сборов третьих лиц.
    Все споры и разногласия, возникающие между Сторонами по Залоговому билету и настоящему Договору и/или в связи с ним, разрешаются только в судебном порядке. Споры должны быть рассмотрены в Бостандыкском районном суде города Алматы в соответствии с действующим законодательством Республики Казахстан.
    Так же, руководствуясь ст. 135, cт. 140 ГПК РК, Стороны признают, что требования Ломбарда по возврату Суммы займа, оплате Суммы начисленного вознаграждения, оплаты сумм судебных издержек являются бесспорными и Ломбард, заявляя свои требования к Заемщику, обращается к нотариусу за совершением исполнительной надписи или обращается в суд с заявлением о вынесении судебного приказа.
    С условиями залогового билета, Договором залога и правилами Ломбарда (расположенными на сайте www.avansplus.kz) ознакомлен и согласен."

</p>
<br/>
<table style="width: 100%;" class="unbordered">
<tr>
<td>
Я , заемщик, {{$loan->borrower->full_name}} претензий иметь не буду.<br/>
Настоящий залоговый билет составлен в 2-х экземплярах .<br/>
    <br/>
Заемщик  ______________________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$loan->borrower->full_name}}<br/>
    <br/>
Директор: Лебедев Роман Сергеевич&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="{{asset("images/signature.png")}}"><br/>
</td>
<td>
    <img src="{{asset("images/stamp.png")}}">
</td>
</tr>
</table>
</body>