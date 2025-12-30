<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\Lawyer;
use App\Traits\Res;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    use Res;

    /**
     * Toggle follow/unfollow a lawyer
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'lawyer_id' => 'required|integer|exists:lawyers,id',
        ]);

        $lawyer = Lawyer::find($request->lawyer_id);

        if (!$lawyer) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        $existingFollow = Follow::where('user_id', auth()->id())
            ->where('lawyer_id', $request->lawyer_id)
            ->first();

        if ($existingFollow) {
            $existingFollow->delete();
            $message = __('validation.unfollowed_successfully');
            $followed = false;
        } else {
            Follow::create([
                'user_id' => auth()->id(),
                'lawyer_id' => $request->lawyer_id,
            ]);
            $message = __('validation.followed_successfully');
            $followed = true;
        }

        $followersCount = Follow::where('lawyer_id', $request->lawyer_id)->count();

        return $this->sendRes($message, true, [
            'followed' => $followed,
            'followers_count' => $followersCount
        ]);
    }

    /**
     * Get list of lawyers followed by the authenticated user
     */
    public function myFollowing()
    {
        $follows = Follow::where('user_id', auth()->id())
            ->with(['lawyer' => function($query) {
                $query->with(['city', 'region', 'sectionsOfLaws']);
            }])
            ->latest()
            ->paginate(15);

        $lawyers = $follows->getCollection()->map(function($follow) {
            return new \App\Http\Resources\LawyerResource($follow->lawyer);
        });

        return $this->sendRes(__('validation.success'), true, [
            'data' => $lawyers,
            'pagination' => [
                'current_page' => $follows->currentPage(),
                'last_page' => $follows->lastPage(),
                'per_page' => $follows->perPage(),
                'total' => $follows->total(),
            ]
        ]);
    }
}
