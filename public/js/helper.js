$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf"]').attr('content')
    }
});

Date.prototype.mmddyyyy = function() {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();

    return [(dd>9 ? '' : '0') + dd,
        (mm>9 ? '' : '0') + mm,
        this.getFullYear()

    ].join('.');
};

$(function(){
    $("[data-bank_account]").inputmask("KZ******************");
    $("[data-IIN_input]").inputmask("999999999999");
    $("[data-phone_input]").inputmask("+7 (999) 999-99-99");
    $("[data-date]").inputmask("9999-99-99",{ "placeholder": "гггг-мм-дд" });
    $("[data-date-reverse]").inputmask("99-99-9999",{ "placeholder": "дд-мм-гггг" });

    $("[data-iframe-loader]").load(function(){
        $("[data-preloader]").hide();
        json_text = $(this).contents().text();
        if(json_text){
            json = $.parseJSON(json_text);
            if(json){
                jsonHandler(json);
            }
        }
    });

    //При клике на прелоадер скрываем его
    $("[data-preloader]").click(function (e) {
       $(this).hide();
    });

    $("body").delegate(".close_popup", "click", function (e) {
        e.preventDefault();
        $.magnificPopup.instance.close();
        //$('.mfp-close').trigger('click');
    });

    //Показываем тултипы после загрузки страницы
    $("[data-tooltip]").show();
    $("[data-tooltip]").parent().attr("has-tooltip", true);
    $("[data-tooltip]").parent().click(function(){
        $(this).find("[data-tooltip]").toggle();
    });
    // $("[data-tooltip]").click(function(){
    //     $(this).hide();
    // });

    $(".data-mfp-close-trigger").click(function (e) {
        e.preventDefault();
        $.magnificPopup.instance.close();
    });

    //При нажатии enter - запрещаем отправку формы
    $("form").on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
});



function showNoty(message, type, time, killer) {
    if (time) {} else {
        time = 5000
    }
    if (typeof killer !== 'undefined') {} else {
        killer = false
    }
    new Noty({
        type: type,
        layout: 'topRight',
        theme: 'metroui',
        text: message,
        timeout: time,
        progressBar: true,
        closeWith: ['click', 'button'],
        animation: {
            open: 'noty_effects_open',
            close: 'noty_effects_close'
        },
        id: false,
        force: false,
        killer: killer,
        queue: 'global',
        container: false,
        buttons: [],
        sounds: {
            sources: [],
            volume: 1,
            conditions: []
        },
        titleCount: {
            conditions: []
        },
        modal: false
    }).show()
}

function showTooltip(selector, message, triggerClose_add) {
    triggerClose = {
        originClick: true,
        click: true,
        tap: true,
        mouseleave: true,
    };
    if (triggerClose_add) {
        triggerClose[triggerClose_add] = true
    }
    $(selector).tooltipster({
        side: "top",
        position: "top",
        trigger: 'custom',
        triggerOpen: {
            mouseenter: false
        },
        triggerClose: triggerClose
    }).tooltipster('content', message).tooltipster('open')
}



function jsonHandler(json) {
    $("[data-preloader]").hide();

    var noty_type = "default";
    if(json.errors || json.error || json.status == false){
        noty_type = "error";
    }
    if(json.status == 1){
        noty_type = "success";
    }
    if(json.message){
        showNoty(json.message, noty_type);
    }
    if(json.javascript){
        eval(json.javascript);
    }

    $("input, textarea, select").removeClass("error");
    if(json.errors){

       for(input_name in json.errors){
           key = input_name;
           input_name_array = input_name.split('.');
           if(input_name_array.length > 1){
               input_name_with_square = input_name_array[0];
               input_name_array.reduce(function(field, current){
                    input_name_with_square = input_name_with_square + "["+ current +"]";
                    return "["+ field +"]";
               });
               input_name = input_name_with_square;
           }
           $el = $('[name="'+input_name+'"]');
           if($el.length){
               error_text = json.errors[key][0];
               $el.addClass("error");
           }else if(input_name_array.length > 1){
               input_name = input_name_array[0];
               $el = $('[name^="'+input_name+'"]');
               error_text = json.errors[key][0];
               $el.addClass("error");
           }
       }
    }

    if(json.redirect){
        window.location.href = json.redirect;
    }
}


function showPopup(element, callbacks) {
    if ($(element).length > 0) {
        $.magnificPopup.open({
			closeOnBgClick: false,
            showCloseBtn: false,
            items: {
                src: $(element)
            },
            callbacks: callbacks,
            //type: 'inline'
        });

    }
}

function showAlert(text, type, callbacks) {
    if(type == "success"){
        $("#alert_success [data-text]").html(text);
        showPopup("#alert_success", callbacks);
    }
    if(type == "error"){
        $("#alert_error [data-text]").html(text);
        showPopup("#alert_error", callbacks);
    }
}

//Отправка форм посредство ajax
function submitFormWithAjax(btn_or_form, callback){
    if($(btn_or_form).prop("tagName") == "FORM"){
        $form = $(btn_or_form);
    }else{
        $form = $(btn_or_form).closest("form");
    }
    data = $form.serialize();
    action = $form.attr("action");

    $("[data-preloader]").show();

    $.post(action, data, callback);
}