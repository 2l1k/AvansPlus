function loan() {

}

$(function () {

    $("[data-borrower_register]").click(function (e) {
        e.preventDefault();
        submitFormWithAjax(this, function (json) {``
            jsonHandler(json, status);

            if(json.popup_error){
                showPopup("#account_exists");
            }

            if (json.status == 1 || json.status == 0) {
                showPopup("#sms_popup");
            }
        })
    });

    //При нажатии на кнопку авторизации в форме существующего аккаунта, показываем форму входа
    $("[data-show_login_form]").click(function (e) {
        e.preventDefault();
        // $.magnificPopup.instance.close = function () {
        //     showPopup('#login');
        // };
        setTimeout(function () {
            showPopup('#login');
        }, 200);
        $.magnificPopup.instance.close();
    });


    var sms_popup_confirm_code = 0;
    $("#sms_popup [name=code]").keyup(function () {
        var code = $(this).val();
        if (code.length == 4 && code != sms_popup_confirm_code) {
            submitFormWithAjax(this, function (json) {
                jsonHandler(json, status);
            });
        }
    });

    $("[data-borrower_forget_password]").click(function (e) {
        e.preventDefault();
        $form = $(this).closest("form");
        data = $form.serialize();
        action = $form.attr("action");


        submitFormWithAjax(this, function (json) {
            jsonHandler(json, status);
            if (json.status == 1) {
                showPopup("#recovery_password");
            }
        });

    });

    $("[data-borrower_add_new_password]").click(function (e) {
        e.preventDefault();
        $form = $(this).closest("form");
        data = $form.serialize();
        action = $form.attr("action");

        $.post(action, data, function (json) {
            jsonHandler(json, status);
            if (json.status == 1) {
                showPopup("#success-recovery");
            }
        })
    });

    $("[data-login]").click(function (e) {
        e.preventDefault();
        submitFormWithAjax(this, function (json) {
            jsonHandler(json, status);
        })
    });

});

$(function () {

    //Подтверждение заявки
    $("[data-send_confirmation_code]").click(function (e) {
        e.preventDefault();
        $form = $(this).closest("form");
        data = $form.serialize();
        action = $form.attr("action");

        $form.hide();
        $(this).closest(".popup").find("[hidden]").prop("hidden", false);

        $.post(action, data, function (json) {
            jsonHandler(json, status);
        })
    });


    //при вводе кода подтверждения с клавиатуры
    var loan_confirmation_form_confirm_code = 0;
    $("#loan_confirmation_form [name=code]").keyup(function () {
        var code = $(this).val();
        if (code.length == 4 && loan_confirmation_form_confirm_code != code) {
            confirm_code = code;
            submitFormWithAjax(this, function (json) {
                jsonHandler(json, status);
            })
        }
    });

    //при клике на кнопку далее
    $("[data-check_confirmation_code]").click(function (e) {
        e.preventDefault();
        var code = $("#loan_confirmation_form [name=code]").val();
        if (code.length == 4) {
            submitFormWithAjax(this, function (json) {
                jsonHandler(json, status);
            })
        }
    });

});


// ACCOUNT
$(function () {

    var count_popups = 0;
    var last_popup = "";

    //Показываем все попапы, из верстки

    var popup_length = $('[data-acount-steps] > div').length; //количество попапов для отображения

    $('[data-acount-steps] > div').each(function () {
        var callbacks = {};
        //Если текущий попап не является последним в наборе,
        if (++count_popups != popup_length) {
            //стоит отметить, что в HTML ошибки выводятся в конце
            if (count_popups == 1) {
                last_popup = $(this).attr("id");
            }
            callbacks = {
                open: function () {
                    $.magnificPopup.instance.close = function () {
                        $.magnificPopup.proto.close.call(this);
                        showPopup($("#" + last_popup));
                    }
                }
            };
        }


        showPopup($(this), callbacks);
    });

    $('[name=profile_settings], [name=profile_payment_requisites_settings]').submit(function (e) {
        e.preventDefault();
        $form = $(this);
        data = $form.serialize();
        action = $form.attr("action");

        $.post(action, data, function (json) {
            jsonHandler(json, status);
        })
    });

    $('[name=profile_settings] [data-cancel-btn]').click(function (e) {
        e.preventDefault();
        $("[name=profile_settings] [type=text]").prop("readonly", true);
        $("[name=profile_settings] select").prop("disabled", true);
        $("[name=profile_settings] .edit_buttons").prop("hidden", true);
    });


    //Статус отправки догвоора на этапе подписания договора. Если договор не был отправлен ранее - отправляем

    //При клике на "Далее" в форме встречного предложения
    $("[data-send-pledge_agreement-btn]").click(function (e) {
        e.preventDefault();
        if ($('#pledge_agreement_form').length > 0) {
            //Формируем договор и отправляем на почту


            submitFormWithAjax($('#pledge_agreement_form').eq(0), function (json) {
                jsonHandler(json, status);

                if(json.pledge_agreement_html){
                    $(".dogovor_item").html(json.pledge_agreement_html);
                }

                $("[data-preloader]").hide();
                $.magnificPopup.instance.close();
                showPopup("#step-4");
            })

        }
    });

    //При клике на "распечатать договор", открываем в новой вкладке
    $("#pledge_agreement_file_download_btn").click(function (e) {
        e.preventDefault();
        var data_url = $(this).attr("data-url");
        window.open(data_url, '_blank');
    });


    //При новой заявке на займ
    $("[data-add_new_loan_btn]").click(function (e) {
        e.preventDefault();
        submitFormWithAjax($("[data-new_loan]"), function (json) {
            jsonHandler(json, status);
            $.magnificPopup.instance.close();
        })
    });


    //При отказе от встречного предложения
    $("[data-refuse_loan]").click(function (e) {
        e.preventDefault();
        submitFormWithAjax($(this).parent().find("form").eq(0), function (json) {
            jsonHandler(json, status);
        })
    });


    //При отказе от встречного предложения
    $("[data-new_loan]").click(function (e) {
        e.preventDefault();
        sum = $("[data-calc_form] [name=sum]").val();
        if($("[is_banned]").length > 0){
            showPopup('#is_banned_popup');
        }else{
            showPopup('#accept_profile_data');
        }
    });

});