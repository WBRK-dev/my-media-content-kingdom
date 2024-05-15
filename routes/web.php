<?php

use Illuminate\Support\Facades\Route;

// Controllers/Resource Controllers
use App\Http\Controllers\VideoController;

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

Route::get('/', function () {
    return view('home');
});

Route::get("/upload", [VideoController::class, "create"]);
Route::post("/upload", [VideoController::class, "store"]);

Route::get("/watch/{videoid}/{resolution}/{filename}", [VideoController::class, "show"]);
Route::get("/watch/{videoid}/{filename}", [VideoController::class, "showPlaylist"]);