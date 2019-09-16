<body style="margin: 0; font-family: DejaVu Sans, sans-serif; font-size: 12px;">

<h3 style="text-align: center; font-size: 12px;">ТОО "Ломбард Аванс Плюс Кз"</h3>

<p style="font-size: 9px;">
    Адрес: г. Алматы, ул. мкрн. Алмагуль, дом 9, кв. 144<br/>
    Телефоны: 8 (776) 222-55-66
</p>

<table style="width: 100%; font-size: 12px;" class="bordered">
    <tr>
        <td rowspan="2"><b>Залоговый билет</b></td>
        <td class="bordered">Номер документа</td>
        <td class="bordered">Дата составления</td>
    </tr>
    <tr>
        <td><b>{{$loan->loan_id}}</b></td>
        <td><b>{{ date('d.m.Y', strtotime($loan->issue_date)) }}</b></td>
    </tr>
    <tr>
        <td>Заёмщик:</td>
        <td colspan="2"><b>{{$loan->borrower->full_name}}</b></td>
    </tr>
    <tr>
        <td>ИИН:</td>
        <td colspan="2"><b>{{$loan->borrower->borrowerIdentificationCard->IIN}}</b></td>
    </tr>
    <tr>
        <td>Паспортные данные:</td>
        <td colspan="2"><b>Уд.Личности №{{$loan->borrower->borrowerIdentificationCard->number}}
            выдано {{ date('d.m.Y', strtotime($loan->borrower->borrowerIdentificationCard->issue_date)) }} {{($loan->borrower->borrowerIdentificationCard->issuedAuthority ? $loan->borrower->borrowerIdentificationCard->issuedAuthority->text : "")}}</b></td>
    </tr>
    <tr>
        <td>Место жительства:</td>
        <td colspan="2"><b>@if(!empty($loan->borrower->borrowerAddress->full_address))
                {{$loan->borrower->borrowerAddress->full_address}}
            @elseif(!empty($loan->borrower->place_of_residence))
                {{$loan->borrower->place_of_residence}}
            @else
                Республика Казахстан, город {{isset($loan->borrower->borrowerAddress->raCity) ? $loan->borrower->borrowerAddress->raCity->name : ""}}, улица {{$loan->borrower->borrowerAddress->ra_street_name}}, дом № {{$loan->borrower->borrowerAddress->ra_house_number}}{{ (!empty($loan->borrower->borrowerAddress->ra_apartment_number) ? ", квартира №{$loan->borrower->borrowerAddress->ra_apartment_number}" : "") }}
            @endif</b></td>
    </tr>
</table>
<br/>
<table style="width: 100%; font-size: 12px;" class="bordered">
    <tr>
        <td><b>Залог</b></td>
        <td><b>Сумма займа</b></td>
    </tr>
    <tr>
        <td>денежные средства должника находящиеся на банковском счете №<br/><b>{{ $loan->borrower->borrowerBankAccount->number}} в {{ $loan->borrower->borrowerBankAccount->bank_name_with_bik}}</b></td>
        <td>{{$loan->sum}}</td>
    </tr>
    <tr>
        <td style="text-align: right"><b>ИТОГО</b></td>
        <td>{{$loan->sum}}</td>
    </tr>
</table>

<p><b>ТОО "Ломбард Аванс Плюс Кз" уведомляет, что срок займа истекает: {{ date('d.m.Y', strtotime($loan->expiration_date)) }}</b></p>
<p>
    Итого сумма займа: {{$loan->sum}} (<span class="summa">{{\App\Helpers\MathHelper::numberToString($loan->sum)}} тенге</span>)<br/>
    Срок займа: <b>{{$loan->duration_agreement}} дней</b><br/>
    Дата выдачи займа: {{ date('d.m.Y', strtotime($loan->issue_date)) }}<br/>
    Дата погашения займа: <b>{{ date('d.m.Y', strtotime($loan->expiration_date)) }}</b><br/>
    Сумма вознаграждения(%) за 1 день:: <b>{{$loan->sum * $loan->interest_rate}}</b> тенге, по процентной ставке:
    <b>{{\App\Helpers\AppHelper::getConfig("interest_rate") * 100}}%</b> в день<br/>
    Сумма начисленного вознаграждения(%) за 30 дней:
    <b>{{$loan->sum * $loan->interest_rate * $loan->duration_agreement}}</b> тенге
</p>
<p style="font-size: 9px;">
    Заемщик обязуется возвратить Сумму займа и оплатить Сумму начисленного вознаграждения (процентов) за весь cрок
    займа, установленный настоящим залоговым билетом, по истечении Срока займа.
    В случае невозвращения в установленный залоговым билетом срок Суммы займа, Заемщик признает требования Ломбарда по
    взысканию Суммы займа и Суммы начисленного вознаграждения бесспорными, так же Заемщик признает бесспорными
    требования Ломбарда по взысканию с Заемщика суммы судебных издержек, которые складываются из суммы неустойки (10
    МРП) и суммы государственной пошлины, и других сборов третьих лиц.
    Все споры и разногласия, возникающие между Сторонами по Залоговому билету и настоящему Договору и/или в связи с ним,
    разрешаются только в судебном порядке. Споры должны быть рассмотрены в Бостандыкском районном суде города Алматы в
    соответствии с действующим законодательством Республики Казахстан.<br/>
    Так же, руководствуясь ст. 135, cт. 140 ГПК РК, Стороны признают, что требования Ломбарда по возврату Суммы займа, оплате Суммы<br/> начисленного вознаграждения, оплаты сумм судебных издержек являются бесспорными и Ломбард, заявляя свои требования к Заемщику, обращается к нотариусу за совершением исполнительной надписи или обращается в суд с заявлением о вынесении судебного приказа.<br/>

    С условиями залогового билета, Договором залога, правилами Ломбарда (расположенными на сайте www.avansplus.kz) ознакомлен и согласен.
</p>

<table style="width: 100%; font-size: 12px;" class="unbordered">
    <tr>
        <td colspan="3" style="vertical-align: middle;">
            Я , заемщик, <b>{{$loan->borrower->full_name}}</b> претензий иметь не буду.<br/>
            Настоящий залоговый билет составлен в 2-х экземплярах.
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="vertical-align: middle;">
            Заемщик _________________________
        </td>
        <td style="vertical-align: middle;">
            <b style="white-space: nowrap;">{{$loan->borrower->full_name}}</b>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="vertical-align: middle;">
            Директор: Лебедев Роман Сергеевич
        </td>
        <td style="vertical-align: middle;">
            <img src='{{asset("images/signature.png")}}' style="width: 100px; margin: 0;" />
        </td>
        <td style="vertical-align: middle;">
            <img src='{{asset("images/stamp.png")}}' style="width: 100px; margin: 0;" />
        </td>
    </tr>
</table>
<p style="font-size: 9px">
	ТОО «Ломбард Аванс Плюс КЗ»<br/>
	Юридический адрес: Республика Казахстан, г. Алматы, мкрн. Алмагуль, дом 9, кв. 144<br/>
	Рас.счет/IBAN: KZ666017131000000639<br/>
	Банк: АО «Народный Банк Казахстана»<br/>
	БИК: HSBKKZKX
</p>

<div class="page-break" style="page-break-after: always;"><br/><br/></div>

<div id="veksel" style="position: relative; padding: 80px 80px 40px;">
    <img src='{{asset("images/frame.png")}}' id="frame" style="position: absolute; top: 0px; left: 0px; width: 135%; height: 1000px; margin: 0;" />

    <h3 style="text-align: center; font-size: 16px;">Простой вексель</h3>
    <p>На сумму: <b>{{$loan->billSumForPeriod(40, 10)}} <span class="summa">{{\App\Helpers\MathHelper::numberToString($loan->billSumForPeriod(40, 10))}} тенге</span></b></p>
    <p>Дата составления векселя: <b>{{ date('d.m.Y', strtotime($loan->issue_date)) }}</b><br/></p>
    <p>Место составления векселя: г.Алматы, ул. Айтеке би 134/1, оф 50</p>
    <p>Векселедатель: <b>{{$loan->borrower->full_name}}</b></p>

    обязуется безусловно уплатить по этому векселю денежную сумму в размере:<br/>
    {{$loan->billSumForPeriod(40, 10)}}  (<span class="summa">{{\App\Helpers\MathHelper::numberToString($loan->billSumForPeriod(40, 10))}} тенге</span>)<br/>
    <br/>
    непосредственно Векселедержателю: <br/>
    ТОО "Ломбард Аванс Плюс Кз", г. Алматы, мкрн. Алмагуль, дом 9, кв. 144, БИН: 170340000816<br/>
    <br/>
    или по его приказу любому другому третьему лицу.<br/>
    Этот вексель подлежит оплате в следующий срок: по предъявлении, но не ранее <b>{{ date('d.m.Y', strtotime($loan->billDateForPeriod(40))) }}</b>

    <table class="none_padding_lefttable" style="font-size: 12px;">
        <tr>
            <td>Местом платежа является:</td>
            <td>г.Алматы, Рас.счет/IBAN: KZ666017131000000639<br/>
                Банк: АО «Народный Банк Казахстана»<br/>
                БИК: HSBKKZKX</td>
        </tr>
    </table>

    Уд.Личности №		{{$loan->borrower->borrowerIdentificationCard->number}}	выдано	{{date('d-m-Y', strtotime($loan->borrower->borrowerIdentificationCard->issue_date))}} {{($loan->borrower->borrowerIdentificationCard->issuedAuthority ? $loan->borrower->borrowerIdentificationCard->issuedAuthority->text : "")}}
    проживающий по адресу:
    @if(!empty($loan->borrower->borrowerAddress->full_address))
        {{$loan->borrower->borrowerAddress->full_address}}
    @elseif(!empty($loan->borrower->place_of_residence))
        {{$loan->borrower->place_of_residence}}
    @else
        Республика Казахстан, город {{isset($loan->borrower->borrowerAddress->raCity) ? $loan->borrower->borrowerAddress->raCity->name : ""}}, улица {{$loan->borrower->borrowerAddress->ra_street_name}}, дом № {{$loan->borrower->borrowerAddress->ra_house_number}}{{ (!empty($loan->borrower->borrowerAddress->ra_apartment_number) ? ", квартира №{$loan->borrower->borrowerAddress->ra_apartment_number}" : "") }}
    @endif
    <br/>

    ИИН	<b>{{$loan->borrower->borrowerIdentificationCard->IIN}}</b><br/>
    <br/>

    ФИО (полностью, прописью) и подпись Векселедателя:<br/><br/>
    _____________________________________________________________________________

    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>


    ДЛЯ АВАЛЯ (вексельное поручительство)<br/><br/>

    За кого выдан _____________________________________________________________<br/><br/>
    Кем выдан ________________________________________________________________<br/><br/>
    Дата ________________ Подпись авалиста ________________________________М.П.<br/><br/>

</div>

<style>
    body {
        margin: 0;
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
    }
    .page-break {
        page-break-after: always;
    }

    h3 {
        font-size: 12px;
    }

    img {
        width: 150px;
        margin: 10px;
    }

    .bordered, .bordered td {
        border: 1px solid #000;
    }

    table {
        width: 500px;
        border-collapse: collapse;
        font-size: 12px;
    }
    table td {
        padding: 2px 5px;
        vertical-align: top;
    }
    .none_padding_lefttable td {
        padding-left: 0px ;
    }
    table.unbordered td {
		padding: 2px 0;
        border: 0px solid #000;
        vertical-align: middle;
    }
	@media screen and (max-width: 850px) {
		#veksel {padding; 80px 40px 40px!important}
		#frame {height: 1110px!important}
	}
</style>

</body>