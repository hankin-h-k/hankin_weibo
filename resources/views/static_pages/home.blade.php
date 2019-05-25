@extends('layouts.default')

@section('title')
首页
@stop

@section('content')
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
@stop