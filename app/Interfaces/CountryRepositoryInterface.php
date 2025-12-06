<?php

namespace App\Interfaces;

interface CountryRepositoryInterface
{
    /**
     * Get all countries with filters and pagination
     */
    public function getAllCountries(array $filters = [], int $perPage = 15);

    /**
     * Get country by ID
     */
    public function getCountryById(int $id);


    public function getAll();
    /**
     * Create a new country
     */
    public function createCountry(array $data);

    /**
     * Update country
     */
    public function updateCountry(int $id, array $data);

    /**
     * Delete country
     */
    public function deleteCountry(int $id);

    /**
     * Get active countries
     */
    public function getActiveCountries();
}
