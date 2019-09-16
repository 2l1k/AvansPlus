@extends('layouts.app')

@section('content')
    <section class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-11 pull-xs-none">
                    <div class="row">
                        <div itemscope itemtype="http://schema.org/Product" class="col-md-7">
                            <h1 itemprop="name"><span>О</span>нлайн займы</h1>
                            <h2 itemprop="description" class="before-line">Мы выдаем деньги, когда другие отказывают</h2>
                            <p itemprop="text">Если вы срочно нуждаетесь в займе наличными, <br/>то получите его прямо сейчас.</p>
                            <a itemprop="url" href="#order" title="Получить займ" class="btn btn-2 aw main-size" data-scroll>Получить займ</a>
                        </div>
                        <div class="col-md-5">
                            <div class="calc hidden-xs" data-calc_form data-duration_agreement="30" data-interest_rate="{{\App\Helpers\AppHelper::getConfig('interest_rate')}}" data-guarantor_rate="{{\App\Helpers\AppHelper::getConfig('guarantor_rate')}}">
                                <div class="calc-1">
                                    <div class="h2">Сумма <input type="text" name="sum" value="" readonly /> Тенге</div>
                                    <div id="slider"></div>
                                    <div class="list-inline-item">
                                        <div class="text-xs-left">10000</div>
                                        <div class="text-xs-right">50000</div>
                                    </div>
                                </div>
                                <div class="calc-2">
                                    <h3>Минимальный <br/>и максимальный</h3>
                                    <h2>срок займа 30 дней</h2>
                                    <table>
                                        <tr>
                                            <td>Вы берете:</td>
                                            <td><span id="sum">10000</span> тенге</td>
                                        </tr>
                                        <tr>
                                            <td>Дата погашения:</td>
                                            <td><span id="date"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Вы возвращаете:</td>
                                            <td><span id="sum2">0</span> тенге</td>
                                        </tr>
                                        <!--tr>
                                            <td colspan="2" style="color: #000; padding-top: 1rem;">Дополнииельные услуги</td>
                                        </tr>
                                        <tr>
                                            <td>Услуги третьих лиц:</td>
                                            <td><span data-sum_third>0</span> тенге</td>
                                        </tr-->
                                    </table>
                                </div>
                                <a href="#order" class="btn btn-2 aw" data-scroll>Получить</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="get-money hidden-xs-down">
        <div class="container">
            <h2 class="h1 title"><span>Как получить деньги?</span></h2>
            <div class="row">
                <div class="col-md-10 pull-xs-none">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="icon icon-1"><span>1</span></div>
                            <h3>Заполните заявку</h3>
                            <p>Процесс занимает всего 10 минут. При повторном обращении Вам нужно будет сделать всего несколько кликов!</p>
                        </div>
                        <div class="col-md-4">
                            <div class="icon icon-2"><span>2</span></div>
                            <h3>Получите решение</h3>
                            <p>Наша система работает очень быстро. Вы получите уведомление о нашем решении на своей email.</p>
                        </div>
                        <div class="col-md-4">
                            <div class="icon icon-3"><span>3</span></div>
                            <h3>Получите деньги</h3>
                            <p>При положительном решении мы мгновенно отправляем деньги на счет - используйте их как пожелаете.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="terms hidden-xs-down">
        <div class="container">
            <h2 class="h1 title"><span>Все, что от вас нужно</span></h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="item">
                        <div class="icon icon-1"></div>
                        <h3>Счет <br/>в банке</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="item">
                        <div class="icon icon-2"></div>
                        <h3>Быть <br/>Трудоустроенным</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="item">
                        <div class="icon icon-3"></div>
                        <h3>Надлежащие личные <br/>документы</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="item">
                        <div class="icon icon-4"></div>
                        <h3>Контактные <br/>данные</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="order" class="order">
        <div class="container">
            <h5><button class="open btn btn-3 aw">Оставить заявку</button></h5>
        </div>
    </section>
    <section class="form registration_form">
        <div class="container">
            <div class="row">
                <div class="col-md-10 pull-xs-none">
                    <button class="close">Свернуть</button>
                    <h2 class="title">Пожалуйста, заполните все поля</h2>
                    <form action="{{action("BorrowerController@store")}}" method="POST">
                        <input type="hidden" name="duration_agreement" value="30" />
                        <h3 class="icon icon-1"><span>Имя и контактные данные</span></h3>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="IIN" placeholder="ИИН" data-IIN_input />
                                <input type="text" name="phone_number" placeholder="Телефон" data-phone_input />
                                <input type="text" name="email" placeholder="E-mail" />
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="lastname" placeholder="Фамилия" />
                                <input type="text" name="firstname" placeholder="Имя" />
                                <input type="text" name="fathername" placeholder="Отчество" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <input type="text" name="place_of_residence" placeholder="Адрес прописки" />
                            </div>
                        </div>
                        <h3 class="icon icon-2"><span>Трудоустройство</span></h3>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="work_place" placeholder="Место работы" />
                                <input type="text" name="working_position" placeholder="Должность" />
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="salary" placeholder="Размер зароботанной платы" />
                                <select name="salary_obtaining_method_id">
                                    <option>Способ получения зарплаты</option>
                                    @foreach($salary_obtaining_methods as $salary_obtaining_method)
                                        <option value="{{$salary_obtaining_method->id}}">{{$salary_obtaining_method->text}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h3 class="icon icon-3"><span>Финансы</span></h3>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="sum" placeholder="Желаемая сумма займа" />
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="bank_account_number" placeholder="Номер счета" data-bank_account />
                            </div>
                        </div>
                        <button type="button" class="btn btn-3 aw" data-borrower_register>Отправить заявку на заем</button>
                    </form>
                    <button class="close">Свернуть</button>
                </div>
            </div>
        </div>
    </section>
    <section class="advantages hidden-xs-down">
        <div class="container">
            <h2 class="h1 title"><span>НАШИ ПРЕИМУЩЕСТВА</span></h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="icon icon-1"></div>
                    <h3><span>100%</span>ОНЛАЙН</h3>
                    <p>Нужен только доступ в интернет, мобильный телефон и действующая банковская карточка.</p>
                </div>
                <div class="col-md-3">
                    <div class="icon icon-2"></div>
                    <h3><span>30</span>МИНУТ</h3>
                    <p>Заполняйте заявку онлайн и получайте деньги на банковскую карту.</p>
                </div>
                <div class="col-md-3">
                    <div class="icon icon-3"></div>
                    <h3><span>ГИБКИЕ</span>УСЛОВИЯ</h3>
                    <p>Выбирайте сумму займа и срок возврата займа.</p>
                </div>
                <div class="col-md-3">
                    <div class="icon icon-4"></div>
                    <h3><span>ЛУЧШИЕ</span>ОТНОШЕНИЯ</h3>
                    <p>Забудьте о неудобных ситуациях. Просить у друзей и родственников больше не надо.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="reviews hidden-xs-down">
        <div class="container">
            <h2 class="h1 title"><span>Отзывы клиентов</span></h2>
            <div class="carousel">
                <div itemprop="review" itemscope itemtype="http://schema.org/Review">
                    <p itemprop="description">Иногда пользуюсь услугой онлайн займа. Нет скрытых комиссий, все прозрачно, выручает когда нужны деньги до зарплаты!</p>
                    <h3 itemprop="author">Дадамбаева Бану</h3>
                </div>
                <div itemprop="review" itemscope itemtype="http://schema.org/Review">
                    <p itemprop="description">Хорошо, что в Казахстане появилась такая услуга! Не хватало денег на свадьбу сыну, оперативно получила деньги. Спасибо за сервис!</p>
                    <h3 itemprop="author">Нурмуганбет Жанар</h3>
                </div>
                <div itemprop="review" itemscope itemtype="http://schema.org/Review">
                    <p itemprop="description">Иногда пользуюсь услугой онлайн займа. Нет скрытых комиссий, все прозрачно, выручает когда нужны деньги до зарплаты!</p>
                    <h3 itemprop="author">Дадамбаева Бану</h3>
                </div>
            </div>
        </div>
    </section>
    <section class="requires">
        <div class="container">
            <h2 class="h1 title"><span>УСЛОВИЯ ДЛЯ ПОЛУЧЕНИЕ ДЕНЕГ</span></h2>
            <div class="row">
                <div class="col-md-10 pull-xs-none">
                    <div class="icon icon-1">
                        <h2>Срок займа и ставка вознаграждения</h2>
                        <h3>Максимальная ставка вознаграждения 2% в день , Срок займа - 30 дней.</h3>
                    </div>
                    <div class="icon icon-2">
                        <h2>Финансовая ответственность (штрафы и проценты)</h2>
                        <h3>В случае нарушения установленного срока платежа по займу, заемщик будет обязан оплатить сумму судебных издержек, которые складываются из суммы (10 МРП) и суммы государственной пошлины и других сборов третьих лиц.</h3>
                    </div>
                    <div class="icon icon-3">
                        <h2>Методы взыскания задолженности</h2>
                        <h3>В случае просрочки, компания будет применять все законные средства взыскания задолженности, включая официальные коллекторские агентства или судебное взыскание.</h3>
                    </div>
                    <div class="icon icon-4">
                        <h2>Возможное влияние на рейтинг кредитоспособности клиента</h2>
                        <h3>В случае просрочки, Компания будет передавать данные о сумме и сроке просрочки в кредитные бюро, что может повлиять на кредитную историю и кредитный рейтинг заемщика.</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
