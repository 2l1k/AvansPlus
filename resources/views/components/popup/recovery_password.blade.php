<div id="recovery_password" class="popup">
    <form action="{{action("BorrowerController@addNewPassword")}}" method="POST">
        <h2>Восстановление пароля</h2>
        <div class="row">
            <div class="col-md-8 pull-xs-none">
                <input type="text" name="recovery_code" placeholder="Введите код из СМС"/>
            </div>
            <div class="col-md-8 pull-xs-none">
                <input type="password" name="new_password" placeholder="Введите новый пароль"/>
            </div>
        </div>
        <div class="text-xs-center">
            <button type="button" class="btn btn-2 aw" data-borrower_add_new_password>Сменить пароль</button>
        </div>
    </form>
</div>