<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Traits\Res;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    use Res;

    /**
     * Get all active news
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            
            $news = News::where('active', true)
                ->with('translations')
                ->orderBy('date', 'desc')
                ->paginate($perPage);

            return $this->sendRes(
                __('news.retrieved_successfully'),
                true,
                [
                    'news' => NewsResource::collection($news->items()),
                    'pagination' => [
                        'total' => $news->total(),
                        'per_page' => $news->perPage(),
                        'current_page' => $news->currentPage(),
                        'last_page' => $news->lastPage(),
                        'from' => $news->firstItem(),
                        'to' => $news->lastItem(),
                    ]
                ]
            );

        } catch (\Exception $e) {
            \Log::error('Error fetching news: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    /**
     * Get a specific news item
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $news = News::where('active', true)
                ->with('translations')
                ->find($id);

            if (!$news) {
                return $this->sendRes(__('news.not_found'), false, [], [], 404);
            }

            return $this->sendRes(
                __('news.retrieved_successfully'),
                true,
                new NewsResource($news)
            );

        } catch (\Exception $e) {
            \Log::error('Error fetching news: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }
}
