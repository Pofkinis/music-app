<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumRequest;
use App\Models\Album;
use Illuminate\Http\JsonResponse;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'admin'])->only(['store', 'update', 'destroy']);
    }

    public function index(): JsonResponse
    {
        return response()->json(Album::paginate(10));
    }

    public function store(AlbumRequest $request): JsonResponse
    {
        return response()->json(Album::create($request->all()));
    }

    public function show(Album $album): JsonResponse
    {
        return response()->json($album);
    }

    public function update(AlbumRequest $request, Album $album): JsonResponse
    {
        $album->update($request->all());
        return response()->json($album);
    }

    public function destroy(Album $album): JsonResponse
    {
        $album->delete();
        return response()->json('Album has been deleted', 204);
    }
}
