<?php

namespace App\Repositories;

use App\Interfaces\InstructionRepositoryInterface;
use App\Models\Instruction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InstructionRepository implements InstructionRepositoryInterface
{
    /**
     * Get all instructions with filters and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Instruction::with(['translations']);

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('lang_value', 'like', "%{$search}%")
                  ->whereIn('lang_key', ['title', 'description']);
            });
        }

        // Apply active filter
        if (isset($filters['active']) && $filters['active'] !== '') {
            $query->where('active', $filters['active']);
        }

        // Apply date from filter
        if (!empty($filters['created_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_date_from']);
        }

        // Apply date to filter
        if (!empty($filters['created_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_date_to']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get instruction by ID with relations
     */
    public function getInstructionById(int $id)
    {
        return Instruction::with(['translations'])->findOrFail($id);
    }

    /**
     * Get all instructions
     */
    public function getAll()
    {
        return Instruction::with(['translations'])->get();
    }

    /**
     * Create a new instruction
     */
    public function create(array $data): Instruction
    {
        return DB::transaction(function () use ($data) {
            // Create instruction
            $instruction = Instruction::create([
                'active' => $data['active'] ?? true,
            ]);

            // Set translations
            $instruction->setTranslation('title', 'en', $data['title_en']);
            $instruction->setTranslation('title', 'ar', $data['title_ar']);
            $instruction->setTranslation('description', 'en', $data['description_en']);
            $instruction->setTranslation('description', 'ar', $data['description_ar']);
            $instruction->save();

            return $instruction->fresh(['translations']);
        });
    }

    /**
     * Update an instruction
     */
    public function update(Instruction $instruction, array $data): Instruction
    {
        return DB::transaction(function () use ($instruction, $data) {
            // Update basic fields
            $instruction->update([
                'active' => $data['active'] ?? true,
            ]);

            // Update translations
            $instruction->setTranslation('title', 'en', $data['title_en']);
            $instruction->setTranslation('title', 'ar', $data['title_ar']);
            $instruction->setTranslation('description', 'en', $data['description_en']);
            $instruction->setTranslation('description', 'ar', $data['description_ar']);
            $instruction->save();

            return $instruction->fresh(['translations']);
        });
    }

    /**
     * Delete an instruction
     */
    public function delete(Instruction $instruction): bool
    {
        return $instruction->delete();
    }
}
