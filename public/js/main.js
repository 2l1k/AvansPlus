function calcSliderInit(price){
    var guarantor_rate = parseFloat($('[data-guarantor_rate]').attr("data-guarantor_rate"));
    var interest_rate = parseFloat($('[data-interest_rate]').attr("data-interest_rate"));
    var duration_agreement = parseInt($('[data-duration_agreement]').attr("data-duration_agreement"));
    var overpayment = price * interest_rate * duration_agreement;
    var amount_to_refund = price + overpayment;
    $("[data-calc_form] [name=sum]").val(price);
    $("[data-calc_form] #sum").text(price);
    $("[data-calc_form] #sum2").text(amount_to_refund);
    $("[data-calc_form] [data-calculate-sum]").text(amount_to_refund);
    //$("[data-calc_form] [data-sum_third]").text(Math.round(guarantor_rate * price * duration_agreement));
    $("[data-calc_form] #dicount").text(amount_to_refund - (amount_to_refund / 100 * 13));
    $("[data-calc_form] #sumDay").text(Math.round(amount_to_refund / duration_agreement));
    $("[data-calc_form] #sumDay_overpayment").text(overpayment / duration_agreement);

    $('.registration_form [name=sum]').val(price);


   //Утсанавливаем начальную дату
   var d = new Date();
   d.setDate(d.getDate() + duration_agreement);
   $("[data-calc_form] #date").text(d.mmddyyyy());
}

$(function() {
	$("#slider").slider({
		value: 10000,
		min: 10000,
		max: 50000,
		step: 1000,
		slide: function(event, ui) {
            calcSliderInit(ui.value);
		}
	});


    calcSliderInit(10000);
	
	$(".calc button").click(function() {
		var sum = parseInt($("[name=sum]").val());
        calcSliderInit(sum);
        // $("#sum2").text(sum + (sum / 100 * 1.5) * 30);
		// $("[data-calculate-sum]").text(sum + (sum / 100 * 1.5) * 30);
		// setTimeout(function() {
		// 	var sum2 = parseInt($("#sum2").text());
		// 	$("#dicount").text(sum2 - (sum2 / 100 * 13));
		// }, 100);
	});
});

$(".order .open").click(function() {
	$(".order").slideUp();
	$(".form").slideDown();
});
$(".form .close").click(function() {
	$(".order").slideDown();
	$(".form").slideUp();
});

$(function() {
	$(".carousel").slick({
		dots: false,
		arrows: true,
		slidesToShow: 2,
        infinite: true,
		slidesToScroll: 1
	});
	$(document).delegate(".carousel button", "click", function() {
		$(".reviews").toggleClass("change-slide");
	});
});

$("[data-scroll]").click(function(e) {
	e.preventDefault();
	var id = ($(".registration_form").is(":visible")) ? ".registration_form" : $(this).attr("href"),

		top = $(id).offset().top;
	$("body, html").animate({scrollTop: top}, 1000);
	setTimeout(function() {
		$(".order").slideUp();
		$(".form").slideDown();
	}, 1000);
});
$("[data-popup]").magnificPopup({
	type: 'inline'
});
$("[wait]").magnificPopup({
	type: 'inline',
	closeOnBgClick: false,
	showCloseBtn: false
});
$("body").delegate("[data-close]", "click", function() {
	$.magnificPopup.close();
});
$("[tab]").click(function(e) {
	e.preventDefault();
	var id = $(this).attr("href");
	$(".tab").fadeOut();
	$(id).fadeIn();
	$(".tab-nav a").removeClass("active");
	$(this).addClass("active");
});

$("#edit").click(function() {
	$(this).css({color: "rgba(0, 0, 0, 0)", opacity: ".14"});
	$("[name=profile_settings] [readonly]").removeAttr("readonly");
	$("[name=profile_settings] [disabled]").removeAttr("disabled");
	$("[name=profile_settings] [hidden]").removeAttr("hidden");
});

// $(".file").click(function() {
//
// });
$("body").delegate("[name=id_card_document_1], [name=id_card_document_2], [name='address_documents[]'], [name='pension_documents[]']", "change", function() {
    inp = $(this);
    var lbl = $(this).closest(".fake_file_input").find("span");
    var file_api = (window.File && window.FileReader && window.FileList && window.Blob) ? true : false;
    var file_name;
    var file_name2;
    if (file_api && inp[0].files[0] && inp[0].files[1]) {
        file_name = inp[0].files[0].name;
        file_name2 = inp[0].files[1].name;
    } else {
        file_name = inp.val().replace("C:\\fakepath\\", '');
        file_name2 = inp.val().replace("C:\\fakepath\\", '');
    }
    if (!file_name.length) {
        return;
    }
    if (lbl.is(":visible")) {
        lbl.text(file_name +"; "+ file_name2);
        $(this).parent().addClass("done");
    }
    $("#error-2").show();
}).change();

$("body").delegate("[name='agreement_documents[]']", "change", function() {
    inp = $(this);
    var lbl = $(this).closest(".fake_file_input").find("span");
    var file_api = (window.File && window.FileReader && window.FileList && window.Blob) ? true : false;
    var file_name;
    var file_name2;
    if (file_api && inp[0].files[0] && inp[0].files[1]) {
        file_name = inp[0].files[0].name;
        file_name2 = inp[0].files[1].name;
    } else {
        file_name = inp.val().replace("C:\\fakepath\\", '');
        file_name2 = inp.val().replace("C:\\fakepath\\", '');
    }
    if (!file_name.length) {
        return;
    }
    if (lbl.is(":visible")) {
        lbl.text(file_name +"; "+ file_name2);
        $(this).parent().addClass("done");
        $(this).closest(".fake_file_input").after('<label class="file fake_file_input" name="agreement_documents">\n' +
            '                                    <span>Загрузить</span>\n' +
            '                                    <input type="file" name="agreement_documents[]" multiple/>\n' +
            '                                </label>');
    }
    $("#error-2").show();
}).change();


$(".alert-win button").click(function() {
	$(".alert-win").hide();
});

$(".repayment").click(function() {
	$("#repayment button").removeAttr("hidden");
	$("#repayment button").removeAttr("disabled");
});

$(document).ready(function() {
	if ($(window).width() < 1024) {
		$("header").prependTo("body");
	}
});