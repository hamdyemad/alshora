<?php

namespace App\Interfaces;

use App\Models\Instruction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InstructionRepositoryInterface
{
    /**
     * Get all instructions with filters
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    /**
     * Get instruction by ID
     */
    public function getInstructionById(int $id);

    public function getAll();

    /**
     * Create a new instruction
     */
    public function create(array $data): Instruction;

    /**
     * Update an instruction
     */
    public function update(Instruction $instruction, array $data): Instruction;

    /**
     * Delete an instruction
     */
    public function delete(Instruction $instruction): bool;
}
