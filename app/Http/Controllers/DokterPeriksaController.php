<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use App\Models\DetailPeriksa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DokterPeriksaController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();
        
        // Get schedules of the logged-in doctor
        $jadwalIds = JadwalPeriksa::where('id_dokter', $dokterId)->pluck('id');

        // Get passengers queuing for this doctor, order by not checked yet
        $pasienAntrian = DaftarPoli::with(['pasien', 'periksas'])
            ->whereIn('id_jadwal', $jadwalIds)
            ->orderByRaw('(SELECT COUNT(*) FROM periksa WHERE periksa.id_daftar_poli = daftar_poli.id) ASC')
            ->orderBy('no_antrian', 'asc')
            ->get();

        return view('dokter.periksa.index', compact('pasienAntrian'));
    }

    public function create($id_daftar_poli)
    {
        $antrian = DaftarPoli::with('pasien')->findOrFail($id_daftar_poli);
        
        // Ensure no periksa exists yet
        if ($antrian->periksas()->count() > 0) {
            return redirect()->route('dokter.periksa.index')->with('error', 'Pasien ini sudah diperiksa.');
        }

        $obats = Obat::all(); // Provide medicines for prescription
        
        return view('dokter.periksa.create', compact('antrian', 'obats'));
    }

    public function store(Request $request, $id_daftar_poli)
    {
        $request->validate([
            'tgl_periksa' => 'required|date',
            'catatan' => 'required|string',
            'obats' => 'nullable|array',
            'obats.*' => 'exists:obat,id'
        ]);

        $antrian = DaftarPoli::findOrFail($id_daftar_poli);
        
        if ($antrian->periksas()->count() > 0) {
            return redirect()->route('dokter.periksa.index')->with('error', 'Pasien ini sudah diperiksa.');
        }

        DB::beginTransaction();
        try {
            // Cost basis: 150.000 for service + medicine prices
            $biaya_periksa = 150000;
            $obatIds = $request->input('obats', []);

            // 1. Check and deduct medicine stocks
            foreach ($obatIds as $obatId) {
                $obat = Obat::lockForUpdate()->find($obatId); // PESSIMISTIC LOCK: prevents race conditions
                
                if ($obat->stok < 1) {
                    throw new \Exception("Stok {$obat->nama_obat} habis. Tidak dapat meresepkan obat ini, harap batalkan atau pilih obat lain.");
                }
                
                // Deduct stock
                $obat->stok -= 1;
                $obat->save();

                // Add to total cost
                $biaya_periksa += $obat->harga;
            }

            // 2. Insert into Periksa table
            $periksa = Periksa::create([
                'id_daftar_poli' => $id_daftar_poli,
                'tgl_periksa' => $request->tgl_periksa,
                'catatan' => $request->catatan,
                'biaya_periksa' => $biaya_periksa
            ]);

            // 3. Insert into DetailPeriksa table
            foreach ($obatIds as $obatId) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $obatId
                ]);
            }

            DB::commit();
            return redirect()->route('dokter.periksa.index')->with('success', 'Hasil pemeriksaan dan resep berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
