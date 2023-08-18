<?php

use App\Models\Group;
use App\Models\Manga;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', function () {
//     return view('frontend.home');
// })->name('fe.home');

Auth::routes([
    'register' => true,
    'verify' => false,
    'reset' => false
]);
Route::prefix('/')->name('fe.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('list');
        Route::get('/add', [CategoryController::class, 'getCreate'])->name('getAdd');
        Route::post('/add', [CategoryController::class, 'postCreate'])->name('postAdd');
        Route::get('/delete/{category}', [CategoryController::class, 'delete'])->name('delete');
        Route::get('/edit/{category}', [CategoryController::class, 'getEdit'])->name('getEdit');
        Route::post('/edit/{category}', [CategoryController::class, 'postEdit'])->name('postEdit');
    });
    Route::prefix('manga')->name('manga.')->group(function () {
        Route::get('/', [MangaController::class, 'index'])->name('list');
        Route::get('/add', [MangaController::class, 'getCreate'])->name('getAdd');
        Route::post('/add', [MangaController::class, 'postCreate'])->name('postAdd');
        Route::get('/delete/{manga}', [MangaController::class, 'delete'])->name('delete');
        Route::get('/edit/{manga}', [MangaController::class, 'getEdit'])->name('getEdit');
        Route::post('/edit/{manga}', [MangaController::class, 'postEdit'])->name('postEdit');
    });

    Route::prefix('chapter')->name('chapter.')->group(function () {
        Route::get('/{manga}', [ChapterController::class, 'index'])->name('list');
        Route::get('/add/{manga}', [ChapterController::class, 'getAdd'])->name('getAdd');
        Route::post('/add/{manga}', [ChapterController::class, 'postAdd'])->name('postAdd');
        Route::get('/delete/{chapter}', [ChapterController::class, 'delete'])->name('delete');
        Route::get('/edit/{chapter}', [ChapterController::class, 'getEdit'])->name('getEdit');
        Route::post('/edit/{chapter}', [ChapterController::class, 'postEdit'])->name('postEdit');
        Route::post('getTemp', [ChapterController::class, 'getTempChapter'])->name('getTempChapter');
    });
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('list');
        Route::get('/add', [UserController::class, 'getAdd'])->name('getAdd');
        Route::post('/add', [UserController::class, 'postAdd'])->name('postAdd');
        Route::get('/edit/{user}', [UserController::class, 'getEdit'])->name('getEdit');
        Route::post('/edit/{user}', [UserController::class, 'postEdit'])->name('postEdit');
        Route::get('/delete/{user}', [UserController::class, 'delete'])->name('delete');
    });
    Route::prefix('group')->name('group.')->group(function () {
        Route::get('/', [GroupController::class, 'index'])->name('list');
        Route::get('/add', [GroupController::class, 'getAdd'])->name('getAdd')->can('group.add');
        Route::post('/add', [GroupController::class, 'postAdd'])->name('postAdd')->can('group.add');
        Route::get('/edit/{group}', [GroupController::class, 'getEdit'])->name('getEdit')->can('group.edit');
        Route::post('/edit/{group}', [GroupController::class, 'postEdit'])->name('postEdit')->can('group.edit');
        Route::get('/delete/{group}', [GroupController::class, 'delete'])->name('delete')->can('group.delete');
    });
    Route::get('/', [DashboardController::class, 'index'])->name('home');
});
// Route::group([
//     'name' => 'admin.',
//     'prefix' => 'admin',
//     'middleware' => 'auth',
// ], function () {
//     Route::get('/', function () {
//         return view('backend.home');
//     })->name('home');
// });