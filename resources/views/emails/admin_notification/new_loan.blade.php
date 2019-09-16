<div style="max-width: 678px; font-family: Tahoma; font-weight: normal; line-height: 20px;">
    <h2 style="margin-bottom: 5px; font-size: 18px;  color: #505863;">Новая заявка №{{$loan->loan_id}}</h2>
    <p style="margin-top: 0; margin-bottom: 20px; font-size: 12px; color: #505863;">Подана новая заявка на займ:</p>
    <table cellpadding="5" border="1" cellspacing="0">
    <tr>
    <td>Фамилия</td>
    <td>{{$loan->borrower->lastname}}</td>
    </tr>
    <tr>
    <td>Имя</td>
    <td>{{$loan->borrower->firstname}}</td>
    </tr>
    <tr>
    <td>Отчество</td>
    <td>{{$loan->borrower->fathername}}</td>
    </tr>
    <tr>
    <td>Телефон</td>
    <td>{{$loan->borrower->phone_number}}</td>
    </tr>
    <tr>
    <td>ИИН</td>
    <td>{{$loan->borrower->borrowerIdentificationCard->IIN}}</td>
    </tr>
    <tr>
    <td>Email</td>
    <td>{{$loan->borrower->email}}</td>
    </tr>
    <tr>
    <td>Место работы</td>
    <td>{{$loan->borrower->borrowerEmployment->work_place}}</td>
    </tr>
    <tr>
    <td>Должность</td>
    <td>{{$loan->borrower->borrowerEmployment->working_position}}</td>
    </tr>
    <tr>
    <td>Размер заработной платы</td>
    <td>{{$loan->borrower->borrowerEmployment->salary}}</td>
    </tr>
        @if(!empty($loan->borrower->borrowerEmployment->salaryObtainingMethod))
    <tr>
    <td>Способ получения зарплаты</td>
    <td>{{($loan->borrower->borrowerEmployment->salaryObtainingMethod->text)}}</td>
    </tr>
        @endif
    <tr>
    <td>Номер счета</td>
    <td>{{$loan->borrower->borrowerBankAccount->number}}</td>
    </tr>
    <tr>
    <td>Желаемая сумма займа</td>
    <td>{{$loan->sum}}</td>
    </tr>
    </table>
    <p style="margin-bottom: 20px; font-size: 12px; color: #505863;">С уважением,</p>
    <img src="{{asset("images/mail_sign.jpg")}}">
</div>