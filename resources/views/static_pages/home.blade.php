@extends('layouts.default')

@section('title')
首页
@stop

@section('content')
	@if(Auth::check())
		<div class="row">
			<div class="col-md-8">
				<section class="status_form">
					@include('shared._status_form', ['user'=>Auth::user()])
				</section>
				<h4>微博列表</h4>
				<hr>
		        @include('shared._feed')
			</div>
			<aside class="col-md-4">
				<section class="user_info">
					@include('shared._user_info', ['user'=>Auth::user()])
				</section>
				<section class="stats mt-2">
					@include('shared._stats', ['user'=>Auth::user()])
				</section>
			</aside>
		</div>
	@else
		<div class="jumbotron">
			<h1>Hello WeiBo</h1>
			<p>
				你现在所看到的是 
				<a href="https://learnku.com/courses/laravel-essential-training/5.8" target="_blank">Laravel 入门教程</a> 的示例项目主页。
			</p>
			<p>
				一切，将从这里开始。
			</p>
			<a class="btn btn-lg btn-success" href="{{ route('signup') }}">现在注册</a>
		</div>
	@endif
	
@stop