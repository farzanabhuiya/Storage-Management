<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PdfController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\Api\FolderController;
use App\Http\Controllers\Api\StorageController;
use App\Http\Controllers\Api\FavoriteController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


                  //    LoginController Route
Route::controller(LoginController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::middleware('auth:sanctum')->post('/logout', 'logOut');
});


                   //   floder route
 Route::middleware('auth:sanctum')->group(function () {
    Route::get('/folders/count', [FolderController::class, 'index']);
    Route::post('/folders', [FolderController::class, 'store']);
    Route::delete('/folders/{id}', [FolderController::class, 'destroy']);
 });

       


                //  note route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notes/count', [NoteController::class, 'index']);  ///note count
    Route::post('/add/notes', [NoteController::class, 'store']);
    Route::put('/notes/{id}', [NoteController::class, 'update']);
    Route::delete('/notes/{id}', [NoteController::class, 'destroy']);

    Route::get('/notes-all', [NoteController::class, 'all']); //all note route
});






               //   image route
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/images', [ImageController::class, 'store']);     
    Route::get('/images/count', [ImageController::class, 'index']); /// image count

     Route::get('/images-all', [ImageController::class, 'all']);  //all image
});




                    //    pdf route
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/add/pdfs', [PdfController::class, 'store']);     
    Route::get('/pdfs/count', [PdfController::class, 'index']); //all pdf count 
      
    Route::get('/pdfs-all', [PdfController::class, 'all']);    // Get All pdf
});

                    //  storage route

Route::middleware('auth:sanctum')->get('/storage-summary', [StorageController::class, 'getStorageSummary']);



        // favorites route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/favorites-all', [FavoriteController::class, 'index']);
    Route::post('/add/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites', [FavoriteController::class, 'destroy']);
});

            // file route
     Route::middleware('auth:sanctum')->group(function () {
    Route::post('/file/lock', [FileController::class, 'lock']);
    Route::post('/file/unlock', [FileController::class, 'unlock']);
});

