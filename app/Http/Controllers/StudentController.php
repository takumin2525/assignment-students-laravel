<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Student;


class StudentController extends Controller
{
    // ログインしているユーザーのみアクセス可能にする。
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 一覧表示 /students GET
    public function index(Request $request)
    {
        [$students, $sort, $direction] = $this->buildIndexList($request);

        return view('students.index', compact('students', 'sort', 'direction'));
    }

    // 一覧表示(Ajax) /students/search GET
    public function search(Request $request)
    {
        [$students, $sort, $direction] = $this->buildIndexList($request);

        return view('students._table', compact('students', 'sort', 'direction'));
    }


    private function buildIndexList(Request $request): array
    {
        $query = Student::query();

        //氏名検索
        if ($request->filled('student_name'))
        {
            $query->where('student_name', 'like', '%'.$query->student_name.'%');
        }
        
        //学年絞込
        if ($request->filled('grade')) 
        {
            $query->where('grade', $request->grade);
        }

        // ソート
        $sort      = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');
        $allowed   = ['id', 'grade', 'student_name', 'created_at'];

        if (in_array($sort, $allowed, true)) {
            $query->orderBy($sort, $direction);
        }

        $students = $query->get();

        return [$students, $sort, $direction];
    }

    // 新規登録フォーム
    public function create()
    {
        return view('students.create');
    }

    // 新規登録 保存
    public function store(Request $request)
    {
        $request->validate(
            [
            'student_name' => 'required|string|max:255',
            'grade'        => 'required|integer|min:1|max:3',
            'address'      => 'nullable|string|max:255',
            'comment'      => 'nullable|string|max:255',
            'img_path'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]
        );

        $student = new Student();
        $student->student_name = $request->student_name;
        $student->grade        = $request->grade;
        $student->address      = $request->address;
        $student->comment      = $request->comment;

        if ($request->hasFile('img_path')) 
        {
            $path = $request->file('img_path')->store('images', 'public');
            $student->img_path = $path;
        }

        $student->save();

        return redirect()
            ->route('students.show', $student->id)
            ->with('success', '学生情報を登録しました！');
    }

    // 編集フォーム
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    // 更新処理
    public function update(Request $request, $id)
    {
        $request->validate(
            [
            'student_name' => 'required|string|max:255',
            'grade'        => 'required|integer|min:1|max:3',
            'address'      => 'nullable|string|max:255',
            'comment'      => 'nullable|string|max:255',
            'img_path'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]
        );

        $student = Student::findOrFail($id);

        // 新しい画像が来たら置き換え
        if ($request->hasFile('img_path')) 
        {
            if ($student->img_path && Storage::disk('public')->exists($student->img_path)) {
                Storage::disk('public')->delete($student->img_path);
            }
            $student->img_path = $request->file('img_path')->store('images', 'public');
        }

        $student->update(
            [
            'student_name' => $request->student_name,
            'grade'        => $request->grade,
            'address'      => $request->address,
            'comment'      => $request->comment,
            ]
        );

        return redirect()
            ->route('students.show', $student->id)
            ->with('success', '学生情報を更新しました！');
    }

    // 詳細表示
    public function show(Request $request, $id)
    {
        $student = Student::with('grades')->findOrFail($id);

        // パラメータがあれば優先、なければ最新学年（なければ1年）
        $selectedGrade = $request->input('grade');
        if (!$selectedGrade) 
        {
            $selectedGrade = $student->grades->max('grade') ?? 1;
        }

        // 選択学年の成績（全学期分）
        $grades = $student->grades()
            ->where('grade', $selectedGrade)
            ->orderBy('term')
            ->get()
            ->keyBy('term');

        return view('students.show', compact('student', 'grades', 'selectedGrade'));
    }

    // 削除
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', '学生情報を削除しました！');
    }

    // 成績編集
    public function editGrades($id, Request $request)
    {
        $student = Student::findOrFail($id);

        $query = $student->grades();
        if ($request->has('grade')) 
        {
            $query->where('grade', $request->input('grade'));
        }
        if ($request->has('term')) 
        {
            $query->where('term', $request->input('term'));
        }
        $grades = $query->first();

        return view('students.grades_edit', compact('student', 'grades'));
    }

    // 成績更新
    public function updateGrades(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate(
            [
            'grade'   => 'required|integer|min:1|max:3',
            'term'    => 'required|integer|min:1|max:3',
            'japanese' => 'nullable|numeric|min:0|max:100',
            'math'     => 'nullable|numeric|min:0|max:100',
            'science'  => 'nullable|numeric|min:0|max:100',
            'social_studies' => 'nullable|numeric|min:0|max:100',
            'english'  => 'nullable|numeric|min:0|max:100',
            'music'    => 'nullable|numeric|min:0|max:100',
            'art'      => 'nullable|numeric|min:0|max:100',
            'home_economics' => 'nullable|numeric|min:0|max:100',
            'health_and_physical_education' => 'nullable|numeric|min:0|max:100',
            ]
        );

        // 存在すれば更新、なければ作成
        $student->grades()->updateOrCreate(
            [
                'grade' => (int) $request->input('grade'),
                'term'  => (int) $request->input('term'),
            ],
            $request->only(
                [
                'japanese', 'math', 'science', 'social_studies', 'english',
                'music', 'art', 'home_economics', 'health_and_physical_education',
                ]
            )
        );

        return redirect()
            ->route('students.show', $student->id)
            ->with('success', '成績を保存しました！');
    }

}



