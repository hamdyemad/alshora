<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientAgendaResource;
use App\Models\ClientAgenda;
use App\Traits\Res;
use Illuminate\Http\Request;

class ClientAgendaController extends Controller
{
    use Res;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->get('per_page', 10);
        
        $query = ClientAgenda::where('user_id', auth()->id());

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('client_phone', 'like', "%{$search}%")
                  ->orWhere('client_inquiry', 'like', "%{$search}%");
            });
        }

        // Date from filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('follow_up_date', '>=', $request->date_from);
        }

        // Date to filter
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('follow_up_date', '<=', $request->date_to);
        }

        $clientAgendas = $query->latest('follow_up_date')->paginate($per_page);

        $data = [
            'data' => ClientAgendaResource::collection($clientAgendas->items()),
            'pagination' => [
                'current_page' => $clientAgendas->currentPage(),
                'last_page' => $clientAgendas->lastPage(),
                'per_page' => $clientAgendas->perPage(),
                'total' => $clientAgendas->total(),
            ]
        ];

        return $this->sendRes(__('client_agenda.retrieved_successfully'), true, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_inquiry' => 'nullable|string',
            'follow_up_response' => 'nullable|string',
            'follow_up_date' => 'required|date_format:Y-m-d',
            'notification_days' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        $clientAgenda = ClientAgenda::create($data);

        return $this->sendRes(__('client_agenda.created_successfully'), true, new ClientAgendaResource($clientAgenda), [], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $clientAgenda = ClientAgenda::where('user_id', auth()->id())->find($id);

        if (!$clientAgenda) {
            return $this->sendRes(__('client_agenda.not_found'), false, [], [], 404);
        }

        return $this->sendRes(__('client_agenda.retrieved_successfully'), true, new ClientAgendaResource($clientAgenda));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $clientAgenda = ClientAgenda::where('user_id', auth()->id())->find($id);

        if (!$clientAgenda) {
            return $this->sendRes(__('client_agenda.not_found'), false, [], [], 404);
        }

        $request->validate([
            'client_name' => 'sometimes|required|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_inquiry' => 'nullable|string',
            'follow_up_response' => 'nullable|string',
            'follow_up_date' => 'sometimes|required|date_format:Y-m-d',
            'notification_days' => 'nullable|integer|min:0',
        ]);

        $clientAgenda->update($request->all());

        return $this->sendRes(__('client_agenda.updated_successfully'), true, new ClientAgendaResource($clientAgenda));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $clientAgenda = ClientAgenda::where('user_id', auth()->id())->find($id);

        if (!$clientAgenda) {
            return $this->sendRes(__('client_agenda.not_found'), false, [], [], 404);
        }

        $clientAgenda->delete();

        return $this->sendRes(__('client_agenda.deleted_successfully'), true);
    }
}
