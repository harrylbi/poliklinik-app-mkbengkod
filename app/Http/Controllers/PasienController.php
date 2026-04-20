<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;

class PasienController extends Controller
{
    public function dashboard()
    {
        $pasienId = Auth::id();

        // Get Active Queue: A DaftarPoli where no Periksa exists
        $antrianAktif = DaftarPoli::with('jadwalPeriksa.dokter.poli')
            ->where('id_pasien', $pasienId)
            ->whereDoesntHave('periksas')
            ->orderBy('created_at', 'desc')
            ->first();

        // Get all schedules for the table
        $jadwals = JadwalPeriksa::with(['dokter.poli'])->get();

        // We can pre-calculate the currently served number for each schedule just in case
        $jadwals->each(function ($jadwal) {
            $jadwal->sedang_dilayani = DaftarPoli::where('id_jadwal', $jadwal->id)
                ->whereDoesntHave('periksas')
                ->min('no_antrian') ?? '-';
        });

        // Current served number for the active queue (if any)
        if ($antrianAktif) {
            $antrianAktif->sedang_dilayani = DaftarPoli::where('id_jadwal', $antrianAktif->id_jadwal)
                ->whereDoesntHave('periksas')
                ->min('no_antrian') ?? '-';
        }

        return view('pasien.dashboard', compact('antrianAktif', 'jadwals'));
    }

    public function getLiveQueueStatus()
    {
        $pasienId = Auth::id();

        // Fetch the active queue
        $antrianAktif = DaftarPoli::where('id_pasien', $pasienId)
            ->whereDoesntHave('periksas')
            ->first();

        $antrianInfo = null;
        if ($antrianAktif) {
            $sedang_dilayani = DaftarPoli::where('id_jadwal', $antrianAktif->id_jadwal)
                ->whereDoesntHave('periksas')
                ->min('no_antrian') ?? '-';

            $antrianInfo = [
                'id_jadwal' => $antrianAktif->id_jadwal,
                'sedang_dilayani' => $sedang_dilayani
            ];
        }

        // Fetch currently served numbers for all schedules
        $jadwals = JadwalPeriksa::all();
        $jadwalsData = $jadwals->map(function ($jadwal) {
            return [
                'id_jadwal' => $jadwal->id,
                'sedang_dilayani' => DaftarPoli::where('id_jadwal', $jadwal->id)
                    ->whereDoesntHave('periksas')
                    ->min('no_antrian') ?? '-'
            ];
        });

        return response()->json([
            'antrian_aktif' => $antrianInfo,
            'jadwals' => $jadwalsData
        ]);
    }

    public function createDaftarPoli()
    {
        $jadwals = JadwalPeriksa::with(['dokter.poli'])->get();
        return view('pasien.daftar-poli', compact('jadwals'));
    }

    public function storeDaftarPoli(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'required|string|max:1000'
        ]);

        // Ketentuan: Pasien tidak dapat mendaftar ke dua poli sekaligus
        // Ketentuan: Pasien hanya dapat mendaftar kembali setelah proses pemeriksaan selesai
        $activeQueue = DaftarPoli::where('id_pasien', Auth::id())
            ->whereDoesntHave('periksas')
            ->first();

        if ($activeQueue) {
            return redirect()->back()->with('error', 'Anda tidak dapat mendaftar. Anda masih memiliki antrian aktif (belum diperiksa) pada poli lain atau jadwal ini.');
        }

        // Generate No Antrian
        $lastQueue = DaftarPoli::where('id_jadwal', $request->id_jadwal)
                ->orderBy('no_antrian', 'desc')
                ->first();
        $noAntrian = $lastQueue ? $lastQueue->no_antrian + 1 : 1;

        DaftarPoli::create([
            'id_pasien' => Auth::id(),
            'id_jadwal' => $request->id_jadwal,
            'keluhan' => $request->keluhan,
            'no_antrian' => $noAntrian
        ]);

        return redirect()->route('pasien.dashboard')->with('success', 'Berhasil mendaftar poli. Nomor antrian Anda adalah ' . $noAntrian);
    }
}
