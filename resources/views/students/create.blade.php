@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">新規学生登録</h1>

    <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="student_name">氏名</label>
            <input type="text" name="student_name" id="student_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="grade">学年</label>
            <select name="grade" id="grade" class="form-control" required>
                <option value="1">1年</option>
                <option value="2">2年</option>
                <option value="3">3年</option>
            </select>
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label for="img_path">顔写真</label>
            <input type="file" name="img_path" id="img_path" class="form-control">
        </div>

        <div class="form-group">
            <label for="comment">コメント</label>
            <textarea name="comment" id="comment" class="form-control"></textarea>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success">登録する</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">一覧に戻る</a>
        </div>
    </form>
</div>
@endsection
