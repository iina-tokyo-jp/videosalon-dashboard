<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AppraiserExport implements FromView
{
    private $appraisers;

    public function __construct($appraisers)
    {
        $this->appraisers = $appraisers;
    }

    public function view(): View
    {
        return view('appraisers.exports.appraisers', [
            'appraisers' => $this->appraisers
        ]);
    }
}
