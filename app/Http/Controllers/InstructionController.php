<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstructionRequest;
use App\Services\InstructionService;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class InstructionController extends Controller
{
    public function __construct(
        protected InstructionService $instructionService,
        protected LanguageService $languageService
    ) {
        $this->middleware('can:instructions.view')->only(['index', 'show']);
        $this->middleware('can:instructions.create')->only(['create', 'store']);
        $this->middleware('can:instructions.edit')->only(['edit', 'update']);
        $this->middleware('can:instructions.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->get('search');
        $active = $request->get('active');
        $dateFrom = $request->get('created_date_from');
        $dateTo = $request->get('created_date_to');

        // Prepare filters array
        $filters = [
            'search' => $search,
            'active' => $active,
            'created_date_from' => $dateFrom,
            'created_date_to' => $dateTo,
        ];

        // Get instructions with filters and pagination
        $instructions = $this->instructionService->getAllInstructions($filters, 10);
        
        // Get languages for table headers
        $languages = $this->languageService->getAll();
        
        return view('pages.instructions.index', compact('instructions', 'search', 'active', 'dateFrom', 'dateTo', 'languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = $this->languageService->getAll();
        return view('pages.instructions.form', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InstructionRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->instructionService->createInstruction($validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('instructions.created_successfully'),
                    'redirect' => route('admin.instructions.index')
                ]);
            }
            
            return redirect()->route('admin.instructions.index')
                ->with('success', __('instructions.created_successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('instructions.error_creating') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('instructions.error_creating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $instruction = $this->instructionService->getInstructionById($id);
            $languages = $this->languageService->getAll();
            return view('pages.instructions.view', compact('instruction', 'languages'));
        } catch (\Exception $e) {
            return redirect()->route('admin.instructions.index')
                ->with('error', __('instructions.not_found'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $languages = $this->languageService->getAll();
            $instruction = $this->instructionService->getInstructionById($id);
            return view('pages.instructions.form', compact('instruction', 'languages'));
        } catch (\Exception $e) {
            return redirect()->route('admin.instructions.index')
                ->with('error', __('instructions.not_found'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InstructionRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $instruction = $this->instructionService->getInstructionById($id);
            $this->instructionService->updateInstruction($instruction, $validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('instructions.updated_successfully'),
                    'redirect' => route('admin.instructions.index')
                ]);
            }
            
            return redirect()->route('admin.instructions.index')
                ->with('success', __('instructions.updated_successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('instructions.error_updating') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('instructions.error_updating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $instruction = $this->instructionService->getInstructionById($id);
            $this->instructionService->deleteInstruction($instruction);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('instructions.deleted_successfully'),
                    'redirect' => route('admin.instructions.index')
                ]);
            }
            
            return redirect()->route('admin.instructions.index')
                ->with('success', __('instructions.deleted_successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('instructions.error_deleting') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('admin.instructions.index')
                ->with('error', __('instructions.error_deleting') . ': ' . $e->getMessage());
        }
    }
}
