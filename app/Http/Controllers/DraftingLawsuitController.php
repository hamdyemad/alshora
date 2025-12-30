<?php

namespace App\Http\Controllers;

use App\Models\DraftingLawsuit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DraftingLawsuitController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:drafting-lawsuits.view')->only(['index', 'show']);
        $this->middleware('can:drafting-lawsuits.create')->only(['create', 'store']);
        $this->middleware('can:drafting-lawsuits.edit')->only(['edit', 'update']);
        $this->middleware('can:drafting-lawsuits.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lawsuits = DraftingLawsuit::orderBy('created_at', 'desc')->paginate(10);

        return view('pages.contracts.drafting-lawsuits.index', compact('lawsuits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.contracts.drafting-lawsuits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'active' => 'boolean'
        ]);

        $data = $request->only(['name', 'active']);
        $data['active'] = $request->has('active');

        // Handle file upload
        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('drafting-lawsuits', 'public');
        }

        DraftingLawsuit::create($data);

        return redirect()->route('admin.drafting-lawsuits.index')
            ->with('success', trans('common.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(DraftingLawsuit $draftingLawsuit)
    {
        return view('pages.contracts.drafting-lawsuits.show', compact('draftingLawsuit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DraftingLawsuit $draftingLawsuit)
    {
        return view('pages.contracts.drafting-lawsuits.edit', compact('draftingLawsuit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DraftingLawsuit $draftingLawsuit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'active' => 'boolean'
        ]);

        $data = $request->only(['name', 'active']);
        $data['active'] = $request->has('active');

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($draftingLawsuit->file) {
                Storage::disk('public')->delete($draftingLawsuit->file);
            }
            $data['file'] = $request->file('file')->store('drafting-lawsuits', 'public');
        }

        $draftingLawsuit->update($data);

        return redirect()->route('admin.drafting-lawsuits.index')
            ->with('success', trans('common.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DraftingLawsuit $draftingLawsuit)
    {
        // Delete file
        if ($draftingLawsuit->file) {
            Storage::disk('public')->delete($draftingLawsuit->file);
        }

        $draftingLawsuit->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => trans('common.deleted_successfully'),
                'redirect' => route('admin.drafting-lawsuits.index')
            ]);
        }

        return redirect()->route('admin.drafting-lawsuits.index')
            ->with('success', trans('common.deleted_successfully'));
    }
}
