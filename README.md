
# Praktikum Modul 3 ABP

🛠️ Langkah-langkah Instalasi

Install PHP dari C:/xampp/php dan tambahkan ke Environment Variable

Install Composer dari https://getcomposer.org/Composer-Setup.exe

## Installation Laravel

```bash
composer global require laravel/installer
```
```bash
laravel new relasi-demo
cd relasi-demo
```

## Setup .env terlebih dulu
```javascript
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=modul3
DB_USERNAME=root
DB_PASSWORD=
```
## Buat Model dan Migration
```bash
php artisan make:model Author -m
php artisan make:model Book -m
```
## Edit File Migration
📁 database/migrations/xxxx_xx_xx_create_authors_table.php
```javascript
Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamps();
});
```
📁 database/migrations/xxxx_xx_xx_create_books_table.php
```javascript
Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->year('year');
            $table->timestamps();
});
```
## Migrasi Ke DB
```bash
php artisan migrate
```
## Relationship Model
📁 app/Models/Author.php
```javascript
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
```
app/Models/Book.php
```javascript
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['author_id', 'title', 'year'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
```
## Buat Seeder untuk data Dummy
```bash
php artisan make:seeder AuthorSeeder
```
📁 database/seeders/AuthorSeeder.php
```javascript
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $authors = [
            ['name' => 'J.K. Rowling', 'email' => 'jkrowling@example.com'],
            ['name' => 'George R.R. Martin', 'email' => 'grrm@example.com'],
            ['name' => 'J.R.R. Tolkien', 'email' => 'tolkien@example.com'],
            ['name' => 'Agatha Christie', 'email' => 'agatha@example.com'],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }
    }
}
```
📁 database/seeders/DatabaseSeeder.php
```javascript
<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
{
    $this->call([
        AuthorSeeder::class,
    ]);
}
}
```

## Jalankan Seeder
```bash
php artisan db:seed
```

## Buat Route methode GET
📁 routes/web.php
```javascript
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
```

## Jalankan Laravel
```bash
php artisan serve
```

## Anda bisa mengaksesnya link seperti dibawah ini

http://localhost:8000/books

## Pastikan XAMPP Nyala ya ges


# Tampilan UI nya

## Buat Controllers
```bash
php artisan make:controller AuthorController
php artisan make:controller BookController
```
## Isi Authorcontroller tsb dengan
📁 app/Http/Controllers/AuthorController.php
```javascript
<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use DataTables;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $authors = Author::all();
        return view('authors.index', compact('authors'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:authors',
        ]);
        Author::create($request->all());
        return redirect()->route('authors.index')->with('success', 'Author created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
```
## Isi Bookcontroller tsb dengan
📁 app/Http/Controllers/BookController.php
```javascript
<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Author;
use DataTables;

use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $authors = Author::all();
        return view('books.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'year' => 'required|digits:4',
            'author_id' => 'required|exists:authors,id',
        ]);
    
        Book::updateOrCreate(
            ['id' => $request->book_id],
            $request->only('title', 'year', 'author_id')
        );
    
        return response()->json(['success' => 'Book saved successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return Book::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Book::findOrFail($id)->delete();
        return response()->json(['success' => 'Book deleted.']);
    }

    public function getBooks() {
        $books = Book::with('author')->get();
        return datatables()->of($books)
            ->addColumn('action', function($row){
                return '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-sm btn-primary editBook">Edit</a>
                        <a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-sm btn-danger deleteBook">Delete</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
```

## Buat Viewnya
Buat dulu beberapa section contohnya buat dulu folder layouts di resources/views/layouts/
📁 resources/views/layouts/app.blade.php
```javascript
<!DOCTYPE html>
<html>
<head>
    <title>Library Book Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @yield('content')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    @yield('scripts')
</body>
</html>
```
Terus buat juga folder resources/views/books/
📁 resources/views/books/index.blade.php
```javascript
@extends('layouts.app') {{-- pastikan kamu punya layout ini --}}
@section('content')
<div class="container mt-4">
    <h2>Book Management</h2>
    <button type="button" class="btn btn-success mb-3" id="createNewBook">Add Book</button>
    <table class="table table-bordered" id="bookTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Author</th>
                <th>Title</th>
                <th>Year</th>
                <th width="150px">Action</th>
            </tr>
        </thead>
    </table>
</div>

{{-- Modal --}}
<div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="bookForm" name="bookForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="bookModalLabel">Add Book</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="book_id" id="book_id">
          <div class="mb-3">
              <label>Author</label>
              <select class="form-select" name="author_id" id="author_id" required>
                  <option value="">-- Select Author --</option>
                  @foreach ($authors as $author)
                      <option value="{{ $author->id }}">{{ $author->name }}</option>
                  @endforeach
              </select>
          </div>
          <div class="mb-3">
              <label>Title</label>
              <input type="text" class="form-control" id="title" name="title" required>
          </div>
          <div class="mb-3">
              <label>Year</label>
              <input type="number" class="form-control" id="year" name="year" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    var table = $('#bookTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('books.getBooks') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'author.name', name: 'author.name'},
            {data: 'title', name: 'title'},
            {data: 'year', name: 'year'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#createNewBook').click(function () {
        $('#bookForm').trigger("reset");
        $('#bookModalLabel').html("Add Book");
        $('#book_id').val('');
        $('#bookModal').modal('show');
    });

    $('body').on('click', '.editBook', function () {
        var id = $(this).data('id');
        $.get("{{ url('books') }}" + '/' + id, function (data) {
            $('#bookModalLabel').html("Edit Book");
            $('#bookModal').modal('show');
            $('#book_id').val(data.id);
            $('#title').val(data.title);
            $('#year').val(data.year);
            $('#author_id').val(data.author_id);
        })
    });

    $('#bookForm').on('submit', function (e) {
        e.preventDefault();
        $('#saveBtn').html('Saving...');
        $.ajax({
            data: $('#bookForm').serialize(),
            url: "{{ route('books.store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                $('#bookForm').trigger("reset");
                $('#bookModal').modal('hide');
                table.draw();
                $('#saveBtn').html('Save');
            },
            error: function (data) {
                alert('Validation failed. Please check the fields.');
                $('#saveBtn').html('Save');
            }
        });
    });

    $('body').on('click', '.deleteBook', function () {
        var id = $(this).data("id");
        if (confirm("Are you sure want to delete this book?")) {
            $.ajax({
                type: "DELETE",
                url: "{{ url('books') }}" + '/' + id,
                success: function (data) {
                    table.draw();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });
});
</script>
@endsection
```
## Tambahin CSRF Protection di View 
resources/views/layouts/app.blade.php

### Tambahin dibagian <head><head/>
```javascript
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### Tambahin Ajax di script
```javascript
@section('scripts')
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
    ......
```

## Silahkan Jalankan Laravelnya
```bash
php artisan serve
```

## Akses Domain
http://localhost:8000/books

## Kalo masih ada pesan gagal/eror ikutin Instalasi dibawah ini
```bash
composer require yajra/laravel-datatables-oracle
```
## Tambahin script ini di config/app.php
```javascript
'providers' => [

    /*
     * Package Service Providers...
     */
    Yajra\DataTables\DataTablesServiceProvider::class, //Letakkan disini

    /*
     * Application Service Providers...
     */
    App\Providers\AppServiceProvider::class,
    // ...
],
```
## Jalankan
```bash
php artisan vendor:publish --provider="Yajra\DataTables\DataTablesServiceProvider"
```
## Silahkan cek lagi harusnya bisa 😂
