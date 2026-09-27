<?php

namespace App\Services;

use App\Models\SugAdministration;
use App\Models\SugOfficer;
use Illuminate\Support\Facades\DB;

class AdministrationService
{
    /**
     * Get the current active SUG administration.
     */
    public function getCurrentAdministration(): ?SugAdministration
    {
        return SugAdministration::where('status', 'current')->first();
    }

    /**
     * Set a specific administration as current.
     */
    public function setCurrentAdministration(int $id): void
    {
        DB::transaction(function () use ($id) {
            SugAdministration::where('status', 'current')->update(['status' => 'past']);
            SugAdministration::where('id', $id)->update(['status' => 'current']);
        });
    }

    /**
     * Create a new administration.
     */
    public function createAdministration(array $data): SugAdministration
    {
        return SugAdministration::create($data);
    }

    /**
     * Assign an officer to an administration.
     */
    public function assignOfficer(int $adminId, int $userId, string $position, string $date, ?string $portfolio = null, ?string $imagePath = null): SugOfficer
    {
        return SugOfficer::create([
            'sug_administration_id' => $adminId,
            'user_id' => $userId,
            'position' => $position,
            'appointment_date' => $date,
            'portfolio' => $portfolio,
            'image_path' => $imagePath,
        ]);
    }

    /**
     * Update an officer's profile.
     */
    public function updateOfficer(int $id, array $data): SugOfficer
    {
        $officer = SugOfficer::findOrFail($id);
        $officer->update($data);
        return $officer;
    }
}
