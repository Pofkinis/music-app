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

    public function show($id): JsonResponse
    {
        try {
            return response()->json(Artist::findorfail($id));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Artist not found'
            ], 404);
        }
    }

    public function update(ArtistRequest $request, $id): JsonResponse
    {
        try {
            $artist = Artist::findorfail($id);
            $artist->update($request->all());
            return response()->json($artist);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Artist not found'
            ], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Artist::findorfail($id)->delete();
            return response()->json('Artist has been deleted', 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Artist not found'
            ], 404);
        }
    }
}
