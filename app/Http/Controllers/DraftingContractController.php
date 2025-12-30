<?php

namespace App\Http\Controllers;

use App\Models\DraftingContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DraftingContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:drafting-contracts.view')->only(['index', 'show']);
        $this->middleware('can:drafting-contracts.create')->only(['create', 'store']);
        $this->middleware('can:drafting-contracts.edit')->only(['edit', 'update']);
        $this->middleware('can:drafting-contracts.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contracts = DraftingContract::orderBy('created_at', 'desc')->paginate(10);

        return view('pages.contracts.drafting-contracts.index', compact('contracts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.contracts.drafting-contracts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'file_en' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_ar' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'active' => 'boolean'
        ]);

        $data = $request->only(['name_en', 'name_ar', 'active']);
        $data['active'] = $request->has('active');

        // Handle English file upload
        if ($request->hasFile('file_en')) {
            $data['file_en'] = $request->file('file_en')->store('drafting-contracts', 'public');
        }

        // Handle Arabic file upload
        if ($request->hasFile('file_ar')) {
            $data['file_ar'] = $request->file('file_ar')->store('drafting-contracts', 'public');
        }

        DraftingContract::create($data);

        return redirect()->route('admin.drafting-contracts.index')
            ->with('success', trans('common.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(DraftingContract $draftingContract)
    {
        return view('pages.contracts.drafting-contracts.show', compact('draftingContract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DraftingContract $draftingContract)
    {
        return view('pages.contracts.drafting-contracts.edit', compact('draftingContract'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DraftingContract $draftingContract)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'file_en' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_ar' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'active' => 'boolean'
        ]);

        $data = $request->only(['name_en', 'name_ar', 'active']);
        $data['active'] = $request->has('active');

        // Handle English file upload
        if ($request->hasFile('file_en')) {
            // Delete old file
            if ($draftingContract->file_en) {
                Storage::disk('public')->delete($draftingContract->file_en);
            }
            $data['file_en'] = $request->file('file_en')->store('drafting-contracts', 'public');
        }

        // Handle Arabic file upload
        if ($request->hasFile('file_ar')) {
            // Delete old file
            if ($draftingContract->file_ar) {
                Storage::disk('public')->delete($draftingContract->file_ar);
            }
            $data['file_ar'] = $request->file('file_ar')->store('drafting-contracts', 'public');
        }

        $draftingContract->update($data);

        return redirect()->route('admin.drafting-contracts.index')
            ->with('success', trans('common.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DraftingContract $draftingContract)
    {
        // Delete files
        if ($draftingContract->file_en) {
            Storage::disk('public')->delete($draftingContract->file_en);
        }
        if ($draftingContract->file_ar) {
            Storage::disk('public')->delete($draftingContract->file_ar);
        }

        $draftingContract->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => trans('common.deleted_successfully'),
                'redirect' => route('admin.drafting-contracts.index')
            ]);
        }

        return redirect()->route('admin.drafting-contracts.index')
            ->with('success', trans('common.deleted_successfully'));
    }
}
