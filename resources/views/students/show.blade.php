@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="mb-4 text-center">学生情報</h1>

  {{-- 学生情報ブロック --}}
  <div class="row">
    {{-- 左：基本情報 --}}
    <div class="col-md-8">
      <table class="table table-bordered">
        <tr><th>学年</th><td>{{ $student->grade }}</td></tr>
        <tr><th>氏名</th><td>{{ $student->student_name }}</td></tr>
        <tr><th>住所</th><td>{{ $student->address }}</td></tr>
        <tr><th>コメント</th><td>{{ $student->comment }}</td></tr>
      </table>
    </div>

    {{-- 右：顔写真 --}}
    <div class="col-md-4 text-center">
      @php
        use Illuminate\Support\Str;
        $img = $student->img_path ?? null;
      @endphp

      @if ($img)
        @php
          $src = Str::startsWith($img, ['http://','https://'])
            ? $img
            : asset('storage/'.$img);
        @endphp
        <img src="{{ $src }}" alt="顔写真"
             class="img-thumbnail rounded-circle"
             style="width:180px;height:180px;object-fit:cover;">
      @else
        <img src="{{ asset('storage/images/default-user.jpg') }}"
             alt="デフォルト顔写真"
             class="img-thumbnail rounded-circle"
             style="width:180px;height:180px;object-fit:cover;opacity:0.8;">
        <p class="text-muted mt-2">顔写真未登録</p>
      @endif
    </div>
  </div>

  {{-- ボタン：編集・一覧へ --}}
  <div class="row mt-3">
    <div class="col text-center">
      <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary mx-2">学生情報編集</a>
      <a href="{{ route('students.index') }}" class="btn btn-secondary mx-2">一覧へ戻る</a>
    </div>
  </div>

  {{-- 成績一覧 --}}
  <h2 class="mt-5 mb-3 text-center">成績一覧</h2>
  @if ($student->grades->isEmpty())
    <p class="text-center text-muted">成績データは登録されていません。</p>
  @else
    {{-- 学年検索フォーム --}}
    <form method="GET" action="{{ route('students.show', $student->id) }}" class="text-center mb-4">
      <label for="grade" class="mx-2">学年：</label>
      <select name="grade" id="grade" class="form-control d-inline-block w-auto">
        @for ($i = 1; $i <= 3; $i++)
          <option value="{{ $i }}" {{ ($selectedGrade ?? '') == $i ? 'selected' : '' }}>{{ $i }}年生</option>
        @endfor
      </select>
      <button type="submit" class="btn btn-primary mx-2">検索</button>
    </form>

    {{-- 成績テーブル --}}
    <table class="table table-bordered text-center">
      <thead class="table-light">
        <tr>
          <th>教科</th>
          <th>1学期</th>
          <th>2学期</th>
          <th>3学期</th>
        </tr>
      </thead>
      <tbody>
        @php
          $subjects = [
            'japanese' => '国語', 'math' => '数学', 'science' => '理科',
            'social_studies' => '社会', 'english' => '英語',
            'music' => '音楽', 'art' => '美術', 'home_economics' => '家庭科',
            'health_and_physical_education' => '保健体育',
          ];
        @endphp

        @foreach ($subjects as $key => $label)
          <tr>
            <td>{{ $label }}</td>
            @for ($term = 1; $term <= 3; $term++)
              <td>{{ optional($grades->get($term))->$key }}</td>
            @endfor
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  {{-- 成績編集ボタン --}}
  <div class="text-center mt-4">
    <a href="{{ route('students.grade.edit', $student->id) }}" class="btn btn-primary mx-2">成績登録・編集</a>
    <a href="{{ route('students.index') }}" class="btn btn-secondary mx-2">一覧へ戻る</a>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const $form   = $('#grade-form');
    const $area   = $('#grades-area');

    $form.on('submit', function (e) {
        e.preventDefault();
        $.get($form.attr('action'), $form.serialize(), function (html) {
            $area.html(html);   // テーブル部分だけ差し替え
        });
    });
});
</script>
@endpush

