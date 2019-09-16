<div id="success-recovery" class="popup">
    <form action="{{route("account.login")}}" method="POST">
        <p class="success-recovery">Вы успешно сменили пароль</p>
        <h2>Войти в личный кабинет</h2>
        <input type="text" name="phone_number" placeholder="Телефон" data-phone_input/>
        <input type="password" name="password" placeholder="Пароль"/>
        <a href="#forget_password" title="" data-popup>Забыли пароль?</a>
        <div class="text-xs-center">
            <button class="btn btn-2 aw" data-login>Войти</button>
            <button class="btn btn-2 aw">Отмена</button>
        </div>
    </form>
</div>