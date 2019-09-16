$(function() {
	$("#slider").slider({
		value: 10000,
		min: 10000,
		max: 50000,
		step: 1000,
		slide: function(event, ui) {
			$("[name=sum]").val(ui.value);
			$("#sum").text(ui.value);
		}
	});
	$("[name=sum]").val($("#slider").slider("value"));
	
	var now = new Date();
	now.setDate(now.getDate() + 30);
	var curr_date = now.getDate();
	var curr_month = now.getMonth() + 1;
	var curr_year = now.getFullYear();
	$("#date").text(curr_date + "." + curr_month + "." + curr_year);
	
	$(".calc button").click(function() {
		var sum = parseInt($("[name=sum]").val());
		$("#sum2").text(sum + (sum / 100 * 1.5) * 30);
		setTimeout(function() {
			var sum2 = parseInt($("#sum2").text());
			$("#dicount").text(sum2 - (sum2 / 100 * 13));
		}, 100);
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

$("[scroll]").click(function(e) {
	e.preventDefault();
	var id = $(this).attr("href"),
		top = $(id).offset().top;
	$("body, html").animate({scrollTop: top}, 1000);
	setTimeout(function() {
		$(".order").slideUp();
		$(".form").slideDown();
	}, 1000);
});
$("[popup]").magnificPopup({
	type: 'inline'
});
$("[wait]").magnificPopup({
	type: 'inline',
	closeOnBgClick: false,
	showCloseBtn: false
});
$("[tab]").click(function(e) {
	e.preventDefault();
	var id = $(this).attr("href");
	
	$(".tab").fadeOut();
	$(id).fadeIn();
	$(".tab-nav a").removeClass("active");
	$(this).addClass("active");
	
	$(".tabs:not(:has(.active)) .tab-content").hide();
	$(".tabs:has(.active) .tab-content").show();
});

$("#edit").click(function() {
	$(this).css({color: "rgba(0, 0, 0, 0)", opacity: ".14", textIndent: "-9999px"});
	$("#tab-2 [readonly]").removeAttr("readonly");
	$("#tab-2 [disabled]").removeAttr("disabled");
	$("#tab-2 [hidden]").removeAttr("hidden");
});

$(".file").click(function() {
	var inp = $(this).find("[type='file']");
	var lbl = $(this).find("span");
	var file_api = (window.File && window.FileReader && window.FileList && window.Blob) ? true : false;

	inp.change(function() {
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
});

$(".alert-win button").click(function() {
	$(".alert-win").hide();
});

$(".repayment").click(function() {
	$("#repayment button").removeAttr("hidden");
	$("#repayment button").removeAttr("disabled");
});