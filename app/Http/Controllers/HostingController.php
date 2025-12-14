<?php

namespace App\Http\Controllers;

use App\Models\HostingTime;
use Illuminate\Http\Request;
use App\Traits\Res;
use App\Http\Requests\StoreHostingSettingsRequest;

class HostingController extends Controller
{
    use Res;

    /**
     * Display the hosting index page
     */
    public function index()
    {
        $days = HostingTime::getDays();
        $hostingTimes = HostingTime::orderByRaw("FIELD(day, 'saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday')")
            ->orderBy('from_time')
            ->get()
            ->groupBy('day');

        return view('pages.hosting.index', compact('hostingTimes', 'days'));
    }

    /**
     * Display the hosting settings form
     */
    public function settings()
    {
        $days = HostingTime::getDays();
        $hostingTimes = HostingTime::orderByRaw("FIELD(day, 'saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday')")
            ->orderBy('from_time')
            ->get()
            ->groupBy('day');

        return view('pages.hosting.settings', compact('days', 'hostingTimes'));
    }

    /**
     * Store or update hosting time settings
     */
    public function storeSettings(StoreHostingSettingsRequest $request)
    {
        try {
            $submittedIds = [];
            $validDays = array_keys(HostingTime::getDays());

            foreach ($request->time_slots as $key => $slot) {
                // Skip if not an array or missing required fields
                if (!is_array($slot) || !isset($slot['day']) || !isset($slot['from_time']) || !isset($slot['to_time'])) {
                    continue;
                }

                // Skip if either time is empty
                if (empty($slot['from_time']) || empty($slot['to_time'])) {
                    continue;
                }

                $day = $slot['day'];
                $fromTime = $slot['from_time'];
                $toTime = $slot['to_time'];
                $isActive = isset($slot['is_active']) && $slot['is_active'] == '1' ? true : false;

                // Validate day
                if (!in_array($day, $validDays)) {
                    continue;
                }

                // Validate that from_time is before to_time
                if ($fromTime >= $toTime) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', __('hosting.time_validation_error', ['day' => HostingTime::getDays()[$day]]));
                }

                if (isset($slot['id']) && !empty($slot['id'])) {
                    // Update existing record
                    $hostingTime = HostingTime::find($slot['id']);
                    if ($hostingTime) {
                        $hostingTime->update([
                            'from_time' => $fromTime,
                            'to_time' => $toTime,
                            'is_active' => $isActive,
                        ]);
                        $submittedIds[] = $hostingTime->id;
                    }
                } else {
                    // Create new record
                    $hostingTime = HostingTime::create([
                        'day' => $day,
                        'from_time' => $fromTime,
                        'to_time' => $toTime,
                        'is_active' => $isActive,
                    ]);
                    $submittedIds[] = $hostingTime->id;
                }
            }

            // Delete removed time slots (only if we have submitted IDs)
            if (!empty($submittedIds)) {
                HostingTime::whereNotIn('id', $submittedIds)->delete();
            }

            return redirect()->back()->with('success', __('hosting.times_updated_successfully'));
        } catch (\Exception $e) {
            \Log::error('Hosting Settings Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', __('hosting.error_updating_times') . ': ' . $e->getMessage());
        }
    }
}
