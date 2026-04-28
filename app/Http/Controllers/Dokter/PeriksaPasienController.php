<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DaftarPoli;
use Illuminate\Support\Facades\Auth;

class PeriksaPasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokterid=Auth::user()->dokter->id;

        $daftarpasien=DaftarPoli::with(['pasien','jadwalPeriksa', 'periksas'])
        ->whereHas('jadwalPeriksa', function($query) use ($dokterid){
            $query->where('id_dokter', $dokterid);
        })
        ->orderBy('no_antrian', 'asc')
        ->get();

        return view('dokter.periksa.index', compact('daftarpasien'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $obat=Obat::all();
        return view('dokter.periksa.create', compact('obat', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'obat_json'=>'required',
            'catatan' => 'nullable|string',
            'biaya_periksa'=>'required|integer',
        ]);

        $obatIds = json_decode($request->obat_json, true);
        $periksa = Periksa::create([
            'id_daftar_poli'=>$request->id_daftar_poli,
            'keluhan'=>$request->keluhan,
            'tindakan'=>$request->tindakan,
            'obat_json'=>$request->obat_json,
            'biaya_periksa'=>$request->biaya_periksa,
        ]);


        foreach ($obatIds as $obatId) {
            DetailPeriksa::create([
                'id_periksa'=>$periksa->id,
                'id_obat'=>$obatId,
            ]); 

        }

        $periksa=Periksa::create([
            'id_daftar_poli'=>$request->id_daftar_poli,
            'keluhan'=>$request->keluhan,
            'tindakan'=>$request->tindakan,
            'obat_json'=>$request->obat_json,
        ]);

        return redirect()->route('dokter.periksa.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
