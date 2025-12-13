<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Traits\Res;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use Res;


    public function index(Request $request, $post_id) {
        $comments = Comment::where('post_id', $post_id)->get();
        return $this->sendRes(__('validation.success'), true, CommentResource::collection($comments));
    }
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);
        return $this->sendRes(__('validation.created_successfully'), true, new CommentResource($comment));
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::where('user_id', auth()->id())->find($id);

        if (!$comment) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update(['content' => $request->content]);
        $comment->load('user');

        return $this->sendRes(__('validation.updated_successfully'), true, new CommentResource($comment));
    }

    public function destroy($id)
    {
        $comment = Comment::where('user_id', auth()->id())->find($id);

        if (!$comment) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        $comment->delete();

        return $this->sendRes(__('validation.deleted_successfully'), true);
    }
}
