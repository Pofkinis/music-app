<?php

use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PlaylistController;
use App\Http\Controllers\Api\SongController;
use App\Http\Controllers\AuthController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('refresh', [AuthController::class, 'refreshToken']);
Route::apiResource('artists', ArtistController::class);
Route::apiResource('albums', AlbumController::class);
Route::apiResource('songs', SongController::class);
Route::get('comments/{song}', [CommentController::class, 'getSongComments'])->name('comments.get-by-song');
Route::get('artist/{artist}/songs', [SongController::class, 'getSongByArtist'])->name('songs.by-artist');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('song/add-to-playlist/{song}', [PlaylistController::class, 'addSong'])->name('song.add-to-list');
    Route::get('song/remove-from-playlist/{song}', [PlaylistController::class, 'removeSong'])->name('song.remove-from-list');
    Route::resource('comments', CommentController::class);
});
