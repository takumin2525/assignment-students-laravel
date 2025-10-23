<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;

class DecrementGradeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        Student::where('grade', '>', 1)->decrement('grade');
        return redirect()->back()->with('success', '全学生の学年を1つ下げました！');
    }
}
