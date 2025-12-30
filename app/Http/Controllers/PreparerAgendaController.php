<?php

namespace App\Http\Controllers;

use App\Services\PreparerAgendaService;
use Illuminate\Http\Request;

class PreparerAgendaController extends Controller
{
    protected $preparerAgendaService;

    public function __construct(PreparerAgendaService $preparerAgendaService)
    {
        $this->preparerAgendaService = $preparerAgendaService;
        
        $this->middleware('can:preparer-agendas.view')->only(['index', 'show']);
        $this->middleware('can:preparer-agendas.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $agendas = $this->preparerAgendaService->getAllAgendas($filters, 10);

        return view('pages.preparer_agendas.index', compact('agendas'))
            ->with([
                'search' => $filters['search'],
                'dateFrom' => $filters['date_from'],
                'dateTo' => $filters['date_to'],
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $agenda = $this->preparerAgendaService->getAgendaById($id);
            return view('pages.preparer_agendas.view', compact('agenda'));
        } catch (\Exception $e) {
            return redirect()->route('admin.preparer-agendas.index')
                ->with('error', trans('agenda.not_found'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $agenda = $this->preparerAgendaService->getAgendaById($id);
            $this->preparerAgendaService->deleteAgenda($agenda);
            
            return response()->json([
                'success' => true,
                'message' => trans('agenda.deleted_successfully'),
                'redirect' => route('admin.preparer-agendas.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => trans('agenda.error_deleting') . ': ' . $e->getMessage()
            ], 422);
        }
    }
}
