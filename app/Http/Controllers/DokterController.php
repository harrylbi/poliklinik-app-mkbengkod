<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function dashboard()
    {
        return view('dokter.dashboard');
    }

    public function exportJadwal()
    {
        $id_dokter = \Illuminate\Support\Facades\Auth::id();
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\JadwalPeriksaExport($id_dokter), 'Jadwal_Periksa.xlsx');
    }

    public function riwayatPasien()
    {
        $id_dokter = \Illuminate\Support\Facades\Auth::id();

        // Riwayat pasien yang pernah diperiksa oleh dokter ini
        $riwayatPasien = \App\Models\Periksa::with(['daftarPoli.pasien'])
            ->whereHas('daftarPoli.jadwalPeriksa', function ($query) use ($id_dokter) {
                $query->where('id_dokter', $id_dokter);
            })
            ->orderBy('tgl_periksa', 'desc')
            ->get();

        return view('dokter.riwayat.index', compact('riwayatPasien'));
    }

    public function exportRiwayat()
    {
        $id_dokter = \Illuminate\Support\Facades\Auth::id();
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RiwayatPasienExport($id_dokter), 'Riwayat_Pasien.xlsx');
    }
}
