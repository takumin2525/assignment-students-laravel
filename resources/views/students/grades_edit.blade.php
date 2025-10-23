@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">{{ $student->student_name }} さんの成績登録・編集</h1>

    <form method="POST" action="{{ route('students.grades.update', $student->id) }}">
        @csrf

        <div class="mb-4 text-center"> 
            <label for="grade" class="mx-2">学年：</label>
            <select name="grade" id="grade" class="form-control d-inline-block w-auto">
                <option value="1" selected>1年生</option>
                <option value="2">2年生</option>
                <option value="3">3年生</option>
            </select>

            <label for="term" class="mx-2">学期：</label>
            <select name="term" id="term" class="form-control d-inline-block w-auto">
                <option value="1" selected>1学期</option>
                <option value="2">2学期</option>
                <option value="3">3学期</option>
            </select>
        </div>

            <table class="table table-bordered w-75 mx-auto">
            <thead class="table-light">
                <tr>
                    <th>教科</th>
                    <th>評価（0〜10）</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subjects = [
                        'japanese' => '国語',
                        'math' => '数学',
                        'science' => '理科',
                        'social_studies' => '社会',
                        'english' => '英語',
                        'music' => '音楽',
                        'art' => '美術',
                        'home_economics' => '家庭科',
                        'health_and_physical_education' => '保健体育',
                    ];
                @endphp


                @foreach ($subjects as $key => $label)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>
                            <input type="number"
                                name="{{ $key }}"
                                value="{{ old($key, $grades->$key ?? '') }}"
                                min="0" max="10"
                                class="form-control text-center">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success mx-2">保存</button>
            <a href="{{ route('students.show', $student->id) }}" class="btn btn-secondary mx-2">戻る</a>
        </div>
    </form>
</div>
@endsection
