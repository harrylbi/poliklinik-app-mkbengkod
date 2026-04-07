<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function dashboard()
    {
        return view('dokter.dashboard');
    }
}
