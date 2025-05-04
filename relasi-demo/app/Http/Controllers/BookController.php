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
