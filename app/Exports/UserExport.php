<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UserExport implements FromView
{
    private $userinfos;

    public function __construct($userinfos)
    {
        $this->userinfos = $userinfos;
    }

    public function view(): View
    {
        return view('users.exports.users', [
            'userinfos' => $this->userinfos
        ]);
    }
}
