<?php

namespace App\Interfaces;

use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;

interface BookRepositoryInterface
{
    public function createBook(BookRequest $request);
    public function updateBook(BookRequest $request, $id);
    public function deleteBook(Request $request);
    public function like(Request $request);
    public function getLikes();
    public function getAll();
    public function search($text);
}
