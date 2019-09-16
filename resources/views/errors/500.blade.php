@extends('layouts.app')

@section('content')
    @component('components.common.header_menu')
    @endcomponent
	<section class="server-error">
		<div class="container">
			<h2 class="h1 title"><span>Ошибка сервера</span></h2>
			<p>На сервере произошла непредвиденная ошибка. Пожалуйста,<br/> подождите, она вскоре будет исправлена.</p>
			<a href="/" title="" class="btn btn-2 aw">Вернуться на главную</a>
		</div>
	</section>
@endsection