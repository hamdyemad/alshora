<?php

namespace App\Http\Controllers;

use App\Services\AgendaService;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    protected $agendaService;

    public function __construct(AgendaService $agendaService)
    {
        $this->agendaService = $agendaService;
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

        $agendas = $this->agendaService->getAllAgendas($filters, 10);

        return view('pages.agendas.index', compact('agendas'))
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
            $agenda = $this->agendaService->getAgendaById($id);
            return view('pages.agendas.view', compact('agenda'));
        } catch (\Exception $e) {
            return redirect()->route('admin.agendas.index')
                ->with('error', trans('agenda.not_found'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $agenda = $this->agendaService->getAgendaById($id);
            $this->agendaService->deleteAgenda($agenda);
            
            return response()->json([
                'success' => true,
                'message' => trans('agenda.deleted_successfully'),
                'redirect' => route('admin.agendas.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => trans('agenda.error_deleting') . ': ' . $e->getMessage()
            ], 422);
        }
    }
}
