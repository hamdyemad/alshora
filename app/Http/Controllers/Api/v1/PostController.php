<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use Res;

    public function index()
    {
        $per_page = request('per_page') ?? 0;

        $posts = Post::with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->latest();

        $posts = ($per_page > 0) ? $posts->paginate($per_page) : $posts->get();

        // Handle paginated response
        if($per_page > 0) {
            $data = [
                'data' => PostResource::collection($posts->items()),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                    'from' => $posts->firstItem(),
                    'to' => $posts->lastItem(),
                ]
            ];
            return $this->sendRes(__('validation.success'), true, $data);
        }

        return $this->sendRes(__('validation.success'), true, PostResource::collection($posts));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'content' => $request->content,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image'] = $path;
        }

        $post = Post::create($data);
        $post->load('user'); // Load user for resource

        return $this->sendRes(__('validation.created_successfully'), true, new PostResource($post));
    }

    public function show($id)
    {
        $post = Post::with(['user', 'comments.user', 'likes'])
            ->withCount(['likes', 'comments'])
            ->find($id);

        if (!$post) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        return $this->sendRes(__('validation.success'), true, new PostResource($post));
    }

    public function update(Request $request, $id)
    {
        $post = Post::where('user_id', auth()->id())->find($id);

        if (!$post) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = ['content' => $request->content];

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('posts', 'public');
            $data['image'] = $path;
        }

        $post->update($data);
        $post->load('user');

        return $this->sendRes(__('validation.updated_successfully'), true, new PostResource($post));
    }

    public function destroy($id)
    {
        $post = Post::where('user_id', auth()->id())->find($id);

        if (!$post) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return $this->sendRes(__('validation.deleted_successfully'), true);
    }
}
