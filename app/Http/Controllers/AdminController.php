<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Poli;
use App\Models\Obat;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalDokter = User::where('role', 'dokter')->count();
        $totalPasien = User::where('role', 'pasien')->count();
        $totalPoli = Poli::count();
        $totalObat = Obat::count();

        return view('admin.dashboard', compact('totalDokter', 'totalPasien', 'totalPoli', 'totalObat'));
    }
}
