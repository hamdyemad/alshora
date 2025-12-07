<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Traits\Res;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    use Res;

    public function toggle(Request $request)
    {
        $request->validate([
            'type' => 'required|in:post,comment',
            'id' => 'required|integer',
        ]);

        $modelClass = $request->type === 'post' ? \App\Models\Post::class : \App\Models\Comment::class;
        $model = $modelClass::find($request->id);

        if (!$model) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        $existingLike = Like::where('user_id', auth()->id())
            ->where('likeable_id', $request->id)
            ->where('likeable_type', $modelClass)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $message = __('validation.unliked_successfully');
            $liked = false;
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'likeable_id' => $request->id,
                'likeable_type' => $modelClass,
            ]);
            $message = __('validation.liked_successfully');
            $liked = true;
        }

        return $this->sendRes($message, true, ['liked' => $liked]);
    }
}
