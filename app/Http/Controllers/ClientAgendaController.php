<?php

namespace App\Http\Controllers;

use App\Models\ClientAgenda;
use Illuminate\Http\Request;

class ClientAgendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:client-agendas.view')->only(['index', 'show']);
        $this->middleware('can:client-agendas.delete')->only(['destroy']);
    }

    /**
     * Display a listing of client agendas
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = ClientAgenda::with('user');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('client_phone', 'like', "%{$search}%")
                  ->orWhere('client_inquiry', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($dateFrom) {
            $query->whereDate('follow_up_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('follow_up_date', '<=', $dateTo);
        }

        $clientAgendas = $query->latest('follow_up_date')->paginate(15);

        return view('pages.client-agendas.index', compact('clientAgendas', 'search', 'dateFrom', 'dateTo'));
    }

    /**
     * Display the specified client agenda
     */
    public function show(string $id)
    {
        $clientAgenda = ClientAgenda::with('user')->findOrFail($id);

        return view('pages.client-agendas.view', compact('clientAgenda'));
    }

    /**
     * Remove the specified client agenda
     */
    public function destroy(string $id)
    {
        $clientAgenda = ClientAgenda::findOrFail($id);
        $clientAgenda->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('client_agenda.deleted_successfully'),
            ]);
        }

        return redirect()->route('admin.client-agendas.index')
            ->with('success', __('client_agenda.deleted_successfully'));
    }
}
