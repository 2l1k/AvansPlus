@extends('layouts.app')

@section('content')
    @component('components.common.header_menu')
    @endcomponent

@section('jssection')
    <script src="https://widget.cloudpayments.kz/bundles/cloudpayments"></script>
    <script>
        //note: в примере используется библиотека jquery

        this.pay = function () {
            var widget = new cp.CloudPayments();
            widget.charge({ // options
                    publicId: '{{\App\Helpers\AppHelper::getConfig("cloudpayments_public_id")}}', //id из личного кабинета
                    description: 'Продление займа №{{$active_loan->loan_id}}', //назначение
                    amount: {{$payment_order->order_sum}}, //сумма
                    currency: 'KZT', //валюта
                    invoiceId: '{{$payment_order->id}}', //номер заказа  (необязательно)
                    accountId: '{{$borrower->phone_number}}', //идентификатор плательщика (необязательно)
                    data: {
                        payment_type: 'extend' //произвольный набор параметров
                    }
                },
                function (options) { // success

                    //при успешной оплате и закрытии попапа редиректим на страницу займа в личный кабинет
                    callbacks = {
                        close: function () {
                            window.location.href = "{{route("account.index")}}";
                        }
                    };
                    showAlert("Платёж принят в обработку", "success", callbacks);
                },
                function (reason, options) { // fail
                    showAlert("Платёж не удался", "error");
                });
        };

        $(function () {
            $('#checkout').click(pay);

            $("[data-extend-step2-btn]").click(function(e){
                e.preventDefault();
                $(this).closest(".prolongation").hide();
                $(".repayment[hidden]").removeAttr("hidden", "");
            });

        })
    </script>
@endsection

<section class="prolongation">
    <div class="container">
        <h1 class="title"><span>Личный кабинет</span></h1>
        <h3>Продление кредита №{{$active_loan->loan_id}}</h3>
        <form id="prolongation">
            <div class="row">
                <div class="col-md-8 pull-xs-none">
                    <h3 class="text-xs-center m-b-2">На сколько вы хотите продлить займ?</h3>
                    <div class="row">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            <label>
                                <input type="radio" name="prolongation" value="{{$active_loan->duration_actual}}"/>
                                <div class="caption"  data-extend-step2-btn>
                                    <div class="icon icon-2"></div>
                                    <h3>Продлить<span>на {{$active_loan->duration_actual}}
                                            дней за {{$active_loan->amountExtension()}} тенге</span></h3>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-xs-center">
                <a class="btn btn-2 aw" href="{{route("account.index")}}">Назад</a>
                <button class="btn btn-2 aw" data-extend-step2-btn>Далее</button>
            </div>
        </form>
    </div>
</section>

<section class="repayment" hidden>
    <div class="container">
        <h1 class="title"><span>Личный кабинет</span></h1>
        <h3>Продление кредита №{{$active_loan->loan_id}}</h3>
        <form id="repayment">
            <div class="row">
                <div class="col-md-8 pull-xs-none">
                    <label class="label"><b>Сумма оплаты:</b></label>
                    <div class="row">
                        <div class="col-md-4"><input type="text" name="" value="{{$payment_order->order_sum}} тенге."
                                                     readonly/></div>
                        <div class="col-md-4">
                            <button type="button" id="checkout" class="btn btn-2 aw" disabled>Продлить</button>
                        </div>
                        <div class="col-md-4"><a href="{{route("loan.repayment")}}" title="" class="btn btn-2 aw">Погасить
                                займ</a></div>
                    </div>
                    <label class="label"><b>Выберите способ оплаты:</b></label>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="payment">
                                <input type="radio" name="payment" value="visa/mastercard"/>
                                <img src="{{asset('img/card.png')}}" alt=""/>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label href="#qiwi_instruction" class="payment" data-popup onclick="window.open('https://qiwi.com/payment/form/34577/')">
                                <input type="radio" name="payment" value="qiwi"/>
                                <img src="{{asset('img/qiwi.png')}}" alt=""/>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-xs-center">
                <a href="{{route("account.index")}}" title="" class="btn btn-2 aw">Назад</a>
            </div>
        </form>
    </div>
    <div hidden>
        <div id="qiwi_instruction" class="popup container">
            <button class="mfp-close" style="color: #000;">&times;</button>
            <div class="text-xs-center">
				<h2 class="title">Интсрукция</h2>
				<p>Погашения займа через терминал Qiwi</p>
				<a href="{{asset('img/instruction.png')}}" download="qiwi_instruction.png" class="btn btn-2 aw">Сохранить инструкцию</a>
            </div>
            <div class="text-xs-center">
				<img src="{{asset('img/instruction.png')}}" alt="" class="m-y-3"/>
            </div>
            <div class="text-xs-center">
				<button class="btn btn-2 aw" data-close>Закрыть</button>
				<a href="{{asset('img/instruction.png')}}" download="qiwi_instruction.png"  class="btn btn-2 aw">Сохранить инструкцию</a>
            </div>
        </div>
    </div>
</section>
@component("components.section.get_money")
@endcomponent
@component("components.section.need")
@endcomponent
@endsection
