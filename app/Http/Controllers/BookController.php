<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Interfaces\BookRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private BookRepositoryInterface $bookRepositoryInterface;

    public function __construct(BookRepositoryInterface $orderRepository)
    {
        $this->bookRepositoryInterface = $orderRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BookRequest $request
     * @return JsonResponse
     */
    public function store(BookRequest $request): JsonResponse
    {
        try {
            return response()->json(
                $this->bookRepositoryInterface->createBook($request),
            );
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BookRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(BookRequest $request, $id): JsonResponse
    {
        try {
            return response()->json(
                $this->bookRepositoryInterface->updateBook($request, $id),
            );
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            return response()->json(
                $this->bookRepositoryInterface->deleteBook($request)
            );
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Set like
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function like(Request $request): JsonResponse
    {
        try {
            return response()->json(
                $this->bookRepositoryInterface->like($request)
            );
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Get likes for user
     *
     * @return JsonResponse
     */
    public function getLikes(): JsonResponse
    {
        try {
            return response()
                ->json(
                    $this->bookRepositoryInterface->getLikes()
                );
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Get all books
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        try {
            return response()
                ->json(
                    $this->bookRepositoryInterface->getAll()
                );
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
