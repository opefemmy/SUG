<?php

namespace App\Services;

use App\Models\School;
use App\Models\Department;
use App\Models\Programme;
use App\Models\Level;
use App\Models\AcademicSession;
use Illuminate\Support\Facades\DB;

class AcademicStructureService
{
    public function createSchool(array $data): School
    {
        return School::create($data);
    }

    public function createDepartment(array $data): Department
    {
        return Department::create($data);
    }

    public function createProgramme(array $data): Programme
    {
        return Programme::create($data);
    }

    public function createLevel(array $data): Level
    {
        return Level::create($data);
    }

    public function createSession(array $data): AcademicSession
    {
        return AcademicSession::create($data);
    }

    public function setCurrentSession(int $sessionId): void
    {
        DB::transaction(function () use ($sessionId) {
            AcademicSession::where('is_current', true)->update(['is_current' => false]);
            AcademicSession::where('id', $sessionId)->update(['is_current' => true]);
        });
    }
}
