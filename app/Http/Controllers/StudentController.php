<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Student;

class StudentController extends Controller
{
    // ログインしているユーザーのみアクセス可能にする。
    public function __construct(){
        $this->middleware('auth');
    }
    // 一覧表示
    public function index(Request $request){
        $query = \App\Student::query();
        // 名前検索機能
        if ($request->filled('student_name')) {
            $query->where('student_name', 'like', "%{$request->student_name}%");
        }
        //学年検索機能
        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }
        // 検索結果の取得
        $students = $query->get();
        // ビューに渡す役割
        return view('students.index', compact('students'));
    }


    // 新規登録フォームを表示
    public function create()
    {
        return view('students.create');
    }
    // フォーム送信データを保存
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'student_name' => 'required|string|max:255',
            'grade' => 'required|integer|min:1|max:3',
            'address' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:255',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $student = new Student();
        $student->student_name = $request->student_name;
        $student->grade = $request->grade;
        $student->address = $request->address;
        $student->comment = $request->comment;        

        // 画像がアップロードされていたら保存
        if ($request->hasFile('img_path')) {
            $path = $request->file('img_path')->store('images', 'public');
            $student->img_path = $path;
        }

        // DBに保存
            $student->save();

        // 登録完了後に詳細ページへリダイレクト
        return redirect()->route('students.show', $student->id)->with('success', '学生情報を登録しました。');

    }


    // 編集画面を表示
    public function edit($id)
    {
        $student = \App\Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }
    // 更新処理
    public function update(Request $request, $id)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'grade' => 'required|integer|min:1|max:3',
            'address' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:255',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $student = \App\Student::findOrFail($id);

        // 画像がアップロードされた場合
        if ($request->hasFile('img_path')) {
            // 古い画像を削除
            if ($student->img_path && \Storage::disk('public')->exists($student->img_path)) {
                \Storage::disk('public')->delete($student->img_path);
            }
            // 新しい画像を保存
            $path = $request->file('img_path')->store('images', 'public');
            $student->img_path = $path;
        }

        $student->update([
            'student_name' => $request->student_name,
            'grade' => $request->grade,
            'address' => $request->address,
            'comment' => $request->comment,
            'img_path' => $student->img_path,
        ]);
        return redirect()->route('students.show', $student->id)->with('success', '学生情報を更新しました！');
    }
    
    // 詳細表示
    public function show(Request $request, $id)
    {
        $student = Student::with('grades')->findOrFail($id);

        // 学年選択（パラメータがあれば優先）
        $selectedGrade = $request->input('grade');
        if (!$selectedGrade) {
            // 登録済みの中で最新学年を取得
            $selectedGrade = $student->grades->max('grade') ?? 1;
        }

        // 選択された学年の成績を取得（全学期）
        $grades = $student->grades()
            ->where('grade', $selectedGrade)
            ->orderBy('term')
            ->get()
            ->keyBy('term');
            
        return view('students.show', compact('student', 'grades', 'selectedGrade'));
    }
    // 削除処理
    public function destroy($id)
    {
        $student = \App\Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', '学生情報を削除しました！');
    }

    // 成績登録・編集画面を表示
    public function editGrades($id, Request $request)
    {
        $student = \App\Student::findOrFail($id);

        $query = $student->grades();

        if ($request->has('grade')) {
            $query->where('grade', $request->input('grade'));
        }
        if ($request->has('term')) {
            $query->where('term', $request->input('term'));
        }

        $grades = $query->first();

        return view('students.grades_edit', compact('student', 'grades'));
    }


    // 成績登録・更新処理
    public function updateGrades(Request $request, $id)
    {
        $student = \App\Student::findOrFail($id);

        // バリデーション
        $request->validate([
            'grade' => 'required|integer|min:1|max:3',
            'term' => 'required|integer|min:1|max:3',
            'japanese' => 'nullable|numeric|min:0|max:100',
            'math' => 'nullable|numeric|min:0|max:100',
            'science' => 'nullable|numeric|min:0|max:100',
            'social_studies' => 'nullable|numeric|min:0|max:100',
            'english' => 'nullable|numeric|min:0|max:100',
            'music' => 'nullable|numeric|min:0|max:100',
            'art' => 'nullable|numeric|min:0|max:100',
            'home_economics' => 'nullable|numeric|min:0|max:100',
            'health_and_physical_education' => 'nullable|numeric|min:0|max:100',
        ]);

        // 成績データを保存（存在すれば更新、なければ作成）
        $student->grades()->updateOrCreate(
            ['grade' => (int)$request->input('grade'),'term' => (int)$request->input('term'),],
            $request->only([
                'japanese', 'math', 'science', 'social_studies', 'english',
                'music', 'art', 'home_economics', 'health_and_physical_education'
            ])
        );

        return redirect()->route('students.show', $student->id)
                        ->with('success', '成績を保存しました！');
    }




}
