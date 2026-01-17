<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Dislike;
use App\Models\Lawyer;
use App\Traits\Res;
use Illuminate\Http\Request;

class DislikeController extends Controller
{
    use Res;

    public function toggle(Request $request)
    {
        $request->validate([
            'type' => 'required|in:post,comment,lawyer',
            'id' => 'required|integer',
        ]);

        $modelClass = match($request->type) {
            'post' => \App\Models\Post::class,
            'comment' => \App\Models\Comment::class,
            'lawyer' => \App\Models\Lawyer::class,
        };
        
        $model = $modelClass::find($request->id);

        if (!$model) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        $existingDislike = Dislike::where('user_id', auth()->id())
            ->where('dislikeable_id', $request->id)
            ->where('dislikeable_type', $modelClass)
            ->first();

        if ($existingDislike) {
            $existingDislike->delete();
            $message = __('validation.undisliked_successfully');
            $disliked = false;
        } else {
            Dislike::create([
                'user_id' => auth()->id(),
                'dislikeable_id' => $request->id,
                'dislikeable_type' => $modelClass,
            ]);
            $message = __('validation.disliked_successfully');
            $disliked = true;
        }

        $dislikesCount = Dislike::where('dislikeable_id', $request->id)
            ->where('dislikeable_type', $modelClass)
            ->count();

        return $this->sendRes($message, true, [
            'disliked' => $disliked,
            'dislikes_count' => $dislikesCount
        ]);
    }
}
