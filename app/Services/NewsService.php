<?php

namespace App\Services;

use App\Interfaces\NewsRepositoryInterface;
use App\Models\News;

class NewsService
{
    protected $newsRepository;

    public function __construct(NewsRepositoryInterface $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * Get all news with filters
     */
    public function getAllNews(array $filters = [], int $perPage = 10)
    {
        return $this->newsRepository->getAllWithFilters($filters, $perPage);
    }

    /**
     * Get news by ID
     */
    public function getNewsById(int $id)
    {
        $news = $this->newsRepository->findById($id);
        
        if (!$news) {
            throw new \Exception(__('News not found'));
        }
        
        return $news;
    }

    /**
     * Create news
     */
    public function createNews(array $data)
    {
        return $this->newsRepository->create($data);
    }

    /**
     * Update news
     */
    public function updateNews(News $news, array $data)
    {
        return $this->newsRepository->update($news, $data);
    }

    /**
     * Delete news
     */
    public function deleteNews(News $news)
    {
        return $this->newsRepository->delete($news);
    }
}
