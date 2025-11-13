@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="mb-4 text-center">登録済み学生一覧</h1>

  @if (session('success'))
    <div class="alert alert-success mt-2">
      {{ session('success') }}
    </div>
  @endif

  {{-- 検索＋学年操作（1行・中央揃え） --}}
  <div class="mb-4 d-flex justify-content-center align-items-center flex-wrap gap-2">

    {{-- 検索フォーム（Ajax対象） --}}
    <form id="search-form" method="GET" action="{{ route('students.search') }}" class="m-0 d-flex align-items-center gap-2">
      <input
        type="text"
        name="student_name"
        placeholder="氏名で検索"
        class="form-control w-auto"
        value="{{ request('student_name') }}"
        autocomplete="off"
      >

      <select name="grade" class="form-control w-auto">
        <option value="">全学年</option>
        <option value="1" {{ request('grade') == 1 ? 'selected' : '' }}>1年</option>
        <option value="2" {{ request('grade') == 2 ? 'selected' : '' }}>2年</option>
        <option value="3" {{ request('grade') == 3 ? 'selected' : '' }}>3年</option>
      </select>

      <button type="submit" class="btn btn-primary">検索</button>

      {{-- リセットはGETで一覧へ（Ajaxで差し替え） --}}
      <a href="{{ route('students.search') }}" id="reset-btn" class="btn btn-secondary">リセット</a>
    </form>

    {{-- 学年を1つ上げる --}}
    <form action="{{ route('students.incrementGrade') }}" method="POST" class="m-0">
      @csrf
      <button type="submit" class="btn btn-warning">学年を1つ上げる</button>
    </form>

    {{-- 学年を1つ下げる（紫ボタン） --}}
    <form action="{{ route('students.decrementGrade') }}" method="POST" class="m-0">
      @csrf
      <button type="submit" class="btn btn-purple text-white">学年を1つ下げる</button>
    </form>
  </div>

  {{-- ここに部分ビューを差し替える（テーブル部品ビュー） --}}
  <div id="list">
    @include('students._table', [
      'students'   => $students,
      'sort'       => $sort   ?? 'id',
      'direction'  => $direction ?? 'asc'
    ])
  </div>

  <div class="text-center mt-4">
    <a href="{{ url('/home') }}" class="btn btn-secondary">メニューへ戻る</a>
  </div>
</div>

{{-- 追加スタイル（紫ボタン＆段差調整） --}}
<style>
  .btn-purple { background-color: #6f42c1; border-color: #6f42c1; }
  .btn-purple:hover { background-color: #5a33a3; border-color: #5a33a3; }
  .mb-4 .btn, .mb-4 .form-control { vertical-align: middle; }
  .mb-4 form { display: inline-block; }
</style>

{{-- jQuery（CDN） --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
  const $form   = $('#search-form');
  const baseUrl = "{{ route('students.search') }}";

  // 検索をAjaxで
  $form.on('submit', function (e) {
    e.preventDefault();
    $.get(baseUrl, $form.serialize(), function (html) {
      $('#list').html(html);
    });
  });

  // リセットもAjaxで
  $('#reset-btn').on('click', function (e) {
    e.preventDefault();
    $.get($(this).attr('href'), function (html) {
      $('#list').html(html);
      // 入力をリセット
      $form[0].reset();
    });
  });

  // 見出しクリックでソート（部分ビュー内の th.sortable を委譲で拾う）
  $(document).on('click', 'th.sortable', function () {
    const field = $(this).data('field');
    // data-dir には「次に適用したい向き」を _table 側で入れておく
    const dir   = $(this).data('dir');

    // 既存の検索条件にソート条件を付けてGET
    const qs = $form.serialize() + '&sort=' + encodeURIComponent(field) + '&direction=' + encodeURIComponent(dir);

    $.get(baseUrl, qs, function (html) {
      $('#list').html(html);
    });
  });
});
</script>
@endsection
