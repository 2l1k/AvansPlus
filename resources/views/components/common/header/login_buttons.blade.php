@if(Session::has('borrower_id'))
    <div class="col-md-2 col-xs-6 hidden-md-up">
        <a href="{{route('account.logout')}}" title="" class="btn btn-2 fw default_btn">Выйти</a>
    </div>
    <div class="col-md-2 col-xs-6">
        <a href="{{route('account.index')}}" title="" class="btn btn-2 fw cabinet_btn">{{\App\Helpers\SessionHelper::borrower()->name_with_initials}}</a>
    </div>
@else
    <div class="col-md-2 col-xs-12">
        <a href="#login" title="" class="btn btn-2 fw" data-popup>Личный кабинет</a>
    </div>
@endif