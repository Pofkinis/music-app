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
        $this->middleware(['auth:api', 'admin'])->only(['store', 'update', 'destroy']);
    }

    public function index(): JsonResponse
    {
        return response()->json(Album::with(['artist', 'songs'])
            ->when(request('search', '') != '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'LIKE', '%' . request('search') . '%')
                        ->orWhere('release_date', 'LIKE', '%' . request('search') . '%')
                        ->orWhereHas('artist', function ($qry) {
                            $qry->where('first_name', 'LIKE', '%' . request('search') . '%')
                                ->orWhere('last_name', 'LIKE', '%' . request('search') . '%');
                        });
                });
            })
            ->paginate(10));
    }

    public function store(AlbumRequest $request): JsonResponse
    {
        return response()->json(Album::create($request->all()), 201);
    }

    public function show(Album $album): JsonResponse
    {
        return response()->json($album->load(['artist', 'songs']));
    }

    public function update(AlbumRequest $request, Album $album): JsonResponse
    {
        $album->update($request->all());
        return response()->json($album);
    }

    public function destroy(Album $album): JsonResponse
    {
        if ($album->songs()->exists()) {
            return response()->json('Cannot delete album', 409);
        }
        $album->delete();
        return response()->json('Album has been deleted', 204);
    }
}
