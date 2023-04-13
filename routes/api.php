<?php


use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterOrganization;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagerController;
//use Illuminate\Database\Capsule\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/login', [UserController::class, 'login']);
Route::get('/login', [UserController::class, 'login'])->name('login'); //if not auth or first login

//director section apis calls
Route::post('/orgregister', [RegisterOrganization::class, 'register']);
Route::middleware('auth:api')->prefix('/director')->name('director.')->group(function(){
    Route::get('/managers/{org}', [UserController::class, 'managers']);
    Route::get('/projects/{org}', [ProjectController::class, 'getprojects'])->name('getprojects');
    Route::post('register', [UserController::class, 'store']);
    Route::post('createproject', [ProjectController::class,'store'])->name('addproject');
    Route::post('updateproject/{projectid}', [ProjectController::class, 'update'])->name('updateproject');

    Route::post('/import',[UserController::class,'import'])->name('import');
});


//register Oraganization end point
Route::post('/orgregister', [RegisterOrganization::class, 'register']);
Route::middleware('auth:api')->prefix('/admin')->name('admin.')->group(function(){
// Get organizations,directors,managers,workers,all member of organization
Route::get('/organizations',[AdminController::class, 'get_organizations']);
Route::get('/directors',[AdminController::class, 'get_directors']);
Route::get('/managers',[AdminController::class, 'get_managers']);
Route::get('/workers',[AdminController::class, 'get_workers']);
Route::get('/organizations/{id}/members',[AdminController::class, 'get_organization_members']);
});
// Route::get('/admin/users',[AdminController::class, 'get_users']);

//Delete user
Route::post('/admin/users/{id}', [AdminController::class, 'destroy']);
   

// Not to be used
Route::get('/tasks',[TaskController::class, 'all_tasks']);
// Get all tasks of a worker (from all projects)
Route::get('/worker/{worker_id}/tasks',[TaskController::class, 'worker_tasks']);
// Get all tasks of a particular project - For a manager
Route::get('/project/{project_id}/tasks',[TaskController::class, 'project_tasks']);
// Get all tasks belonging to one project - For a worker
Route::get('/worker/{worker_id}/project/{project_id}/tasks',[TaskController::class, 'worker_project_tasks']);
// Change Task Status
Route::post('update_status/task/{task_id}/status/{status_id}',[TaskController::class, 'update_status']);

//Comments for a particular Task
Route::get('task/{task_id}/comments',[CommentController::class, 'task_comments']);


//manager end points
Route::get('project', [ManagerController::class, 'projects']);
Route::get('worker', [ManagerController::class, 'workers']);
Route::get('single_worker', [ManagerController::class, 'single_worker']);
Route::get('single_project', [ManagerController::class, 'single_project']);
Route::get('tasks', [ManagerController::class, 'tasks']);

Route::post('add_task', [ManagerController::class, 'add_task'] );
Route::put('edit_task', [ManagerController::class, 'edit_task'] );
Route::delete('delete_task/{id}', [ManagerController::class, 'delete_task'] );

Route::get('assigned_tasks', [ManagerController::class, 'get_assigned_tasks']);
Route::get('review_tasks', [ManagerController::class, 'review_task']);