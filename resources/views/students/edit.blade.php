@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">学生情報の編集</h1>

    <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="student_name">氏名</label>
            <input type="text" name="student_name" class="form-control" value="{{ $student->student_name }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="grade">学年</label>
            <select name="grade" class="form-control">
                <option value="1" {{ $student->grade == 1 ? 'selected' : '' }}>1年</option>
                <option value="2" {{ $student->grade == 2 ? 'selected' : '' }}>2年</option>
                <option value="3" {{ $student->grade == 3 ? 'selected' : '' }}>3年</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="address">住所</label>
            <input type="text" name="address" class="form-control" value="{{ $student->address }}">
        </div>

        <div class="form-group mb-3">
            <label for="img_path">顔写真</label>
            <input type="file" name="img_path" id="img_path" class="form-control">
            
            @if($student->img_path)
                <div class="mt-3">
                    <p>現在の写真：</p>
                    <img src="{{ asset('storage/' . $student->img_path) }}" alt="顔写真"
                         class="img-thumbnail" style="width:150px; height:150px; object-fit:cover;">
                </div>
            @else
                <p class="text-muted mt-2">顔写真は登録されていません。</p>
            @endif
        </div>

        <div class="form-group mb-3">
            <label for="comment">コメント</label>
            <textarea name="comment" class="form-control">{{ $student->comment }}</textarea>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success">更新する</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">一覧に戻る</a>
        </div>
    </form>
</div>
@endsection
