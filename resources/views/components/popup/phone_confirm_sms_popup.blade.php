<div id="sms_popup" class="popup">
    <form action="{{action("BorrowerController@checkConfirmationCode")}}" method="POST">
        <h2><span>Введите смс-код</span></h2>
        <p>На Ваш телефон отправлен код <br/>для подтверждения телефона</p>
        <div class="row">
            <div class="col-md-8 pull-xs-none">
                <input type="text" name="code" placeholder="_&nbsp;_&nbsp;_&nbsp;_" maxlength="4" autocomplete="off"/>
            </div>
        </div>
    </form>
</div>