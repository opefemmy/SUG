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

        // Get header and remove BOM if present
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'The uploaded CSV file is empty.');
        }

        // Clean headers: remove BOM and trim whitespace
        $header = array_map(function($h) {
            return trim(preg_replace('/^\xEF\xBB\xBF/', '', $h));
        }, $header);

        // Validate required headers exist
        $required = ['matric_no', 'surname', 'first_name', 'department', 'level'];
        foreach ($required as $req) {
            if (!in_array($req, $header)) {
                fclose($handle);
                return back()->with('error', "Missing required CSV column: {$req}");
            }
        }

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) == count($header)) {
                $rows[] = array_combine($header, $data);
            }
        }
        fclose($handle);

        if (empty($rows)) {
            return back()->with('error', 'No valid data found in the CSV file.');
        }

        $results = $this->importService->importStudents($rows);

        return back()->with('import_results', $results);
    }
}
