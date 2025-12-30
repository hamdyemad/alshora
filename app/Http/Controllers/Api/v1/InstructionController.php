<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\InstructionResource;
use App\Models\Instruction;
use App\Traits\Res;
use Illuminate\Http\Request;

class InstructionController extends Controller
{
    use Res;

    /**
     * Get all active instructions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            
            $instructions = Instruction::where('active', true)
                ->with('translations')
                ->latest()
                ->paginate($perPage);

            return $this->sendRes(
                __('instructions.retrieved_successfully'),
                true,
                [
                    'instructions' => InstructionResource::collection($instructions->items()),
                    'pagination' => [
                        'total' => $instructions->total(),
                        'per_page' => $instructions->perPage(),
                        'current_page' => $instructions->currentPage(),
                        'last_page' => $instructions->lastPage(),
                        'from' => $instructions->firstItem(),
                        'to' => $instructions->lastItem(),
                    ]
                ]
            );

        } catch (\Exception $e) {
            \Log::error('Error fetching instructions: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    /**
     * Get a specific instruction
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $instruction = Instruction::where('active', true)
                ->with('translations')
                ->find($id);

            if (!$instruction) {
                return $this->sendRes(__('instructions.not_found'), false, [], [], 404);
            }

            return $this->sendRes(
                __('instructions.retrieved_successfully'),
                true,
                new InstructionResource($instruction)
            );

        } catch (\Exception $e) {
            \Log::error('Error fetching instruction: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }
}
