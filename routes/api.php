<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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

Route::post('/tasks/create', [TaskController::class, 'create']);
Route::post('/tasks/get/list/sort/{orderBy}', [TaskController::class, 'getTaskList']);
Route::post('/tasks/get/id/{id}', [TaskController::class, 'getTaskById']);

Route::post('/templates/render/{templateName}', function (Request $request, $templateName) {
    $renderedView = View::make('js-templates.' . $templateName, ['data' => $request->input()])->render();
    return response()->json($renderedView);
});
