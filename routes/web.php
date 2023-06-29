<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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

Route::get('/', function () {
    return view('tasks');
})->middleware(['auth'])->name('tasks');

Route::post('/tasks/create', [TaskController::class, 'create']);
Route::post('/tasks/get/list', [TaskController::class, 'getTaskList']);
Route::post('/tasks/get/id/{id}', [TaskController::class, 'getTaskById']);

require __DIR__.'/auth.php';
