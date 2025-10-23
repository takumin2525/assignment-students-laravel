<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\IncrementGradeController;
use App\Http\Controllers\DecrementGradeController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// 成績登録・編集ページ表示
Route::get('/students/{id}/grades', 'StudentController@editGrades')->name('students.grades.edit');

// 成績登録・更新処理
Route::post('/students/{id}/grades', 'StudentController@updateGrades')->name('students.grades.update');

// 学生管理
Route::resource('students', 'StudentController');


Route::post('/students/increment-grade', [IncrementGradeController::class, '__invoke'])->name('students.incrementGrade');
Route::post('/students/decrement-grade', [DecrementGradeController::class, '__invoke'])->name('students.decrementGrade');






// http://localhost:8888/phpMyAdmin/
// http://127.0.0.1:8000/