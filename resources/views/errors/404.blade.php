@extends('layouts.app')
@section('content')
    @component('components.common.header_menu')
    @endcomponent
	<section class="page-not-found">
		<div class="container">
			<h2 class="h1 title"><span>Страница не существует</span></h2>
			<p>Страница, на которую Вы пытаетесь попасть, не<br/> существует или была удалена.</p>
			<a href="/" title="" class="btn btn-2 aw">Вернуться на главную</a>
		</div>
	</section>
@endsection