<?php

namespace App\Services;

use App\Interfaces\AgendaRepositoryInterface;
use App\Models\Agenda;

class AgendaService
{
    protected $agendaRepository;

    public function __construct(AgendaRepositoryInterface $agendaRepository)
    {
        $this->agendaRepository = $agendaRepository;
    }

    /**
     * Get all agendas with filters
     */
    public function getAllAgendas(array $filters = [], int $perPage = 10)
    {
        return $this->agendaRepository->getAllWithFilters($filters, $perPage);
    }

    /**
     * Get agenda by ID
     */
    public function getAgendaById(int $id)
    {
        $agenda = $this->agendaRepository->findById($id);
        
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
        return $this->agendaRepository->create($data);
    }

    /**
     * Update agenda
     */
    public function updateAgenda(Agenda $agenda, array $data)
    {
        return $this->agendaRepository->update($agenda, $data);
    }

    /**
     * Delete agenda
     */
    public function deleteAgenda(Agenda $agenda)
    {
        return $this->agendaRepository->delete($agenda);
    }
}
