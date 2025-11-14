@php
    $subjects = [
        'japanese'                   => '国語',
        'math'                       => '数学',
        'science'                    => '理科',
        'social_studies'            => '社会',
        'english'                    => '英語',
        'music'                      => '音楽',
        'art'                        => '美術',
        'home_economics'            => '家庭科',
        'health_and_physical_education' => '保健体育',
    ];
@endphp

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
