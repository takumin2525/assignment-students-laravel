<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;

class IncrementGradeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        Student::where('grade', '<', 3)->increment('grade');
        return redirect()->back()->with('success', '全学生の学年を1つ進めました！');
    }
}
