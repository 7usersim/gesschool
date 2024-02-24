<?php

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\CoursesByClassController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\FeesController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PermissionsRolesController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TimeTableController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdminMiddleware;
use App\Models\Etablissement;
use App\Models\PermissionsRoles;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class,"login"]);
Route::get('/login', [HomeController::class,"logIn"])->name('login');
Route::post('/singIn', [HomeController::class,"connexion"]);
Route::get('/logout', [HomeController::class,"logout"]);

//--------------------------------------------- gestion des roles


Route::post('/roles/getRoles', [RolesController::class,"getListRole"]);
Route::post('/roles/save', [RolesController::class,"save"]);
Route::get('/roles/list', [RolesController::class,"liste"]);
Route::get('/roles/edit/{id}', [RolesController::class,"edit"]);
Route::post('/roles/update', [RolesController::class,"update"]);

//--------------------------------------------- Permissions

Route::post('/permission/getList', [PermissionsRolesController::class,"getListPermission"]);
Route::post('/permission/save', [PermissionsRolesController::class,"save"]);
Route::get('/permission/list', [PermissionsRolesController::class,"liste"]);
// Route::get('/roles/edit/{id}', [PermissionsRolesController::class,"edit"]);
// Route::post('/roles/update', [PermissionsRolesController::class,"update"]);


//--------------------------------------------- gestion des utilisateurs
Route::get('/users/index', [UserController::class,"index"]);
Route::post('/users/save', [UserController::class,"save"]);
Route::post('/users/get_users', [UserController::class,"getListUser"]);

//----------------------------------------------------gestion etablissement
Route::get('/etablissement/index', [EtablissementController::class, 'index']);
Route::post('/etablissement/save', [EtablissementController::class, 'save']);
Route::post('/etablissement/getList', [EtablissementController::class, 'getList']);
Route::get('/config/edit/{id}', [EtablissementController::class, 'edit']);

//-----------------------------------------------gestion de cycle
Route::post('/cycle/getList', [CycleController::class,"getListCycle"]);
Route::post('/cycle/save', [CycleController::class,"save"]);
Route::get('/cycle/list', [CycleController::class,"liste"]);

//------------------------------------------------gestion filiere
Route::get('/filiere/index', [FiliereController::class,"index"]);
Route::post('/filiere/save', [FiliereController::class,"save"]);
Route::post('/filiere/getFiliere', [FiliereController::class,"getlistFiliere"]);
Route::post('/filiere/getCycle', [FiliereController::class,"getCycle"]);

//------------------------------------------------gestion classe
Route::get('/class/index', [ClasseController::class,"index"]);
Route::post('/class/save', [ClasseController::class,"save"]);
Route::post('/class/getClass', [ClasseController::class,"getlistClass"]);
Route::post('/class/getAll', [ClasseController::class,"getAll"]);

//---------------------------------------------------gestion eleves
Route::get('/student/liste',[StudentController::class,"index"]);
Route::get('/student/getFiliereByCycle',[StudentController::class,"getFiliereByCycle"]);
Route::get('/student/getClassByField',[StudentController::class,"getClassByField"]);
Route::post('/student/save',[StudentController::class,"save"]);
Route::post('/student/getStudent',[StudentController::class,"getStudent"]);
Route::post('/student/getAll',[StudentController::class,"getAll"]);
Route::get('/student/classlist',[StudentController::class,"getClassList"]);
Route::post('/student/AllStudent',[StudentController::class,"AllStudent"]);
Route::get('/student/getStudentByClass',[StudentController::class,"getStudentsByClasses"]);
Route::get('/student/getCoursesByClass', [StudentController::class,"getCoursesByClass"]);
Route::get('/student/getListClassNoteSearch', [StudentController::class,"getListClassNoteSearch"]);



//----------------------------------------------------------gestion payement
Route::get('/fees/index', [FeesController::class,"index"]);
Route::post('/fees/save', [FeesController::class,"save"]);
Route::post('/fees/getFees', [FeesController::class,"getlistFees"]);
Route::post('/class/getAllFees', [FeesController::class,"getAllStudenstFees"]);
Route::get('/student/getFiliereByCycle',[FeesController::class,"getFiliereByCycle"]);
Route::get('/student/getClassByField',[FeesController::class,"getClassByField"]);
Route::get('/student/getStudentByClass',[FeesController::class,"getStudentsByClasses"]);
Route::get('/fees/getFeesByClasses',[FeesController::class,"getFeesByClasses"]);
Route::post('/student/historiqueFees',[FeesController::class,"listHistorique"]);
Route::get('/fees/historique',[FeesController::class,"Historique"]);
Route::post('/student/getStudentName',[FeesController::class,"getStudentName"]);

//-------------------------------------------------------gestion des cours

Route::get('/courses/index', [CoursesController::class,"index"]);
Route::post('/courses/save', [CoursesController::class,"save"]);
Route::post('/courses/getCourses', [CoursesController::class,"getlistCourses"]);

//------------------------------------------------------repartion des cours par classes
Route::get('/courses/courses', [CoursesByClassController::class,"index"]);
Route::post('/coursesClass/save', [CoursesByClassController::class,"save"]);
Route::post('/courses/getCoursesByClass', [CoursesByClassController::class,"getlistCoursesByClass"]);


//-------------------------------------------------------------------gestion des evaluations
Route::post('/exam/getList', [EvaluationController::class,"getListExam"]);
Route::post('/exam/save', [EvaluationController::class,"save"]);
Route::get('/exam/list', [EvaluationController::class,"liste"]);
Route::get('/notes/all', [NoteController::class,"index"]);
Route::post('/notes/getList', [NoteController::class,"getListNote"]);
Route::post('/notes/save', [NoteController::class,"save"]);
Route::get('/notes/getNoteDetails', [NoteController::class,"getNoteDetails"]);
Route::get('/note/getCoursesByClass', [NoteController::class,"getCoursesByClass"]);
Route::get('/evaluation/getStudent', [NoteController::class,"getStudentsByClasses"]);
Route::post('/evaluation/SearchNoteByClasse', [NoteController::class,"SearchNoteByClasse"]);
Route::get('/evaluation/ListClassNote', [NoteController::class,"ListClassNote"]);


//---------------------------------------------------------gestion des emplois du temps
Route::get('/time/all', [TimeTableController::class,"index"]);
Route::post('/time/getList', [TimeTableController::class,"getListTime"]);
Route::post('/time/save', [TimeTableController::class,"save"]);
Route::get('/note/getCoursesByClass', [TimeTableController::class,"getCoursesByClass"]);
Route::get('/time/TimeList', [TimeTableController::class,"TimeList"]);
Route::post('/time/AllTimeList', [TimeTableController::class,"AllTimeTable"]);





Route::get('config/listeConfig',[EtablissementController::class, 'listeConfig']);

//--------------------------------------------- Dasboard
Route::get('/dashboard/index', [DashboardController::class,"index"]);
