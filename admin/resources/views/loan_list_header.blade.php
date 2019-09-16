<div class="row">
    <div class="col-md-6">

        <form action="{{$loan_forecast_action}}" method="GET" class="loan_forecast_form">
            <input type="hidden" name="ids" value="">
            <div class="loan_forecast_form_container" hidden>
                <h3>Прогноз по займам</h3><br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-date form-group input-group">
                            <label for="lf_days" class="control-label"> </label>
                            <input data-date-format="DD.MM.YYYY" data-date-useseconds="false" type="text" name="lf_days"
                                   placeholder="Выберите дату" class="form-control column-filter">
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                        </div>

                        <div class="form-group form-element-button ">
                            <input type="submit" class="btn btn-primary pull-left" value="Рассчитать">
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>
            <br>
            <input id="show_loan_forecast_form" type="button" class="btn btn-primary pull-left" value="Прогноз по займам">
            <br>
        </form>

        <form action="{{route("admin.loans.forecast.toExcel")}}" method="GET" class="loan_forecast_form_toExcel">
            <input type="hidden" value="" name="lf_days">
            <input type="hidden" name="ids" value="">
            <input type="submit" class="btn btn-primary pull-left" value="Экспорт в Excel">
        </form>

    </div>
    <div class="col-md-6">

        <form action="{{route('admin.loans.export_executive_inscription.word')}}" method="POST"
              class="export_executive_inscription">
            <input type="hidden" name="ids" value="">
            <div class="notary_data_container" hidden>
                <h3>Форма экспорта исполнительных надписей</h3><br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-element-text ">
                            <label for="notary_data[fio]" class="control-label">Нотариусу</label>
                            <input type="text" id="notary_data[fio]" name="notary_data[fio]"
                                   value="г. Алматы Абдрахмановой Ш." class="form-control">
                        </div>

                        <div class="form-group form-element-text ">
                            <label for="notary_data[address]" class="control-label">Адрес</label>
                            <input type="text" id="notary_data[address]" name="notary_data[address]"
                                   value="г.Алматы, Казбек би, 50, оф.28" class="form-control">
                        </div>

                        <div class="form-group form-element-button ">
                            <input type="submit" class="btn btn-primary pull-right" value="Экспортировать">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-element-text ">
                            <label for="notary_data[from]" class="control-label">От</label>
                            <textarea rows="10" type="text" id="notary_data[from]" name="notary_data[from]"
                                      class="form-control">От ТОО «Ломбард Аванс Плюс КЗ»
БИН: 170340000816,.
Юр. адрес: г.Алматы, Айтеке би 134/1, оф 50
В лице директора Волкова Алексея Геннадье-вича, 28.11.1982 года рождения, уроженца Алматинской области, ИИН821128300515.
Банковские реквизиты:
ИИК: KZ029261802193774000
Банк: АО «Казкоммерцбанк»
БИК: KZKOKZKX,
КБЕ: 15, ОКЭД: 65230
Тел.+7 776-222-55-66</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <input id="show_form" type="button" class="btn btn-primary pull-right"
                   value="Экспорт исполнительных надписей">
            <br>
        </form>
    </div>
</div>
<iframe id="download_iframe" style="display:none;"></iframe>