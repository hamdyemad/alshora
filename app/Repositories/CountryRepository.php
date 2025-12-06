<?php

namespace App\Repositories;

use App\Interfaces\CountryRepositoryInterface;
use App\Models\Areas\Country;
use Illuminate\Support\Facades\DB;

class CountryRepository implements CountryRepositoryInterface
{
    /**
     * Get all countries with filters and pagination
     */
    public function getAllCountries(array $filters = [], int $perPage = 15)
    {
        $query = Country::with('translations')->filter($filters);
        // Order by latest
        $query->orderBy('created_at', 'desc');

        return ($perPage == 0) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Get country by ID
     */
    public function getCountryById(int $id)
    {
        return Country::with('translations')->findOrFail($id);
    }

    /**
     * Create a new country
     */
    public function createCountry(array $data)
    {
        return DB::transaction(function () use ($data) {
            if( $data['default_country'] == 1) {
                Country::query()->update(['default_country' => 0]);
            }
            $country = Country::create([
                'code' => $data['code'],
                'phone_code' => $data['phone_code'] ?? null,
                'active' => $data['active'] ?? 0,
                'default_country' => $data['default_country'] ?? 0
            ]);

            // Set translations from nested array
            if (isset($data['translations']) && is_array($data['translations'])) {
                foreach ($data['translations'] as $langId => $translation) {
                    if (isset($translation['name'])) {
                        $country->translations()->create([
                            'lang_id' => $langId,
                            'lang_key' => 'name',
                            'lang_value' => $translation['name'],
                        ]);
                    }
                }
            }
            
            return $country;
        });
    }

    /**
     * Update country
     */
    public function updateCountry(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $country = Country::findOrFail($id);
            if( $data['default_country'] == 1) {
                Country::query()->update(['default_country' => 0]);
            }
            $country->update([
                'code' => $data['code'],
                'phone_code' => $data['phone_code'] ?? null,
                'active' => $data['active'] ?? 0,
                'default_country' => $data['default_country'] ?? 0
            ]);

            // Update translations from nested array
            if (isset($data['translations']) && is_array($data['translations'])) {
                foreach ($data['translations'] as $langId => $translation) {
                    if (isset($translation['name'])) {
                        $country->translations()->updateOrCreate(
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

            $country->refresh();
            $country->load('translations');

            return $country;
        });
    }

    /**
     * Delete country
     */
    public function deleteCountry(int $id)
    {
        $country = Country::findOrFail($id);
        $country->translations()->delete();
        return $country->delete();
    }

    /**
     * Get active countries
     */
    public function getActiveCountries()
    {
        return Country::with('translations')->where('active', 1)
            ->get();
    }

    public function getAll()
    {
        return Country::with('translations')->get();
    }
}
