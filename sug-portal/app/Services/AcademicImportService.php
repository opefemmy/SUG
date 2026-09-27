<?php

namespace App\Services;

use App\Models\School;
use App\Models\Department;
use App\Models\Programme;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class AcademicImportService
{
    /**
     * Import schools from a CSV file.
     * Format: name, code
     */
    public function importSchools(string $filePath): array
    {
        $results = ['success' => 0, 'errors' => []];
        $handle = fopen($filePath, 'r');

        // Skip header
        fgetcsv($handle);

        while (($data = fgetcsv($handle)) !== FALSE) {
            if (count($data) < 1) continue;

            try {
                $name = trim($data[0]);

                School::updateOrCreate(
                    ['name' => $name],
                    [
                        'slug' => Str::slug($name),
                    ]
                );
                $results['success']++;
            } catch (Exception $e) {
                $results['errors'][] = "Error importing school {$data[0]}: " . $e->getMessage();
            }
        }
        fclose($handle);
        return $results;
    }

    /**
     * Import hierarchy from a CSV file.
     * Format: school_name, department_name, programme_name, programme_code, duration_years
     */
    public function importHierarchy(string $filePath): array
    {
        $results = ['success' => 0, 'errors' => []];
        $handle = fopen($filePath, 'r');

        // Skip header
        fgetcsv($handle);

        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle)) !== FALSE) {
                if (count($data) < 4) continue;

                $schoolName = trim($data[0]);
                $deptName = trim($data[1]);
                $progName = trim($data[2]);
                $duration = (int)trim($data[3]);

                // 1. Resolve/Create School
                $school = School::firstOrCreate(
                    ['name' => $schoolName],
                    ['slug' => Str::slug($schoolName)]
                );

                // 2. Resolve/Create Department
                $department = Department::firstOrCreate(
                    ['name' => $deptName, 'school_id' => $school->id],
                    ['slug' => Str::slug($deptName)]
                );

                // 3. Resolve/Create Programme
                Programme::updateOrCreate(
                    ['name' => $progName, 'department_id' => $department->id],
                    [
                        'duration_years' => $duration,
                    ]
                );
                $results['success']++;
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $results['errors'][] = "Critical import error: " . $e->getMessage();
        }
        fclose($handle);
        return $results;
    }
}
