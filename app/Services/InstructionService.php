<?php

namespace App\Services;

use App\Interfaces\InstructionRepositoryInterface;
use App\Models\Instruction;
use Illuminate\Support\Facades\Log;

class InstructionService
{
    protected $instructionRepository;

    public function __construct(InstructionRepositoryInterface $instructionRepository)
    {
        $this->instructionRepository = $instructionRepository;
    }

    /**
     * Get all instructions with filters
     */
    public function getAllInstructions(array $filters = [], int $perPage = 10)
    {
        try {
            return $this->instructionRepository->getAllWithFilters($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching instructions: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all instructions
     */
    public function getAll()
    {
        try {
            return $this->instructionRepository->getAll();
        } catch (\Exception $e) {
            Log::error('Error fetching instructions: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get instruction by ID
     */
    public function getInstructionById(int $id)
    {
        try {
            return $this->instructionRepository->getInstructionById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching instruction: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create instruction
     */
    public function createInstruction(array $data)
    {
        try {
            return $this->instructionRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating instruction: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update instruction
     */
    public function updateInstruction(Instruction $instruction, array $data)
    {
        try {
            return $this->instructionRepository->update($instruction, $data);
        } catch (\Exception $e) {
            Log::error('Error updating instruction: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete instruction
     */
    public function deleteInstruction(Instruction $instruction)
    {
        try {
            return $this->instructionRepository->delete($instruction);
        } catch (\Exception $e) {
            Log::error('Error deleting instruction: ' . $e->getMessage());
            throw $e;
        }
    }
}
