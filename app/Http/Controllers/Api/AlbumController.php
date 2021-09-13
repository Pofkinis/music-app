<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumRequest;
use App\Models\Album;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Album::paginate(10));
    }

    public function store(AlbumRequest $request): JsonResponse
    {
        return response()->json(Album::create($request->all()));
    }

    public function show($id): JsonResponse
    {
        try {
            return response()->json(Album::findorfail($id));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Album not found'
            ], 404);
        }
    }

    public function update(AlbumRequest $request, $id): JsonResponse
    {
        try {
            $artist = Album::findorfail($id);
            $artist->update($request->all());
            return response()->json($artist);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Album not found'
            ], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Album::findorfail($id)->delete();
            return response()->json('Album has been deleted', 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Album not found'
            ], 404);
        }
    }
}
