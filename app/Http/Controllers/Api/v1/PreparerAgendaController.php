<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PreparerAgendaResource;
use App\Models\PreparerAgenda;
use App\Traits\Res;
use Illuminate\Http\Request;

class PreparerAgendaController extends Controller
{
    use Res;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->get('per_page', 10);
        
        $query = PreparerAgenda::where('user_id', auth()->id());

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('court_bailiffs', 'like', "%{$search}%")
                  ->orWhere('paper_type', 'like', "%{$search}%")
                  ->orWhere('paper_number', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        // Date from filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('datetime', '>=', $request->date_from);
        }

        // Date to filter
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('datetime', '<=', $request->date_to);
        }

        $agendas = $query->latest('datetime')->paginate($per_page);

        $data = [
            'data' => PreparerAgendaResource::collection($agendas->items()),
            'pagination' => [
                'current_page' => $agendas->currentPage(),
                'last_page' => $agendas->lastPage(),
                'per_page' => $agendas->perPage(),
                'total' => $agendas->total(),
            ]
        ];

        return $this->sendRes(__('validation.success'), true, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'court_bailiffs' => 'required|string',
            'paper_type' => 'required|string',
            'paper_delivery_date' => 'required|date',
            'paper_number' => 'required|string',
            'session_date' => 'required|date',
            'client_name' => 'required|string',
            'notes' => 'nullable|string',
            'datetime' => 'required|date_format:Y-m-d H:i,Y-m-d H:i:s',
            'notification_days' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        $agenda = PreparerAgenda::create($data);

        return $this->sendRes(__('validation.created_successfully'), true, new PreparerAgendaResource($agenda));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $agenda = PreparerAgenda::where('user_id', auth()->id())->find($id);

        if (!$agenda) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        return $this->sendRes(__('validation.success'), true, new PreparerAgendaResource($agenda));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $agenda = PreparerAgenda::where('user_id', auth()->id())->find($id);

        if (!$agenda) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        $request->validate([
            'court_bailiffs' => 'sometimes|required|string',
            'paper_type' => 'sometimes|required|string',
            'paper_delivery_date' => 'sometimes|required|date',
            'paper_number' => 'sometimes|required|string',
            'session_date' => 'sometimes|required|date',
            'client_name' => 'sometimes|required|string',
            'notes' => 'nullable|string',
            'datetime' => 'sometimes|required|date_format:Y-m-d H:i,Y-m-d H:i:s',
            'notification_days' => 'nullable|integer|min:0',
        ]);

        $agenda->update($request->all());

        return $this->sendRes(__('validation.updated_successfully'), true, new PreparerAgendaResource($agenda));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $agenda = PreparerAgenda::where('user_id', auth()->id())->find($id);

        if (!$agenda) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        $agenda->delete();

        return $this->sendRes(__('validation.deleted_successfully'), true);
    }
}
