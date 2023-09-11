<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CsvExport implements FromView
{
    private $records;

    private $view;

    private $days;

    public function __construct($records, $view, $days)
    {
        $this->records = $records;
        $this->view = $view;
        $this->days = $days;
    }

    public function view(): View
    {
        return view($this->view, [
            'records' => $this->records,
            'days' => $this->days
        ]);
    }
}
