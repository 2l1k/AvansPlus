$(function () {

    $("[data-date]").inputmask("99.99.9999",{ "placeholder": "дд.мм.гггг" });

    //Экспорт нотариальных документов на странице займов
    $export_form = $("form.export_executive_inscription");
    $export_form.find("#show_form").click(function (e) {
        e.preventDefault();
        $(this).hide();
        if (!$(".notary_data_container").is(":visible")) {
            $(".notary_data_container").show();
        }
    });
    $export_form.submit(function (e) {
        e.preventDefault();
        $form = $(this);
        var action = $form.attr("action");
        var ids = $('[name="_id[]"]:checked').map(function () {
            return this.value;
        }).get().join(',');
        $form.find("[name=ids]").val(ids);

        $.post(action, $form.serialize(), function (json) {
            if (json.file_path) {
                $("#download_iframe").attr("src", '/' + json.file_path);
            }
        });
    });

    //Прогноз по займам
    $loan_forecast_form = $("form.loan_forecast_form");
    $loan_forecast_form_toExcel = $("form.loan_forecast_form_toExcel");

    $loan_forecast_form.find("#show_loan_forecast_form").click(function (e) {
        e.preventDefault();
        $(this).hide();
        if (!$(".loan_forecast_form_container").is(":visible")) {
            $(".loan_forecast_form_container").show();
            $loan_forecast_form_toExcel.show();
        }
    });

    $loan_forecast_form.find("[type=submit]").click(function (e) {
        e.preventDefault();
        $form = $(this).closest("form");
        var action = $form.attr("action");
        var ids = $('[name="_id[]"]:checked').map(function () {
            return this.value;
        }).get().join(',');
        $form.find("[name=ids]").val(ids);

        $form.submit();
    });


//Экспорт в CSV заявок из прогноза по займам
    $loan_forecast_form_toExcel.find("[type=submit]").click(function (e) {
        e.preventDefault();
        $form = $(this).closest("form");

        $form.find("[name=ids]").val($loan_forecast_form.find("[name=ids]").val());
        $form.find("[name=lf_days]").val($loan_forecast_form.find("[name=lf_days]").val());

        $form.submit();
    });


    //Таблица прогноза по займам

    $forecast_table = $("table.forecast_table");
    var total_sum_by_issued = 0;
    var total_sum_maturity = 0;
    var total_net_profit = 0;
    $forecast_table.find(" tbody tr").each(function () {
        total_sum_maturity += parseInt($(this).find("div[amount_maturity]").text()); //сумма к возврату
        total_sum_by_issued += parseInt($(this).find("div[sum]").text()); //сумма выдачи
        total_net_profit += parseInt($(this).find("div[net_profit]").text()); //чистая прибыль
    });
    $forecast_table.find("tfoot").append("<tr>" +
        "<td></td>" +
        "<td></td>" +
        "<td></td>" +
        "<td></td>" +
        "<td></td>" +
        "<td></td>" +
        "<td ></td>" +
        "<td ></td>" +
        "<td>"+ total_sum_by_issued +"</td>" +
        "<td>"+ total_sum_maturity +"</td>" +
        "<td>"+ total_net_profit +"</td>" +
        "<td></td>" +
        "<td></td>" +
        "<tr>");




    //При выборе готового варианта ответа
    $(".ready_answer").change(function(){
        var answer = $(this).val();
        $(this).closest(".form-elements").find("textarea").val(answer);
    });


});
