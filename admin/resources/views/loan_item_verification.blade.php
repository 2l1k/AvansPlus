<div class="row varify_tables">

    @if(isset($verified_taxpayer) && $verified_taxpayer)
        <div class="col-md-6">
            <h3>Наличие в списке налогоплательщиков</h3>
            <div class="row">
                <div class="col-md-10">
                    @if(empty($verified_taxpayer->html_result))
                        @if($verified_taxpayer->is_verified == 0)
                            Идёт проверка ...
                        @else
                            Не найдено
                        @endif
                    @else
                        <table class="table">
                            {!! $verified_taxpayer->html_result !!}
                        </table>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(isset($verified_restricted) && $verified_restricted)

        <div class="col-md-6">
            <h3>Наличие в списке ограниченных к выезду</h3>
            <div class="row">
                <div class="col-md-10">
                    @if(empty($verified_restricted->html_result))
                        @if($verified_restricted->is_verified == 0)
                            Идёт проверка ...
                        @else
                            Не найдено
                        @endif
                    @else
                        <table class="table">
                            {!! $verified_restricted->html_result !!}
                        </table>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(isset($verified_debtor) && $verified_debtor)
        <div class="col-md-12">
            <h3>Наличие в списке должников</h3>
            <div class="row">
                <div class="col-md-10">
                    @if(empty($verified_debtor->html_result))
                        @if($verified_debtor->is_verified == 0)
                            Идёт проверка ...
                        @else
                            Не найдено
                        @endif
                    @else
                        <table class="table">
                            {!! $verified_debtor->html_result !!}
                        </table>
                    @endif
                </div>
            </div>
        </div>

    @endif

</div>
