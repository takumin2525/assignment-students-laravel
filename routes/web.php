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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/students/{id}/grades',  'StudentController@editGrades')->name('students.grade.edit');
Route::post('/students/{id}/grades', 'StudentController@updateGrades')->name('students.grade.update');

Route::get('/students/search', 'StudentController@search')->name('students.search');

Route::post('/students/increment-grade', 'IncrementGradeController')->name('students.incrementGrade');
Route::post('/students/decrement-grade', 'DecrementGradeController')->name('students.decrementGrade');

Route::resource('students', 'StudentController');


// http://localhost:8888/phpMyAdmin/
// http://127.0.0.1:8000/