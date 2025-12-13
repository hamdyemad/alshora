<?php

namespace App\Http\Controllers;

use App\Models\BranchOfLaw;
use App\Models\Law;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LawController extends Controller
{
    /**
     * Display a listing of laws for a specific branch
     */
    public function index($branches_of_law)
    {
        $branchOfLaw = BranchOfLaw::findOrFail($branches_of_law);
        $laws = $branchOfLaw->laws()->latest()->paginate(10);

        return view('pages.laws.index', compact('branchOfLaw', 'laws'));
    }

    /**
     * Show the form for creating a new law
     */
    public function create($branches_of_law)
    {
        $branchOfLaw = BranchOfLaw::findOrFail($branches_of_law);
        return view('pages.laws.create', compact('branchOfLaw'));
    }

    /**
     * Store a newly created law in storage
     */
    public function store(Request $request, $branches_of_law)
    {
        $branchOfLaw = BranchOfLaw::findOrFail($branches_of_law);

        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'active' => 'boolean'
        ]);

        $law = new Law();
        $law->branch_of_law_id = $branchOfLaw->id;
        $law->active = $request->has('active');
        $law->save();

        // Set translations after saving
        $law->setTranslation('title', 'en', $request->input('title_en'));
        $law->setTranslation('title', 'ar', $request->input('title_ar'));
        $law->setTranslation('description', 'en', $request->input('description_en'));
        $law->setTranslation('description', 'ar', $request->input('description_ar'));
        $law->save();

        return redirect()->route('admin.branches-of-laws.laws.index', ['branches_of_law' => $branchOfLaw->id])
            ->with('success', trans('common.created_successfully'));
    }

    /**
     * Display the specified law
     */
    public function show($branches_of_law, $law)
    {
        $branchOfLaw = BranchOfLaw::findOrFail($branches_of_law);
        $law = Law::findOrFail($law);
        return view('pages.laws.show', compact('branchOfLaw', 'law'));
    }

    /**
     * Show the form for editing the specified law
     */
    public function edit($branches_of_law, $law)
    {
        $branchOfLaw = BranchOfLaw::findOrFail($branches_of_law);
        $law = Law::findOrFail($law);
        return view('pages.laws.edit', compact('branchOfLaw', 'law'));
    }

    /**
     * Update the specified law in storage
     */
    public function update(Request $request, $branches_of_law, $law)
    {
        $branchOfLaw = BranchOfLaw::findOrFail($branches_of_law);
        $law = Law::findOrFail($law);

        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'active' => 'boolean'
        ]);

        $law->active = $request->has('active');
        $law->save();

        // Update translations
        $law->setTranslation('title', 'en', $request->input('title_en'));
        $law->setTranslation('title', 'ar', $request->input('title_ar'));
        $law->setTranslation('description', 'en', $request->input('description_en'));
        $law->setTranslation('description', 'ar', $request->input('description_ar'));
        $law->save();

        return redirect()->route('admin.branches-of-laws.laws.index', ['branches_of_law' => $branchOfLaw->id])
            ->with('success', trans('common.updated_successfully'));
    }

    /**
     * Remove the specified law from storage
     */
    public function destroy($branches_of_law, $law)
    {
        $branchOfLaw = BranchOfLaw::findOrFail($branches_of_law);
        $law = Law::findOrFail($law);
        $law->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => trans('common.deleted_successfully'),
                'redirect' => route('admin.branches-of-laws.laws.index', ['branches_of_law' => $branchOfLaw->id])
            ]);
        }

        return redirect()->route('admin.branches-of-laws.laws.index', ['branches_of_law' => $branchOfLaw->id])
            ->with('success', trans('common.deleted_successfully'));
    }
}
