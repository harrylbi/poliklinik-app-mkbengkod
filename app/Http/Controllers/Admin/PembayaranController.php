<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Periksa;

class PembayaranController extends Controller
{
    public function index()
    {
        // View tagihan yang menunggu verifikasi dan yang sudah lunas
        $tagihans = Periksa::with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter'])
            ->whereIn('status_pembayaran', ['menunggu_verifikasi', 'lunas'])
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return view('admin.pembayaran.index', compact('tagihans'));
    }

    public function verifikasi($id)
    {
        $periksa = Periksa::findOrFail($id);
        
        if ($periksa->status_pembayaran == 'menunggu_verifikasi') {
            $periksa->status_pembayaran = 'lunas';
            $periksa->save();
            return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil diverifikasi.');
        }

        return redirect()->back()->with('error', 'Status pembayaran tidak valid.');
    }
}
