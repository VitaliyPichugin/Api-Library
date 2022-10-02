<?php

namespace App\Repositories;

use App\Interfaces\LibraryRepositoryInterface;
use App\Models\Book;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LibraryRepository implements LibraryRepositoryInterface
{
    /**
     * @param Request $request
     * @param $lib_id
     * @return mixed
     */
    public function attachBook(Request $request, $lib_id) : Library
    {
        $lib = Library::find($lib_id);
        $update = [];
        foreach ($request->all() as $item) {
            $update[$item['id']] = ['stock' => $item['stock']];
        }
        $lib->books()->sync($update);
        return $lib;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createLibrary(Request $request) : mixed
    {
        $request->validate(['name' => ['required', 'string']]);
        return Library::insert(['name' => $request->name]);
    }

    public function deleteLibrary($id)
    {
        $lib = Library::find($id);
        $book_ids = $lib->books()->get()->pluck('id');

        if ($book_ids->count()) {
            $lib->books()->detach($book_ids);
            Book::whereIn('id', $book_ids)->doesntHave('library')->delete();
        }
        $lib->delete();
        return $lib;
    }

    /**
     * @param $book_id
     * @param $lib_id
     * @return mixed
     */
    public function detachBook($book_id, $lib_id) : Library
    {
        $lib = Library::find($lib_id);
        $lib->books()->detach([$book_id]);
        Book::whereIn('id', [$book_id])->doesntHave('library')->delete();
        return $lib;
    }

    /**
     * @return Collection
     */
    public function getAll() : Collection
    {
        return Library::with('books')->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getLibrary($id) : Library
    {
        return Library::with('books')
            ->whereId($id)
            ->get();
    }

    /**
     * @param Request $request
     * @param $lib_id
     * @return mixed
     */
    public function updateName(Request $request, $lib_id) : mixed
    {
        return  Library::whereId($lib_id)->update(['name' => $request->name]);
    }
}
