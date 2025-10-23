@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="mb-4 text-center">登録済み学生一覧</h1>

        @if (session('success'))
            <div class="alert alert-success mt-2">
                {{ session('success') }}
            </div>
        @endif

    {{-- 検索 + 学年操作（全部1行・中央揃え） --}}
    <div class="mb-4 d-flex justify-content-center align-items-center flex-wrap gap-2">

    {{-- 検索フォーム --}}
    <form method="GET"
            action="{{ route('students.index') }}"
            class="m-0 d-flex align-items-center gap-2">

        <input type="text"
            name="student_name"
            placeholder="氏名で検索"
            class="form-control w-auto"
            value="{{ request('student_name') }}">

        <select name="grade" class="form-control w-auto">
        <option value="">全学年</option>
        <option value="1" {{ request('grade') == 1 ? 'selected' : '' }}>1年</option>
        <option value="2" {{ request('grade') == 2 ? 'selected' : '' }}>2年</option>
        <option value="3" {{ request('grade') == 3 ? 'selected' : '' }}>3年</option>
        </select>

        <button type="submit" class="btn btn-primary">検索</button>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">リセット</a>
    </form>

    {{-- 学年を1つ上げる --}}
    <form action="{{ route('students.incrementGrade') }}" method="POST" class="m-0">
        @csrf
        <button type="submit" class="btn btn-warning">学年を1つ上げる</button>
    </form>

    {{-- 学年を1つ下げる（紫） --}}
    <form action="{{ route('students.decrementGrade') }}" method="POST" class="m-0">
        @csrf
        <button type="submit" class="btn btn-purple text-white">学年を1つ下げる</button>
    </form>
    </div>

    {{-- 追加スタイル（位置ズレ解消＆紫ボタン） --}}
    <style>
    /* 3つの form を1行で綺麗に揃えるための微調整 */
    .mb-4 .btn, .mb-4 .form-control { vertical-align: middle; }
    .mb-4 form { display: inline-block; }      /* m-0 とセットで段差を無くす */
    .btn-purple { background-color: #6f42c1; border-color: #6f42c1; }
    .btn-purple:hover { background-color: #5a33a3; border-color: #5a33a3; }
    </style>




    @if($students->isEmpty())
        <p class="text-center text-muted">登録された学生はいません。</p>
    @else
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>学年</th>
                    <th>顔写真</th>
                    <th>氏名</th>
                    <th>住所</th>
                    <th>コメント</th>
                    <th>作成日</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->grade }}</td>
                        <td class="text-center">
                            @if($student->img_path)
                                <img src="{{ asset('storage/' . $student->img_path) }}" alt="顔写真" style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                            @else
                                <img src="{{ asset('storage/images/default-user.jpg') }}" alt="デフォルトアイコン" style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                            @endif
                        </td>
                        <td>{{ $student->student_name }}</td>
                        <td>{{ $student->address }}</td>
                        <td>{{ $student->comment }}</td>
                        <td>{{ $student->created_at }}</td>
                        
                        <td>
                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-success">詳細</a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="text-center mt-4">
        <a href="{{ url('/home') }}" class="btn btn-secondary">メニューへ戻る</a>
    </div>
</div>
@endsection
