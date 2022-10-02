<?php

namespace App\Repositories;

use App\Http\Requests\BookRequest;
use App\Interfaces\BookRepositoryInterface;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class BookRepository implements BookRepositoryInterface
{
    /**
     * @param BookRequest $request
     * @return Book
     */
    public function createBook(BookRequest $request) : Book
    {
        $request->validated();
        $update = [];
        $book = new Book();
        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->save();
        foreach ($request->libs as $item) {
            $update[$item['id']] = ['stock' => $request->stock];
        }
        $book->library()->attach($update);
        return $book;
    }

    /**
     * @param BookRequest $request
     * @param $id
     * @return mixed
     */
    public function updateBook(BookRequest $request, $id) : mixed
    {
        $request->validated();
        $update = [];
        $book = Book::find($id);
        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->save();

        foreach ($request->libs as $lib) {
            $update[$lib['id']] = ['stock' => $lib['stock'] ?? 1];
        }

        $book->library()->sync($update);
        return $book;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function deleteBook(Request $request) : mixed
    {
        $book = Book::find($request->id);
        $book->library()->detach($request->lib_id);
        if (!$book->library()->count()) {
            $book->delete();
        }
        return $book;
    }

    /**
     * @param Request $request
     * @return int
     */
    public function like(Request $request) : int
    {
        $res = auth()
            ->user()
            ->likedBooks()
            ->toggle($request->id);
        if (in_array($request->id, $res['attached'])) {
            return 1;
        }
        return 0;
    }

    /**
     * @return mixed
     */
    public function getLikes() : mixed
    {
        return auth()
            ->user()
            ->likedBooks()
            ->get()
            ->pluck('id');
    }

    /**
     * @return mixed
     */
    public function getAll() : mixed
    {
        return Book::select(['id', 'title'])->get();
    }

    /**
     * @param $text
     * @return Collection
     */
    public function search($text) : Collection
    {
        return Book::with('library')
            ->where('title', 'like', '%' . $text . '%')
            ->orWhere('description', 'like', '%' . $text . '%')
            ->get();
    }
}
