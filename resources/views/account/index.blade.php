@extends('layouts.app')

@section('content')
    @component('components.common.header_menu')
    @endcomponent
    <style>
        .logo img {
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
    <section class="account">
        <div class="container">
            <h2 class="h1 title"><span>Личный кабинет</span></h2>
            <div class="tabs">
                <div class="tab-nav">
                    <a href="#tab-1" title="" class="active" tab>Открытый <br/>заем</a>
                    <a href="#tab-2" title="" class="" tab>Настройки <br/>профиля</a>
                    <a href="#tab-3" title="" class="" tab>Платежные <br/>реквизиты</a>
                    <a href="#tab-4" title="" class="" tab>История <br/>займов</a>
                </div>
                <div class="tab-content">
                    <div id="tab-1" class="tab">
                        @if(!isset($active_loan))
                            <p class="text-center">Текущие займы отсутствуют</p>
                        @else
                            @if($active_loan->availabelForRepayment())
                                <div class="row">
                                    <div class="col-md-5 pull-xs-none">
                                        <h2 class="title">
                                            <a href="{{route("loan.repayment")}}" title="" class="pull-xs-left">Погасить
                                                сейчас</a>
                                            <a href="{{route("loan.extend")}}" title=""
                                               class="pull-xs-right">Продлить</a>
                                        </h2>
                                        @if($active_loan->amountExtension() > 0)
                                            <div class="hint">Продлите займ сейчас всего
                                                за {{$active_loan->amountExtension()}}
                                                тенге
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if($active_loan->wasApproved())
                            @if($active_loan->isOverdue())
                                <div class="row">
                                    <div class="col-md-10 pull-xs-none">
                                        <h1 class="title late">Ваш займ просрочен! Сумма к погашению
                                            составляет {{$active_loan->amountMaturity()}}
                                            тенге</h1>
                                    </div>
                                </div>
                            @endif
                            @endif
                            <div class="row">
                                <div class="col-md-8 pull-xs-none">
                                    <table>
                                        <tr>
                                            <td>Номер договора займа</td>
                                            <td>{{$active_loan->loan_id}}</td>
                                        </tr>
                                        <tr>
                                            <td>Заявка принята</td>
                                            <td>{{$active_loan->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Сумма займа</td>
                                            <td>{{$active_loan->sum}} тенге</td>
                                        </tr>
                                        <tr>
                                            <td>Процент вознаграждения</td>
                                            <td>{{$active_loan->reward_sum}} тенге</td>
                                        </tr>
                                        @if($active_loan->wasApproved())
                                            <tr>
                                                <td>Дата выдачи займа</td>
                                                <td>{{$active_loan->issue_date}}</td>
                                            </tr>
                                            <tr>
                                                <td>Расходы по принудительному взысканию</td>
                                                <td>{{$active_loan->enforcementCost()}} тенге</td>
                                            </tr>
                                            <tr>
                                                <td>Погасить не позже</td>
                                                <td>{!!  ($active_loan->isOverdue()) ? '<span class="late">'. $active_loan->expiration_date .' - просрочен</span>' : $active_loan->expiration_date  !!}</td>
                                            </tr>
                                            <tr>
                                                <td>Срок пользования займом</td>
                                                <td>{{$active_loan->duration_actual}} дней</td>
                                            </tr>
                                            <tr>
                                                <td>Погашенная сумма</td>
                                                <td>{{$active_loan->paid_sum}} тенге</td>
                                            </tr>
                                            <tr>
                                                <td>Осталось к погашению</td>
                                                <td>{{$active_loan->amountMaturity()}} тенге</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>Статус кредита</td>
                                            <td>{{$active_loan->loanStatus->text}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div id="tab-2" class="tab">
                        <div class="row">
                            <div class="col-md-8 pull-xs-none">
                                <form name="profile_settings"
                                      action="{{action("BorrowerController@update", Session::get('borrower_id'))}}">
                                    <input name="_method" type="hidden" value="PUT">
                                    <div class="item">
                                        <h3>Контактная информация</h3>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Мобильный телефон</label>
                                                <span class="fake_input">{{$borrower->phone_number}}</span>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Электронная почта</label>
                                                <span class="fake_input">{{$borrower->email}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <h3>Данные из удостоверения личности
                                            <button type="button" id="edit">Редактировать</button>
                                        </h3>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Фамилия</label>
                                                <input type="text" name="lastname" value="{{$borrower->lastname}}"
                                                       readonly/>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Имя</label>
                                                <input type="text" name="firstname" value="{{$borrower->firstname}}"
                                                       readonly/>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Отчество</label>
                                                <input type="text" name="fathername" value="{{$borrower->fathername}}"
                                                       readonly/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Пол</label>
                                                <select name="gender_id" disabled>
                                                    <option>Выберите значение</option>
                                                    @foreach ($genders as $gender)
                                                        <option value="{{$gender->id}}" {{ $borrower->gender_id == $gender->id ? "selected" : ""}}>{{$gender->text}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>ИНН</label>
                                                <span class="fake_input">{{$borrower->borrowerIdentificationCard->IIN}}</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Дата рождения</label>
                                                <input type="text" name="DOB"
                                                       value="{{date('d-m-Y', strtotime($borrower->DOB))}}"
                                                       data-date-reverse
                                                       readonly/>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Место рождения</label>
                                                <input type="text" name="place_birth" value="{{$borrower->place_birth}}"
                                                       readonly/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>№ удостоверения личности</label>
                                                <input type="text" name="borrower_identification_card[number]"
                                                       value="{{$borrower->borrowerIdentificationCard->number}}"
                                                       readonly/>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Дата выдачи</label>
                                                <input type="text" name="borrower_identification_card[issue_date]"
                                                       value="{{date('d-m-Y', strtotime($borrower->borrowerIdentificationCard->issue_date))}}"
                                                       data-date-reverse readonly/>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Срок выдачи</label>
                                                <input type="text" name="borrower_identification_card[expiration_date]"
                                                       value="{{date('d-m-Y', strtotime($borrower->borrowerIdentificationCard->expiration_date))}}"
                                                       data-date-reverse readonly/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Кем выдано</label>
                                                <select name="borrower_identification_card[issued_authority_id]"
                                                        disabled>
                                                    <option>Выберите значение</option>
                                                    @foreach ($issued_authorities as $issued_authority)
                                                        <option value="{{$issued_authority->id}}" {{ $borrower->borrowerIdentificationCard->issued_authority_id == $issued_authority->id ? "selected" : ""}}>{{$issued_authority->text}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Семейное положение</label>
                                                <select name="marital_status_id" disabled>
                                                    <option>Выберите значение</option>
                                                    @foreach ($marital_statuses as $marital_status)
                                                        <option value="{{$marital_status->id}}" {{ $borrower->marital_status_id == $marital_status->id ? "selected" : ""}}>{{$marital_status->text}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="text-xs-center edit_buttons" hidden>
                                            <button class="btn btn-2 aw">Сохранить</button>
                                            <button class="btn btn-2 aw" data-cancel-btn>Отмена</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="tab-3" class="tab">
                        <div class="row">
                            <div class="col-md-8 pull-xs-none">
                                <form name="profile_payment_requisites_settings"
                                      action="{{action("BorrowerController@updateBankAccountNumber")}}">
                                    <div class="item">
                                        <h3>Платежные реквизиты</h3>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Тип</label>
                                                <select name="">
                                                    <option>Банковский счет</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Номер</label>
                                                <input type="text" name="number"
                                                       value="{{$borrower->borrowerBankAccount ? $borrower->borrowerBankAccount->number : ""}}"
                                                       data-bank_account/>
                                            </div>
                                            <div class="col-md-4 text-xs-right">
                                                <button class="btn btn-2 aw">Изменить</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="tab-4" class="tab">
                        <div class="row">
                            <div class="col-md-8 pull-xs-none">
                                <table>
                                    <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Сумма кредита</th>
                                        <th>Дата выдачи</th>
                                        <th>Дата закрытия</th>
                                        <th>Статус</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($loans as $loan)
                                        <tr>
                                            <td class="id">{{$loan->id}}</td>
                                            <td>{{$loan->sum}} тенге.</td>
                                            <td>{{($loan->issue_date && $loan->loan_status_id != \App\Helpers\StatusHelper::REFUSED) ? date('d-m-Y', strtotime($loan->issue_date)) : "-"}}</td>
                                            <td>{{($loan->closing_date) ? date('d-m-Y', strtotime($loan->closing_date)) : "-"}}</td>
                                            <td>{{($loan->loanStatus) ? $loan->loanStatus->text : ""}}</td>
                                            <td>
                                                @if(!empty($loan->borrowerLoanAgreementDocument->pledge_agreement_file_path))
                                                    <a href="{{asset($loan->borrowerLoanAgreementDocument->pledge_agreement_file_path)}}"
                                                       target="_blank">Скачать</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="order" class="calculator" data-calc_form data-duration_agreement="30"
             data-interest_rate="{{\App\Helpers\AppHelper::getConfig('interest_rate')}}">
        <div class="container">
            <h2>Какую сумму денег Вы хотели бы получить?</h2>
            <div class="row">
                <div class="col-md-5 pull-xs-none">
                    <div class="calc" data-calc_form data-duration_agreement="30"
                         data-interest_rate="{{\App\Helpers\AppHelper::getConfig('interest_rate')}}">
                        <div class="calc-1">
                            <div class="h2">Сумма <input type="text" name="sum" value="" readonly/> Тенге</div>
                            <div id="slider"></div>
                            <div class="list-inline-item">
                                <div class="text-xs-left">10000</div>
                                <div class="text-xs-right">50000</div>
                            </div>
                            <p>срок займа 30 дней</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="result">
                <p>Сумма вознаграждения в день <span id="sumDay_overpayment">200</span> тг.</p>
                <div class="row">
                    <div class="col-xs-4 step">
                        <h3>Вы берете</h3>
                        <h2><span id="sum">10000</span> тенге</h2>
                    </div>
                    <div class="col-xs-4 step">
                        <h3>До (включительно)</h3>
                        <h2><span id="date"></span></h2>
                    </div>
                    <div class="col-xs-4 step">
                        <h3>Возвращаете</h3>
                        <h2><span id="discount" data-calculate-sum>12615</span> тенге</h2>
                    </div>
                </div>
                <div class="text-xs-center">
                    <form action="{{route("loan.addNewLoan")}}" method="POST">
                        <input type="hidden" name="duration_agreement" value="30"/>
                        <input type="hidden" name="sum" placeholder="Желаемая сумма займа"/>
                        <button type="button" class="btn btn-3 aw" data-new_loan>Оформить заем</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section class="how-it-work hidden-xs-down">
        <div class="container">
            <div class="items">
                <h2 class="h1 title"><span>Как это работает?</span></h2>
                <div class="row">
                    <div class="col-md-3">
                        <div class="item">
                            <div class="icon icon-1"></div>
                            <h3><span>Выбеите сумму</span>и срок займа</h3>
                            <p>Срок займа составляет 30 дней.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="item">
                            <div class="icon icon-2"></div>
                            <h3><span>Оформите</span>завку</h3>
                            <p>У Вас это займет не больше 10 минут.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="item">
                            <div class="icon icon-3"></div>
                            <h3><span>Ожидайте</span>ответ</h3>
                            <p>Мы дадим ответ в течение 1 минуты.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="item">
                            <div class="icon icon-4"></div>
                            <h3><span>Получите</span>Деньги</h3>
                            <p>24 часа. Visa.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="get-money hidden-xs-down">
        <div class="container">
            <h2 class="h1 title"><span>Получить онлайн заем очень просто</span></h2>
            <div class="row">
                <div class="col-md-10 pull-xs-none">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="icon icon-1"><span>1</span></div>
                            <h3>Оформите заявку</h3>
                            <p>На кредит на сайте. <b>Форма</b> для заполнения заявки на займ проста и интуитивно
                                понятна - это займет у Вас не более 15 минут</p>
                        </div>
                        <div class="col-md-4">
                            <div class="icon icon-2"><span>2</span></div>
                            <h3>Подпишите оферту</h3>
                            <p>С помощью кода, присланного в <b>СМС</b>, и получите решение по займу. Решение наша
                                система примет в течение <b>20 минут</b></p>
                        </div>
                        <div class="col-md-4">
                            <div class="icon icon-3"><span>3</span></div>
                            <h3>Получите деньги</h3>
                            <p>На Вашу карту или банковский счет. В зависимости от банка, перевод денег занимает от 1
                                часа до суток</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="need hidden-xs-down">
        <div class="container">
            <h2 class="h1 title"><span>Что нужно заемщику, чтобы получить кредит?</span></h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="item">
                        <div class="icon icon-1"></div>
                        <h3>Счет<br/>в банке</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="item">
                        <div class="icon icon-2"></div>
                        <h3>Быть<br/>Трудоустроенным</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="item">
                        <div class="icon icon-3"></div>
                        <h3>надлежащие личные <br/>документы</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="item">
                        <div class="icon icon-4"></div>
                        <h3>Контактные<br/>данные</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div data-acount-steps hidden>
    @if(isset($active_loan))
            @if($active_loan_status_id == 1)
                {{-- статус займа : 1 - заявка заполнена клиентом --}}
                <div id="step-1" class="popup">
                    <div id="preloader">
                        <div class="preloader"></div>
                    </div>
                    <h2 class="title">Пожалуйста подождите</h2>
                    <p class="h2">пока наш менеджер рассмотрит заявку.<br/>На это может потребоваться до 20 минут.</p>
                </div>
            @elseif($active_loan_status_id == 2)
                {{-- статус займа : 1 - Загрузка документов --}}
                <div id="step-2" class="popup">
                    <form action="{{action("BorrowerController@loadDocuments")}}" method="POST"
                          target="document_load_iframe" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <h2 class="title">Загрузите документы</h2>
                        <h3>для оформления займа:</h3>
                        <h4>Документы необходимые для суммы от 10 000 до 50 000 тенге</h4>
                        <!--h4>Документы необходимы для суммы до 10 000 тенге</h4-->
                        <div class="row">
                            <div class="col-md-4 pull-xs-none">
                                <div>
                                    <label class="label fake_file_input_label">
                                        @if(isset($tooltips["id_card_document_1"]))
                                            <p class="info_tooltip" data-tooltip
                                               data-tooltip-type=close>{{$tooltips["id_card_document_1"]}}</p>
                                        @endif
                                        <span>Фото удстоверения с обоих сторон</span>
                                    </label>
                                    <label class="file fake_file_input" name="id_card_document_1">
                                        <span>Загрузить переднюю сторону</span>
                                        <input type="file" name="id_card_document_1"/>
                                    </label>
                                    <label class="file fake_file_input" name="id_card_document_2">
                                        <span>Загрузить заднюю сторону</span>
                                        <input type="file" name="id_card_document_2"/>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--h4>Документы необходимые для суммы более 10 000 тенге</h4-->
                        <div class="row">
                            <div class="col-md-4 pull-xs-none">
                                <div>
                                    <label class="label fake_file_input_label">
                                        @if(isset($tooltips["address_documents[]"]))
                                            <p class="info_tooltip" data-tooltip
                                               data-tooltip-type=close>{{$tooltips["address_documents[]"]}}</p>
                                        @endif
                                        <span>Адресная справка</span>
                                    </label>
                                    <label class="file fake_file_input " name="address_documents">
                                        <span>Загрузить</span>
                                        <input type="file" name="address_documents[]" multiple/>
                                    </label>
                                </div>
                                <div>
                                    <label class="label fake_file_input_label">
                                        @if(isset($tooltips["pension_documents[]"]))
                                            <p class="info_tooltip" data-tooltip
                                               data-tooltip-type=close>{{$tooltips["pension_documents[]"]}}</p>
                                        @endif
                                        <span>Справка о пенсионных отчислениях</span>
                                    </label>
                                    <label class="file fake_file_input " name="pension_documents">
                                        <span>Загрузить</span>
                                        <input type="file" name="pension_documents[]" multiple/>
                                    </label>
                                </div>
                                <p>Либо отправьте их на электронный адрес в ответ на наше письмо. </p>
                            </div>
                        </div>
                        <div class="text-xs-center">
                            <button class="btn btn-2 aw" onclick="$('[data-preloader]').show();">Далее</button>
                        </div>
                    </form>
                </div>
            @elseif($active_loan_status_id == 3)
                <div id="step-2-3" class="popup">
                    <div id="preloader">
                        <div class="preloader"></div>
                    </div>
                    <h2 class="title">Пожалуйста подождите</h2>
                    <p class="h2">пока Ваши документы обработает менеджер.</p>
                </div>
            @elseif($active_loan_status_id == 4)

                {{--@if(empty($pledge_agreement_file_path))--}}
                <form action="{{route("loan.sendPledgeAgreement")}}" method="POST" id="pledge_agreement_form"></form>
                {{--@endif--}}

                <div id="step-4" class="popup">
                    <form action="{{action("LoanController@loadAgreementDocuments")}}" method="POST"
                          target="document_load_iframe" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-md-8 pull-xs-none">
                                <h2 class="title text-xs-center">Подпишите договор</h2>
                            </div>
                        </div>
                        <div class="row icon-steps">
                            <div class="col-md-10 pull-xs-none">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="icon icon-1"></div>
                                        <h3 class="title text-xs-center">Мы отправили Ваш договор на email</h3>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="icon icon-2"></div>
                                        <h3 class="title text-xs-center">Распечатайте его и подпишите</h3>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="icon icon-3"></div>
                                        <h3 class="title text-xs-center">Загрузите в форму ниже или отправьте на
                                            application@avansplus.kz</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 m-y-3 pull-xs-none text-xs-center">
                                <button data-url="{{(!empty($pledge_agreement_file_path) ? asset($pledge_agreement_file_path) : "")}}"
                                        id="pledge_agreement_file_download_btn" class="btn btn-2 aw">Распечатать договор
                                </button>
                            </div>
                        </div>
                        <!--div class="row">
                            <div class="col-md-8 pull-xs-none">
                                <h3 class="h2 title text-xs-center">Подпишите договор</h3>
                                <h3 class="text-xs-center">Получите и подпишите для дальнейшей загрузки в Кабинет:</h3>
                                <h2 class="title"><a href="#" title="" class="print">Распечатать договор для подписания</a>
                                </h2>
                                <h2 class="title"><span class="tomail">Мы отправили Ваш договор на email.</span></h2>
                                <h2 class="title"><span class="pen">Пожалуйста подпишите его</span></h2>
                                <h3 class="title text-xs-center">Загрузите подписанный вами договор<a
                                            href="http://avanse.puzzle.kz/img/example.png" title="Образец для подписания"
                                            class="example" target="blanc">?</a></h3>
                                <p class="h3">(скан/pdf)</p>
                            </div>
                        </div-->
                        <div class="row">
                            <div class="col-md-4 pull-xs-none">
                                <label class="label fake_file_input_label">
                                    <span>Загрузите подписанный вами договор</span>
                                </label>
                                <label class="file fake_file_input" name="agreement_documents">
                                    <span>Загрузить</span>
                                    <input type="file" name="agreement_documents[]" multiple/>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 pull-xs-none">
                                <div class="contract">
                                    <div class="dogovor_item" unselectable="on" onmousedown="return false;"
                                         onselectstart="return false;" oncontextmenu="return false;"
                                         ondragstart="return false;" ondblclick="return false;" marginwidth="0"
                                         marginheight="0">
                                        {!! view('docs.pdf.pledge_agreement', ["loan" => $active_loan]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-xs-center">
                            <button class="btn btn-2 aw" onclick="$('[data-preloader]').show();">Далее</button>
                        </div>
                    </form>
                </div>
                @if(!$errors)
                    <div id="step-3" class="popup">
                        <h2 class="title">Ваша заявка предварительно одобрена!</h2>
                        <p class="h2">Ваша заявка предварительно одобрена!<br/>Мы можем предоставить для вас заём<br/>в
                            размере {{$active_loan->roundCounterofferSum()}} тенге
                            на {{$active_loan->counteroffer_duration_agreement}} дней.<br/>Пожалуйста, подтвердите своё
                            согласие.</p>
                        <div class="row">
                        </div>
                        <div class="text-xs-center">
                            <form action="{{route("loan.refuse")}}" method="POST"></form>
                            <button type="button" class="btn btn-2 aw btn-gray" data-refuse_loan>отменить</button>
                            <button type="button" class="btn btn-2 aw" data-send-pledge_agreement-btn data-popup>Далее
                            </button>
                        </div>
                    </div>
                @endif
            @elseif($active_loan_status_id == 7)
                <div id="step-4-5" class="popup">
                    <div id="preloader">
                        <div class="preloader"></div>
                    </div>
                    <h2 class="title">Ваш договор обрабатывается</h2>
                </div>
            @elseif($active_loan_status_id == 8)
                <div id="step-5" class="popup">
                    <form action="{{route("loan.sendConfirmationCode")}}" method="POST">
                        <h2 class="title">Подписание СМС кодом</h2>
                        <div class="text-xs-center">
                            <button type="button" class="btn btn-1 aw" data-send_confirmation_code>Получить код
                                подтверждения
                            </button>
                        </div>
                    </form>
                    <form action="{{route("loan.checkConfirmationCode")}}" method="POST" id="loan_confirmation_form"
                          hidden>
                        <h2 class="title">Подписание СМС кодом</h2>
                        <p class="h2">На Ваш номер телефона отправлен одноразовый смс-код для подтверждения
                            телефона.<br/>Код
                            будет действителен в течение 2х часов.<br/>Введя код, вы подтверждаете свое согласие на
                            условия
                            оферты.</p>
                        <div class="row">
                            <div class="col-md-3 pull-xs-none">
                                <input type="text" name="code" placeholder="_&nbsp;_&nbsp;_&nbsp;_" maxlength="4" autocomplete="off"/>
                                <a>Получить код еще раз</a>
                            </div>
                        </div>
                        <div class="text-xs-center m-t-1">
                            <button class="btn btn-2 aw" data-check_confirmation_code>Далее</button>
                        </div>
                    </form>
                </div>
            @elseif($active_loan_status_id == 9)
                <div id="step-6" class="popup">
                    <button class="mfp-close" style="color: #000;">&times;</button>
                    <h2 class="title">Ваш займ одобрен</h2>
                    <p class="h2">вы получите деньги на свой расчетный счет
                        KZ {{$active_loan->borrower->borrowerBankAccount->number}} в течении 4 часов. Статус своего
                        займа вы
                        можете отслеживать в личном кабинете. Воспользуйтесь нашим сервисом интернет платежей для
                        погашения
                        займа. Вы всегда можете продлить свой заём через личный кабинет.</p>
                </div>
            @endif

            @if($errors)
                @foreach($errors as $key => $error)
                    <div id="error-1" class="alert-win container danger">
                        <button class="mfp-close" style="width: auto;">&times;&nbsp;<span class="data-mfp-close-trigger"
                                                                                          style="font-size: 14pt; vertical-align: bottom;">закрыть</span>
                        </button>
                        <h2>{{$error}}</h2>
                    </div>
                    {{-- <div class="alert-win container success">
                        <button>&times;</button>
                        <h2>Ваша заявка принята</h2>
                    </div> --}}
                @endforeach
            @endif



            <iframe src="" name="document_load_iframe" data-iframe-loader hidden></iframe>




        {{-- Если статус займа == новая заявка, то запускаем слушатель обновления статуса --}}
        @if($active_loan_status_category_id == \App\Helpers\StatusHelper::CATEGORY_NEW)
@section('jssection')
    <script src="{{asset("js/modular_scripts/refrash_account_page.js")}}"
            data-loan-status-id="{{$active_loan_status_id}}"
            data-route-url="{{route("loan.active.checkStatus")}}"></script>
@endsection
@endif
@endif


        @if(!empty($borrower_loan_notifications) && is_array($borrower_loan_notifications))
            @foreach($borrower_loan_notifications as $borrower_loan_notification)
                <div id="error-1" class="alert-win container danger">
                    <button class="mfp-close" style="width: auto;">&times;&nbsp;<span class="data-mfp-close-trigger"
                                                                                      style="font-size: 14pt; vertical-align: bottom;">закрыть</span>
                    </button>
                    <h2>{{$borrower_loan_notification["message"]}}</h2>
                </div>
            @endforeach
            {{\App\Model\BorrowerLoanNotification::where("id", $borrower_loan_notification["id"])->update(["is_viewed" => 1])}}
        @endif

    </div>
    <div hidden>
        <div id="accept_profile_data" class="popup default_popup_width" >
            <button class="mfp-close" style="color: #000;">&times;</button>
            <h2 class="title">Изменились ли ваши личные данные?</h2>

                <div class="text-xs-center m-t-1">
                    <a href="#change-data" title="" class="btn btn-2 fw" data-popup="" style="display: inline-block;    width: 80px;">Да</a>
                    <button class="btn btn-2 aw" data-add_new_loan_btn>Нет</button>
                </div>

        </div>
        <div id="change-data" class="popup"  >
            <button class="mfp-close" style="color: #000;">&times;</button>
            <h2 class="title">Пожалуйста поменяйте ваши данные в настройках профиля кабинета на актуальные и попробуйте еще раз</h2>
        </div>
        @if($borrower->is_banned == 1)
            <div id="is_banned_popup" class="popup danger middle_popup_width" is_banned>
            <button class="mfp-close" style="color: #000;">&times;</button>
            <h2 class="title">Извините, мы не можем предоставить вам займ, <br>попробуйте снова после {{date("d-m-Y", strtotime($borrower->unlock_date))}}</h2>
        </div>
        @endif
        {{-- <div id="repeat-order" class="popup">
            <button class="mfp-close" style="color: #000;">&times;</button>
            <h2 class="title">Сумма займа</h2>
            <p class="h2">(ввод суммы до 50 000 тг)</p>
            <form>
                <input type="text" name="" placeholder="Введите сумму" />
                <div class="text-xs-center m-t-1">
                    <button class="btn btn-2 aw">Далее</button>
                </div>
            </form>
        </div> --}}
    </div>
