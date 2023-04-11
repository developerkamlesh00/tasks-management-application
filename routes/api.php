<?php


use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterOrganization;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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
Route::middleware('auth:api')->prefix('/director')->name('director.')->group(function () {
    Route::get('/managers/{org}', [UserController::class, 'managers']);
    Route::get('/projects/{org}', [ProjectController::class, 'getprojects'])->name('getprojects');
    Route::post('register', [UserController::class, 'store']);
    Route::post('createproject', [ProjectController::class, 'store'])->name('addproject');
    Route::post('updateproject/{projectid}', [ProjectController::class, 'update'])->name('updateproject');

    Route::post('/import', [UserController::class, 'import'])->name('import');
});


//register Oraganization end point
Route::post('/orgregister', [RegisterOrganization::class, 'register']);
Route::middleware('auth:api')->prefix('/admin')->name('admin.')->group(function () {
    // Get organizations,directors,managers,workers,all member of organization
    Route::get('/organizations', [AdminController::class, 'get_organizations']);
    Route::get('/directors', [AdminController::class, 'get_directors']);
    Route::get('/managers', [AdminController::class, 'get_managers']);
    Route::get('/workers', [AdminController::class, 'get_workers']);
    Route::get('/user-data', [AdminController::class, 'user_data']);
    Route::get('/recent-users', [AdminController::class, 'recent_users']);
   
    // Route::get('/organizations/{id}/members', [AdminController::class, 'get_organization_members']);
    //Delete user
    Route::post('/users/{id}', [AdminController::class, 'destroy']);
});




// Not to be used
Route::get('/tasks', [TaskController::class, 'all_tasks']);
// Get all tasks of a worker (from all projects)
Route::get('/worker/{worker_id}/tasks', [TaskController::class, 'worker_tasks']);
// Get all tasks of a particular project - For a manager
Route::get('/project/{project_id}/tasks', [TaskController::class, 'project_tasks']);
// Get all tasks belonging to one project - For a worker
Route::get('/worker/{worker_id}/project/{project_id}/tasks', [TaskController::class, 'worker_project_tasks']);
// Change Task Status
Route::post('update_status/task/{task_id}/status/{status_id}', [TaskController::class, 'update_status']);