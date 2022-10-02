<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface LibraryRepositoryInterface
{
    public function createLibrary(Request $request);
    public function getAll();
    public function deleteLibrary($id);
    public function getLibrary($id);
    public function detachBook($book_id, $lib_id);
    public function attachBook(Request $request, $lib_id);
    public function updateName(Request $request, $lib_id);
}
