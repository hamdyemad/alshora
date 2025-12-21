<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgendaResource;
use App\Models\Agenda;
use App\Traits\Res;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    use Res;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->get('per_page', 10);
        
        $query = Agenda::where('user_id', auth()->id());

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action_number', 'like', "%{$search}%")
                  ->orWhere('action_subject', 'like', "%{$search}%")
                  ->orWhere('court', 'like', "%{$search}%")
                  ->orWhere('claiment_name', 'like', "%{$search}%")
                  ->orWhere('defendant_name', 'like', "%{$search}%");
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
            'data' => AgendaResource::collection($agendas->items()),
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
            'action_number' => 'required|string',
            'years' => 'required|string',
            'action_subject' => 'required|string',
            'court' => 'required|string',
            'district_number' => 'required|string',
            'details' => 'nullable|string',
            'claiment_name' => 'required|string',
            'defendant_name' => 'required|string',
            'datetime' => 'required|date_format:Y-m-d H:i,Y-m-d H:i:s',
            'notification_days' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        $agenda = Agenda::create($data);

        return $this->sendRes(__('validation.created_successfully'), true, new AgendaResource($agenda));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $agenda = Agenda::where('user_id', auth()->id())->find($id);

        if (!$agenda) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        return $this->sendRes(__('validation.success'), true, new AgendaResource($agenda));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $agenda = Agenda::where('user_id', auth()->id())->find($id);

        if (!$agenda) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        $request->validate([
            'action_number' => 'sometimes|required|string',
            'years' => 'sometimes|required|string',
            'action_subject' => 'sometimes|required|string',
            'court' => 'sometimes|required|string',
            'district_number' => 'sometimes|required|string',
            'details' => 'nullable|string',
            'claiment_name' => 'sometimes|required|string',
            'defendant_name' => 'sometimes|required|string',
            'datetime' => 'sometimes|required|date_format:Y-m-d H:i,Y-m-d H:i:s',
            'notification_days' => 'nullable|integer|min:0',
        ]);

        $agenda->update($request->all());

        return $this->sendRes(__('validation.updated_successfully'), true, new AgendaResource($agenda));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $agenda = Agenda::where('user_id', auth()->id())->find($id);

        if (!$agenda) {
            return $this->sendRes(__('validation.not_found'), false, [], [], 404);
        }

        $agenda->delete();

        return $this->sendRes(__('validation.deleted_successfully'), true);
    }
}
