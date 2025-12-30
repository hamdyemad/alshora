<?php

namespace App\Http\Controllers;

use App\Models\Measure;
use Illuminate\Http\Request;

class MeasureController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:measures.view')->only(['index', 'show']);
        $this->middleware('can:measures.create')->only(['create', 'store']);
        $this->middleware('can:measures.edit')->only(['edit', 'update']);
        $this->middleware('can:measures.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $measures = Measure::orderBy('created_at', 'desc')->paginate(10);

        return view('pages.contracts.measures.index', compact('measures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.contracts.measures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'active' => 'boolean'
        ]);

        $data = [
            'active' => $request->has('active'),
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
        ];

        $measure = Measure::create(['active' => $data['active']]);
        $measure->setTranslation('title', 'en', $data['title_en']);
        $measure->setTranslation('title', 'ar', $data['title_ar']);
        $measure->setTranslation('description', 'en', $data['description_en'] ?? '');
        $measure->setTranslation('description', 'ar', $data['description_ar'] ?? '');
        $measure->save();

        return redirect()->route('admin.measures.index')
            ->with('success', trans('common.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Measure $measure)
    {
        return view('pages.contracts.measures.show', compact('measure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Measure $measure)
    {
        return view('pages.contracts.measures.edit', compact('measure'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Measure $measure)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'active' => 'boolean'
        ]);

        $measure->update([
            'active' => $request->has('active'),
        ]);

        $measure->setTranslation('title', 'en', $request->title_en);
        $measure->setTranslation('title', 'ar', $request->title_ar);
        $measure->setTranslation('description', 'en', $request->description_en ?? '');
        $measure->setTranslation('description', 'ar', $request->description_ar ?? '');
        $measure->save();

        return redirect()->route('admin.measures.index')
            ->with('success', trans('common.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Measure $measure)
    {
        $measure->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => trans('common.deleted_successfully'),
                'redirect' => route('admin.measures.index')
            ]);
        }

        return redirect()->route('admin.measures.index')
            ->with('success', trans('common.deleted_successfully'));
    }
}

