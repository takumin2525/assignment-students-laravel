<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolGrade extends Model
{
    // 対応するテーブル名の指定
    protected $table = 'school_grades';
    // タイムスタンプ自動更新を無効化
    public $timestamps = false;
    // 一括代入できるカラムを定義
    protected $fillable = [
        'student_id',
        'grade',
        'term',
        'japanese',
        'math',
        'science',
        'social_studies',
        'music',
        'home_economics',
        'english',
        'art',
        'health_and_physical_education',
    ];
    public function student() {
        return $this->belongsTo(Student::class, 'student_id');
    } 
}
