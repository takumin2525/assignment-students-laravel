<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SchoolGrade;

class Student extends Model
{
    // 対応するテーブル名を指定
    protected $table = 'students';
    // 一括代入できるカラムを定義
    protected $fillable = [
        'student_name',
        'grade',
        'address',
        'img_path',
        'comment',
    ];

    public function grades() {
        return $this->hasMany(SchoolGrade::class, 'student_id');
    }
    
    
}


// １テーブルにつき、１モデル