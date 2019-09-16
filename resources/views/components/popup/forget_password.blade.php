<div id="forget_password" class="popup">
    <form action="{{action("BorrowerController@resetPassword")}}" method="POST">
        <h2>Забыли пароль?</h2>
        <div class="row">
            <div class="col-md-8 pull-xs-none">
                <input type="text" name="phone_number" placeholder="Телефон" data-phone_input/>
            </div>
        </div>
        <div class="text-xs-center">
            <button type="button" class="btn btn-2 aw" data-borrower_forget_password>Восстановить пароль</button>
            <button class="btn btn-2 aw close_popup">Отмена</button>
        </div>
    </form>
</div>