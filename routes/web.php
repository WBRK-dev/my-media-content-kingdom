<?php

use App\Http\Controllers\WatchController;
use Illuminate\Support\Facades\Route;

// Controllers/Resource Controllers
use App\Http\Controllers\VideoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;


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

require __DIR__.'/auth.php';

Route::get("/", [HomeController::class, "show"]);

Route::get('/search', [SearchController::class, "search"]);

Route::get("/upload", [VideoController::class, "create"]);
Route::post("/upload", [VideoController::class, "store"]);

Route::delete("/delete/{video}", [VideoController::class, "destroy"]);

Route::get("/watch", [WatchController::class, "show"]);

Route::get("/watch/{videoid}/{resolution}/{filename}", [VideoController::class, "show"]);
Route::get("/watch/{videoid}/{filename}", [VideoController::class, "showPlaylist"]);
Route::get("/api/watch/liked", [WatchController::class, "videoLiked"]);
Route::get("/api/watch/disliked", [WatchController::class, "videoDisliked"]);
Route::get("/api/watch/like_rem_row", [WatchController::class, "deleteLikeRow"]);
Route::post("/api/watch/report_video", [WatchController::class, "videoReported"]);

Route::view("/login", "login");