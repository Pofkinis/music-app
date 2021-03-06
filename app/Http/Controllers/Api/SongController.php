<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SongRequest;
use App\Models\Artist;
use App\Models\Song;
use Illuminate\Http\JsonResponse;

class SongController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'admin'])->only(['store', 'update', 'destroy']);
    }

    public function getSongByArtist(Artist $artist): JsonResponse
    {
        return response()->json($artist->albums()->paginate(10));
    }

    public function index(): JsonResponse
    {
        return response()->json(Song::with(['album', 'album.artist'])
            ->when(request('search', '') != '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'LIKE', '%' . request('search') . '%')
                        ->orWhere('type', 'LIKE', '%' . request('search') . '%')
                        ->orWhereHas('album', function ($qry) {
                            $qry->where('name', 'LIKE', '%' . request('search') . '%')
                                ->orWhereHas('artist', function ($query) {
                                    $query->where('first_name', 'LIKE', '%' . request('search') . '%')
                                        ->orWhere('last_name', 'LIKE', '%' . request('search') . '%');
                                });
                        });
                });
            })
            ->paginate(10));
    }

    public function store(SongRequest $request): JsonResponse
    {
        return response()->json(Song::create($request->all()), 201);
    }

    public function show(Song $song): JsonResponse
    {
        return response()->json($song->load(['album', 'album.artist']));
    }

    public function update(SongRequest $request, Song $song): JsonResponse
    {
        $song->update($request->all());
        return response()->json($song);
    }

    public function destroy(Song $song): JsonResponse
    {
        $song->delete();
        return response()->json('Song has been deleted', 204);
    }
}
