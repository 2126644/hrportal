<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class LeavesExport implements FromView
{
    protected $year;

    public function __construct($year = null)
    {
        $this->year = $year ?? now()->year;
    }

    public function view(): View
    {
        // Get all leave types
        $leaveTypes = DB::table('leaves')->distinct()->pluck('leave_type');

        // Get leave counts per type per month
        $leaveReport = DB::table('leaves')
            ->selectRaw('leave_type, MONTH(start_date) as month, COUNT(*) as total')
            ->whereYear('start_date', $this->year)
            ->groupBy('leave_type', 'month')
            ->get();

        // Pivot the results
        $reportData = [];
        foreach ($leaveReport as $row) {
            $reportData[$row->leave_type][$row->month] = $row->total;
        }

        return view('exports.leave_report', [
            'leaveTypes' => $leaveTypes,
            'reportData' => $reportData,
            'year' => $this->year,
        ]);
    }
}

// FromView (for complex exports with custom formatting)
// Laravel Excel renders this view as an HTML table and converts it to Excel
// must create the Blade file for this to work

// FromCollection or FromQuery (for simple exports)
// You return a collection or query
// Laravel Excel handles the data and columns automatically, no blade needed
