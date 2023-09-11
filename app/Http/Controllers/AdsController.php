<?php

namespace App\Http\Controllers;

use App\Exports\UserLogsExport;
use App\Http\Requests\UpdateUserPointRequest;
use App\Models\Ad;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdsController extends Controller
{
    public function index(Request $request)
    {
      $ads = Ad::all()->sortBy('id');
      return view('ads.index', compact('ads'));
    }

    public function show($id)
    {
    }

    public function create()
    {
      return view('ads.create');
    }

    public function store(Request $request)
    {
      $ad=new Ad;
      if($request->input('enable'))
      {
        $ad->enable=$request->input('enable');
      }
      $ad->name=$request->input('name');
      $ad->start_at=$request->input('start_at');
      $ad->save();

      return redirect('ads');
    }
}
