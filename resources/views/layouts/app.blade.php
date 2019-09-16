<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf" content="{{ csrf_token() }}">
    <title>Онлайн займы Avansplus</title>
    <meta name="description" content="Если вы срочно нуждаетесь в займе наличными, заполняйте заявку онлайн и получайте деньги на банковскую карту. Avansplus - онлайн займы."/>
    <meta name="keywords" content="займы в алматы онлайн, деньги в долг алматы, калькулятор займа, калькулятор онлайн займа, деньги в долг, взять займ в алматы, взять займ онлайн, взять деньги взаймы, займ денег, займ срочно, займ до зарплаты алматы, быстрые займы, заявка онлайн на займ наличными, займы в алматы, деньги до зарплаты, в долг до зарплаты"/>
	<link rel="canonical" href="http://avansplus.kz" />
    <link rel="icon" href="img/favicon.png" type="image/x-icon"/>
	<meta property="og:site_name" content="avansplus.kz"/>
	<meta property="og:type" content="product"/>
	<meta property="og:title" content="Онлайн займы Avansplus"/>
	<meta property="og:url" content="https://avansplus.kz"/>
	<meta property="og:image" content="https://avansplus.kz/img/logo.png"/>
</head>
<body>

<header>
    <div class="container">
        <div class="row">
            <div itemscope itemtype="http://schema.org/Organization" class="col-md-2 col-xs-4 pull-md-left pull-xs-none">
                <a itemprop="url" href="{{action("IndexController@index")}}" title="" class="logo">
                    <img itemprop="logo" src="img/logo.png" alt=""/>
                </a>
            </div>
            <div class="col-md-2 col-xs-5 hidden-xs" style=" width: 200px; min-width: 200px; max-width: 200px; ">
                <a href="tel:+77762225566" title="" class="phone">+7 776 222 55 66</a>
            </div>
            <div class="col-md-3 col-xs-7 hidden-xs">
                <a href="mailto:application@avansplus.kz" title="" class="email">application@avansplus.kz</a>
            </div>
            <div class="col-md-2 col-xs-6 col-md-offset-1 hidden-xs">
                <a href="#order" title="" class="btn btn-1 fw" data-scroll>Получить займ</a>
            </div>
            @component("components.common.header.login_buttons")
            @endcomponent
        </div>
    </div>
</header>
@yield('content')
<footer>
    <div class="container">
        <div class="footer">
            <div class="row">
                <div itemscope itemtype="http://schema.org/Organization" class="col-md-2 col-xs-4 pull-md-left pull-xs-none">
                    <a itemprop="url" href="" title="" class="logo">
                        <img itemprop="logo" src="{{asset("img/logo.png")}}" alt=""/>
                    </a>
                </div>
                <div class="col-md-2 col-xs-12 text-xs-center">
                    <a href="tel:+77762225566" title="" class="phone">+7 776 222 55 66</a>
                </div>
                <div class="col-md-3 col-xs-12 text-xs-center">
                    <a href="mailto:application@avansplus.kz" title="" class="email">application@avansplus.kz</a>
                </div>

                <div class="col-md-3 col-xs-12 col-md-offset-2">
                    <a href="#consultation" title="" class="btn btn-2 fw" data-popup hidden>Получить консультацию</a>
                </div>
            </div>
            <div class="row">

                <div class="col-md-3 col-xs-6 pull-md-left pull-xs-none">
                    <a href="{{route("pages.payment_info")}}" title="" style="background: none; line-height: normal;" class="email pl-xs-0">Информация об оплате</a>
                </div>
                <div class="payment-icons">
                    <div class="col-md-1">
                        <img src="{{asset("img/payment/simplecloud.png")}}" class="payment-icon" alt="" style="width:80%;" />
                    </div>
                    <div class="col-md-1">
                        <img src="{{asset("img/payment/mastercard.png")}}" class="payment-icon" alt="" />
                    </div>
                    <div class="col-md-1">
                        <img src="{{asset("img/payment/visa.png")}}" class="payment-icon" alt="" />
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="row">
                <div class="col-sm-6 col-xs-5">
                    <p>Avansplus 2018 &copy;</p>
                </div>
                <div class="col-sm-6 col-xs-7 text-xs-right">
                    <p>Разработка сайта<a href="http://puzzle.kz" title="" target="blanc"><img src="img/puzzle.png" alt=""/></a></p>
                </div>
            </div>
        </div>
    </div>
</footer>


@component("components.popup.preloader")
@endcomponent
<div hidden>
    @yield('popup')

    @component("components.popup.alert")
    @endcomponent
    @component("components.popup.account_exists")
    @endcomponent
    @component("components.popup.consultation")
    @endcomponent
    @component("components.popup.login")
    @endcomponent
    @component("components.popup.forget_password")
    @endcomponent
    @component("components.popup.recovery_password")
    @endcomponent
    @component("components.popup.success_recovery")
    @endcomponent
    @component("components.popup.phone_confirm_sms_popup")
    @endcomponent

</div>

<link rel="stylesheet" href="/css/jquery-ui.min.css" type="text/css"/>
<link rel="stylesheet" href="/css/magnific-popup.min.css" type="text/css"/>
<link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="/css/slick.min.css" type="text/css"/>
<link rel="stylesheet" href="/css/style.css" type="text/css"/>
<link rel="stylesheet" href="/css/helper.css" type="text/css"/>
<link rel="stylesheet" href="/css/media.css" type="text/css"/>
<link rel="stylesheet" href="/js/noty/themes/metroui.css" type="text/css"/>
<link rel="stylesheet" href="/js/noty/noty.css" type="text/css"/>

<script src="/js/jquery.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/jquery.ui.touch-punch.min.js"></script>
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/slick.min.js"></script>
<script src="/js/noty/noty.min.js"></script>
<script src="/js/tooltipster/js/tooltipster.bundle.min.js"></script>
<script src="/js/jquery.inputmask.bundle.js"></script>
<script src="/js/helper.js"></script>
<script src="/js/main.js"></script>
<script src="/js/loan.js"></script>

@yield('jssection')
@yield('csssection')

<!-- Yandex.Metrika counter -->
<script>
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter50239858 = new Ya.Metrika2({
                    id:50239858,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/tag.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks2");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/50239858" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-125325163-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-125325163-1');
</script>

</body>
</html>