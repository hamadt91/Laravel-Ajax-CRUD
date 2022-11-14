<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class BooksController extends Controller
{

    public function create()
    {
        $books = Book::all();
        return view('index', compact('books'));
    }

    public function store(Request $request)
    {
        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'successfully inserted',
            'data' => $book
        ]);
    }

    public function edit($id)
    {
        $book = Book::find($id);

        return response()->json([
            'status' => 'success',
            'data' => $book
        ]);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        $book->update([
            'title' => $request->model_title,
            'author' => $request->model_author,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $book
        ]);
    }

    public function destroy($id)
    {
        $book = Book::find($id);
        $book->delete();
        return response()->json([
            'status' => 'success',
            'data' => $id,
        ]);
    }
}
