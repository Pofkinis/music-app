<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArtistRequest;
use App\Models\Artist;
use Illuminate\Http\JsonResponse;

class ArtistController extends Controller
{
    public function index()
    {
        return Artist::paginate(10);
    }

    public function store(ArtistRequest $request)
    {
        return Artist::create($request->all());
    }

    public function show($id): JsonResponse
    {
        try {
            return Artist::findorfail($id);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Artist not found'
            ], 404);
        }

    }

    public function update(ArtistRequest $request, $id): JsonResponse
    {
        try {
            $artist = Artist::findorfail($id)->update([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'birth_date' => $request->get('birth_date'),
            ]);

            return $artist;
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Artist not found'
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            Artist::findorfail($id)->delete();
            return response()->json([
                'success' => 'Artist deleted'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Artist not found'
            ], 404);
        }
    }
}
