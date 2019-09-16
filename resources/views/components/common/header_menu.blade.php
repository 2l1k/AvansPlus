<section class="help">
    <div class="container">
        <div class="row">
            <div class="col-md-12 pull-xs-none">
                <a href="/" title="" class="home"></a>
                {{--<a href="/how-it-works.html" title="">Как это работает</a>--}}
                {{--<a href="/faq.html" title="">FAQ</a>--}}
            </div>
            @if(Session::has('borrower_id'))
            <div class="col-md-2 pull-xs-right hidden-xs-down">
                <a href="{{route('account.logout')}}" title="" class="btn btn-2 fw default_btn">Выйти</a>
            </div>
                @endif
        </div>
    </div>
</section>