<?php

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
use App\Models\Author;
use App\Models\Book;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
// Author CRUD
Route::resource('authors', AuthorController::class);

// Book CRUD (AJAX)
Route::resource('books', BookController::class);
Route::get('/get-books', [BookController::class, 'getBooks'])->name('books.getBooks');

Route::get('/', function () {
    return view('welcome');
});
