@php
    // 現在の並び順取得（未指定なら id/asc）
    $curSort = $sort ?? 'id';
    $curDir  = $direction ?? 'asc';
    $flip = function ($dir) {
        return $dir === 'asc' ? 'desc' : 'asc';
    };
@endphp

<table class="table table-striped table-bordered">
    <thead class="thead-light">
        <tr>
            {{-- data-field = ソート対象カラム名, data-dir = 次に適用する向き --}}
            <th class="sortable"
                data-field="id"
                data-dir="{{ $curSort === 'id' ? $flip($curDir) : 'asc' }}">
                ID
            </th>

            <th class="sortable"
                data-field="grade"
                data-dir="{{ $curSort === 'grade' ? $flip($curDir) : 'asc' }}">
                学年
            </th>

            <th>顔写真</th>

            <th class="sortable"
                data-field="student_name"
                data-dir="{{ $curSort === 'student_name' ? $flip($curDir) : 'asc' }}">
                氏名
            </th>

            <th>住所</th>
            <th>コメント</th>

            <th class="sortable"
                data-field="created_at"
                data-dir="{{ $curSort === 'created_at' ? $flip($curDir) : 'asc' }}">
                作成日
            </th>

            <th>操作</th>
        </tr>
    </thead>

    <tbody>
    @forelse ($students as $s)
        <tr>
            <td>{{ $s->id }}</td>
            <td>{{ $s->grade }}</td>

            {{-- 顔写真（img_path or photo / 無い場合はデフォルト） --}}
            <td class="text-center">
                @php
                    $img = $s->img_path ?? $s->photo ?? null;

                    // ストレージ相対パスなら asset('storage/...')、完全URLならそのまま
                    $src = $img
                        ? (\Illuminate\Support\Str::startsWith($img, ['http://', 'https://'])
                            ? $img
                            : asset('storage/' . $img))
                        : null;
                @endphp

                @if ($src)
                    <img  src="{{ $src }}"
                          alt="顔写真"
                          width="36"
                          height="36"
                          style="object-fit:cover;border-radius:50%;">
                @else
                    <img  src="{{ asset('storage/images/default-user.jpg') }}"
                          alt="デフォルトアイコン"
                          width="36"
                          height="36"
                          style="object-fit:cover;border-radius:50%;opacity:0.8;">
                @endif
            </td>

            <td>{{ $s->student_name }}</td>
            <td>{{ $s->address }}</td>
            <td>{{ $s->comment }}</td>
            <td>{{ $s->created_at }}</td>

            <td>
                {{-- 詳細 --}}
                <a class="btn btn-sm btn-success"
                   href="{{ route('students.show', $s->id) }}">
                    詳細
                </a>

                {{-- 削除 --}}
                <form action="{{ route('students.destroy', $s->id) }}"
                      method="POST"
                      class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('削除しますか？');">
                        削除
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center text-muted py-3">
                データがありません
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
