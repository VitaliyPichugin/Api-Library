<?php

namespace App\Http\Controllers;

use App\Interfaces\BookRepositoryInterface;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    private BookRepositoryInterface $repository;

    public function __construct(BookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $text
     * @return JsonResponse
     */
    public function search($text): JsonResponse
    {
        return response()->json(
            $this->repository->search($text)
        );
    }
}
