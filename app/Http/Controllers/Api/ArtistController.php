<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArtistRequest;
use App\Models\Artist;
use Illuminate\Http\JsonResponse;

class ArtistController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'admin'])->only(['store', 'update', 'destroy']);
    }

    public function index(): JsonResponse
    {
        return response()->json(Artist::with(['albums', 'albums.songs'])->paginate(10));
    }

    public function store(ArtistRequest $request): JsonResponse
    {
        return response()->json(Artist::create($request->all()), 201);
    }

    public function show(Artist $artist): JsonResponse
    {
        return response()->json($artist->load(['albums', 'albums.songs']));
    }

    public function update(ArtistRequest $request, Artist $artist): JsonResponse
    {
        $artist->update($request->all());
        return response()->json($artist);
    }

    public function destroy(Artist $artist): JsonResponse
    {
        $artist->delete();
        return response()->json('Artist has been deleted', 204);
    }
}
