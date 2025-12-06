<?php

namespace App\Repositories;

use App\Interfaces\CityRepositoryInterface;
use App\Models\Areas\City;
use Illuminate\Support\Facades\DB;

class CityRepository implements CityRepositoryInterface
{
    /**
     * Get all cities with filters and pagination
     */
    public function getAllCities(array $filters = [], int $perPage = 15)
    {
        $query = City::with(['country.translations', 'translations'])
        ->filter($filters);
        // Order by latest
        $query->orderBy('created_at', 'desc');

        return ($perPage == 0) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Get city by ID
     */
    public function getCityById(int $id)
    {
        return City::with(['country', 'translations'])->find($id);
    }

    /**
     * Create a new city
     */
    public function createCity(array $data)
    {
        return DB::transaction(function () use ($data) {
            $city = City::create([
                'country_id' => $data['country_id'],
                'active' => $data['active'] ?? 0,
            ]);

            // Set translations from nested array
            if (isset($data['translations']) && is_array($data['translations'])) {
                foreach ($data['translations'] as $langId => $translation) {
                    if (isset($translation['name'])) {
                        $city->translations()->create([
                            'lang_id' => $langId,
                            'lang_key' => 'name',
                            'lang_value' => $translation['name'],
                        ]);
                    }
                }
            }
            return $city;
        });
    }

    /**
     * Update city
     */
    public function updateCity(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $city = City::findOrFail($id);

            $city->update([
                'country_id' => $data['country_id'],
                'active' => $data['active'] ?? 0,
            ]);

            if (isset($data['translations']) && is_array($data['translations'])) {
                foreach ($data['translations'] as $langId => $translation) {
                    if (isset($translation['name'])) {
                        $city->translations()->updateOrCreate(
                            [
                                'lang_id' => $langId,
                                'lang_key' => 'name',
                            ],
                            [
                                'lang_value' => $translation['name'],
                            ]
                        );
                    }
                }
            }

            $city->refresh();
            $city->load('translations');

            return $city;
        });
    }

    /**
     * Delete city
     */
    public function deleteCity(int $id)
    {
        $city = City::findOrFail($id);
        $city->translations()->delete();
        return $city->delete();
    }

    /**
     * Get active cities
     */
    public function getActiveCities(array $filters = [], int $perPage = 15)
    {
        $query = City::with('translations')->active();
        return ($perPage == 0) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Get cities by country
     */
    public function getCitiesByCountry(int $countryId)
    {
        return City::where('country_id', $countryId)
            ->where('active', 1)
            ->with('translations')
            ->get();
    }
}
