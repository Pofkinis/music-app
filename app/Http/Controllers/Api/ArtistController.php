<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArtistRequest;
use App\Models\Artist;
use Illuminate\Http\JsonResponse;

class ArtistController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Artist::paginate(10));
    }

    public function store(ArtistRequest $request): JsonResponse
    {
        return response()->json(Artist::create($request->all()));
    }

    public function show(Artist $artist): JsonResponse
    {
        return response()->json($artist);
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
