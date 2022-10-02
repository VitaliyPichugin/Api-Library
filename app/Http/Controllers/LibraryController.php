<?php

namespace App\Http\Controllers;

use App\Interfaces\LibraryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LibraryController extends Controller
{

    private LibraryRepositoryInterface $libraryRepositoryInterface;

    public function __construct(LibraryRepositoryInterface $libraryRepository)
    {
        $this->libraryRepositoryInterface = $libraryRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            return response()->json(
                $this->libraryRepositoryInterface->createLibrary($request)
            );
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json(
                $this->libraryRepositoryInterface->getAll()
            );
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            return response()->json(
                $this->libraryRepositoryInterface->deleteLibrary($id)
            );
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        return response()->json(
            $this->libraryRepositoryInterface->getLibrary($id)
        );
    }

    /**
     * Detach specific book
     *
     * @param $book_id
     * @param $lib_id
     * @return JsonResponse
     */
    public function detach($book_id, $lib_id): JsonResponse
    {
        try {
            return response()->json(
                $this->libraryRepositoryInterface->detachBook($book_id, $lib_id)
            );
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Attach specific book
     *
     * @param Request $request
     * @param $lib_id
     * @return JsonResponse
     */
    public function attach(Request $request, $lib_id): JsonResponse
    {
        try {
            return response()->json(
                $this->libraryRepositoryInterface->attachBook($request, $lib_id)
            );
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Update lib name
     *
     * @param Request $request
     * @param $lib_id
     * @return JsonResponse
     */
    public function update(Request $request, $lib_id): JsonResponse
    {
        try {
            return response()->json(
                $this->libraryRepositoryInterface->updateName($request, $lib_id)
            );
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
