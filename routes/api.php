<?php

use App\Http\Controllers\api\v1\ProjectController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/**
 * search area routes
 */
Route::get('/sub-counties-financial-years', [ProjectController::class, 'subCountiesAndFinancialYearsData'])->name('sub-counties-financial-years');
Route::get('/get-wards/{sub_county_id}', [ProjectController::class, 'getWards']);

/**
 * landing page routes
 */
Route::get('/projects', [ProjectController::class, 'index'])->name('projects-all');
Route::post('/projects/filter', [ProjectController::class, 'query'])->name('filter-projects');

/**
 * projects page data
 */
Route::get('/all-projects', [ProjectController::class, 'allProjects']);
Route::post('/all-projects/filter', [ProjectController::class, 'filter']);

/**
 * feedback
 */
Route::post('/feedback/add', [ProjectController::class, 'saveFeedBack']);