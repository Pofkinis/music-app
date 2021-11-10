<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Song;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getSongComments(Song $song): JsonResponse
    {
        return response()->json($song->comments);
    }

    public function store(CommentRequest $request): JsonResponse
    {
        return response()->json(auth()->user()->comments()->create($request->all()), 201);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        if (auth()->user()->can('delete', $comment)) {
            $comment->delete();
            return response()->json('Comment has been deleted', 204);
        }

        return response()->json('Unauthorized', 401);
    }
}
