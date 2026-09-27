<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StudentImportService;
use Illuminate\Http\Request;

class StudentImportController extends Controller
{
    protected $importService;

    public function __construct(StudentImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index()
    {
        return view('admin.students.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        // Get header
        $header = fgetcsv($handle);

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) == count($header)) {
                $rows[] = array_combine($header, $data);
            }
        }
        fclose($handle);

        $results = $this->importService->importStudents($rows);

        return back()->with('import_results', $results);
    }
}
