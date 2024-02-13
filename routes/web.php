<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReviewsController;
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

Route::get('/', [ProjectController::class, 'home'])->name('home');

Route::get('/view-projects', [ProjectController::class, 'index'])->name('projects-view');
Route::get('/project-reviews', [ReviewsController::class, 'index'])->name('reviews-view');
Route::get('/project/{id}', [ProjectController::class, 'show'])->name('project-show');
Route::get('/project-outputs/{id}', [ProjectController::class, 'outputTarget'])->name('project-targets');
Route::post('/query', [ProjectController::class, 'query'])->name('get_query');
Route::post('/filter-projects', [ProjectController::class, 'filterProject'])->name('filter-projects');
Route::post('/get-wards', [ProjectController::class, 'getWards'])->name('get-wards');
Route::get('/feedback/{id}', [ProjectController::class, 'getFeedback'])->name('get-feedback');
Route::post('/save-feedback', [ProjectController::class, 'saveFeedBack'])->name('save-feedback');

Route::get('/activity-targets', [ProjectController::class, 'activityBreakDown']);