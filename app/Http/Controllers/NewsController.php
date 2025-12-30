<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Services\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
        
        $this->middleware('can:news.view')->only(['index', 'show']);
        $this->middleware('can:news.create')->only(['create', 'store']);
        $this->middleware('can:news.edit')->only(['edit', 'update']);
        $this->middleware('can:news.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'active' => $request->get('active'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $news = $this->newsService->getAllNews($filters, 10);

        return view('pages.news.index', compact('news'))
            ->with([
                'search' => $filters['search'],
                'active' => $filters['active'],
                'dateFrom' => $filters['date_from'],
                'dateTo' => $filters['date_to'],
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.news.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewsRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->newsService->createNews($validated);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => trans('news.created_successfully'),
                    'redirect' => route('admin.news.index')
                ]);
            }
            
            return redirect()->route('admin.news.index')
                ->with('success', trans('news.created_successfully'));
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('news.error_creating') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', trans('news.error_creating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $news = $this->newsService->getNewsById($id);
            return view('pages.news.view', compact('news'));
        } catch (\Exception $e) {
            return redirect()->route('admin.news.index')
                ->with('error', trans('news.not_found'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $news = $this->newsService->getNewsById($id);
            return view('pages.news.form', compact('news'));
        } catch (\Exception $e) {
            return redirect()->route('admin.news.index')
                ->with('error', trans('news.not_found'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NewsRequest $request, string $id)
    {
        $validated = $request->validated();
        $news = $this->newsService->getNewsById($id);

        try {
            $this->newsService->updateNews($news, $validated);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => trans('news.updated_successfully'),
                    'redirect' => route('admin.news.index')
                ]);
            }
            
            return redirect()->route('admin.news.index')
                ->with('success', trans('news.updated_successfully'));
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('news.error_updating') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', trans('news.error_updating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $news = $this->newsService->getNewsById($id);
            $this->newsService->deleteNews($news);
            
            return response()->json([
                'success' => true,
                'message' => trans('news.deleted_successfully'),
                'redirect' => route('admin.news.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => trans('news.error_deleting') . ': ' . $e->getMessage()
            ], 422);
        }
    }
}
