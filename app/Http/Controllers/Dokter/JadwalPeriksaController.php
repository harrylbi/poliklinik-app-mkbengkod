<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;

class JadwalPeriksaController extends Controller
{
    public function index()
    {
        $id_dokter = Auth::id();
        $jadwals = JadwalPeriksa::where('id_dokter', $id_dokter)->get();
        return view('dokter.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        return view('dokter.jadwal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        JadwalPeriksa::create([
            'id_dokter' => Auth::id(),
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'aktif' => false
        ]);

        return redirect()->route('dokter.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        $jadwal = JadwalPeriksa::where('id_dokter', Auth::id())->findOrFail($id);
        return view('dokter.jadwal.edit', compact('jadwal'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jadwal = JadwalPeriksa::where('id_dokter', Auth::id())->findOrFail($id);
        $jadwal->update([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('dokter.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = JadwalPeriksa::where('id_dokter', Auth::id())->findOrFail($id);
        $jadwal->delete();

        return redirect()->route('dokter.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function toggleAktif($id)
    {
        $jadwal = JadwalPeriksa::where('id_dokter', Auth::id())->findOrFail($id);

        if ($jadwal->aktif == false) {
            $hasActive = JadwalPeriksa::where('id_dokter', Auth::id())->where('aktif', true)->exists();
            if ($hasActive) {
                return redirect()->back()->with('error', 'Hanya satu jadwal yang dapat aktif (antrean buka) dalam satu waktu. Tolong matikan jadwal aktif lainnya terlebih dahulu.');
            }
            $jadwal->aktif = true;
            $jadwal->save();
            return redirect()->back()->with('success', "Antrian untuk jadwal hari {$jadwal->hari} telah DIBUKA.");
        } else {
            $jadwal->aktif = false;
            $jadwal->save();
            return redirect()->back()->with('success', "Antrian untuk jadwal hari {$jadwal->hari} telah DITUTUP.");
        }
    }
}
