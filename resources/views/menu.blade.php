@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1>学生管理システム メニュー</h1>
    <p>{{ Auth::user()->name }} さん、ようこそ！</p>

    <div class="d-flex justify-content-center mt-4">
        <a href="{{ route('students.create') }}" class="btn btn-success mx-2">新規学生登録</a>
        <a href="{{ route('students.index') }}" class="btn btn-primary mx-2">学生情報一覧</a>
    </div>

    <div class="mt-4">
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="btn btn-outline-danger">
            ログアウト
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>
</div>
@endsection
