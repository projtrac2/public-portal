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

/**
 * chart data
 */
Route::get('/chart-data-one', [ProjectController::class, 'projectDistributionPerSubCounty']);
Route::post('/chart-data-one/query', [ProjectController::class, 'projectDistributionPerSubCountyQuery']);
Route::get('/chart-data-two', [ProjectController::class, 'budgetAllocationPerFinancialYear']);
Route::post('/chart-data-two/query', [ProjectController::class, 'budgetAllocationPerFinancialYearQuery']);
Route::get('/chart-data-three', [ProjectController::class, 'budgetAllocationPerDept']);
Route::post('/chart-data-three/query', [ProjectController::class, 'budgetAllocationPerDeptQuery']);

Route::get('/output-targets/{id}', [ProjectController::class, 'outputTarget'])->name('output_targets');

Route::get('/date-distribution', [ProjectController::class, 'yearDistribution']);