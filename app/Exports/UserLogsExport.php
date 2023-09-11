<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UserLogsExport implements FromView
{
    private $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    public function view(): View
    {
        return view('users.exports.logs', [
            'logs' => $this->logs
        ]);
    }
}
