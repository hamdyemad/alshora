<?php

namespace App\Services;

use App\Interfaces\PreparerAgendaRepositoryInterface;
use App\Models\PreparerAgenda;

class PreparerAgendaService
{
    protected $preparerAgendaRepository;

    public function __construct(PreparerAgendaRepositoryInterface $preparerAgendaRepository)
    {
        $this->preparerAgendaRepository = $preparerAgendaRepository;
    }

    /**
     * Get all agendas with filters
     */
    public function getAllAgendas(array $filters = [], int $perPage = 10)
    {
        return $this->preparerAgendaRepository->getAllWithFilters($filters, $perPage);
    }

    /**
     * Get agenda by ID
     */
    public function getAgendaById(int $id)
    {
        $agenda = $this->preparerAgendaRepository->findById($id);
        
        if (!$agenda) {
            throw new \Exception(__('Agenda not found'));
        }
        
        return $agenda;
    }

    /**
     * Create agenda
     */
    public function createAgenda(array $data)
    {
        return $this->preparerAgendaRepository->create($data);
    }

    /**
     * Update agenda
     */
    public function updateAgenda(PreparerAgenda $agenda, array $data)
    {
        return $this->preparerAgendaRepository->update($agenda, $data);
    }

    /**
     * Delete agenda
     */
    public function deleteAgenda(PreparerAgenda $agenda)
    {
        return $this->preparerAgendaRepository->delete($agenda);
    }
}
