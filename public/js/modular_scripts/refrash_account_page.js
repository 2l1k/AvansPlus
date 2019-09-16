$(function () {
    //Проверяем, изменился ли статус займа при новой заявке. И если изменился, то перезагружаем страницу.
    $refrashAccountPageScript = $("[data-loan-status-id]");
    var loan_status_id = $refrashAccountPageScript.attr("data-loan-status-id"); //статус активного займа
    var check_status_route = $refrashAccountPageScript.attr("data-route-url");
    var check_status_ajax = false;

    setInterval(function () {
        if(check_status_ajax != false){
            check_status_ajax.abort();
        }

        check_status_ajax = $.ajax({
            type: "POST",
            url: check_status_route,
            data: {},
            success: function(json){
                if(json.loan_status_id != loan_status_id){
                    window.location.reload();
                }
            }
        });
    }, 5000);

    setInterval(function () {
        if(loan_status_id < 9 && $(".mfp-wrap").length == 0){
            window.location.reload();
        }
    }, 1000);



});