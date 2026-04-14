<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pasiens = User::where('role', 'pasien')->get();
        return view('admin.pasien.index', compact('pasiens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_ktp' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        // Generate No RM (YYYYMM-XXX)
        $latestPasien = User::where('role', 'pasien')->whereNotNull('no_rm')->latest()->first();
        $nextNumber = 1;
        if ($latestPasien) {
            $parts = explode('-', $latestPasien->no_rm);
            if (count($parts) > 1) {
                $nextNumber = (int)end($parts) + 1;
            }
        }
        $no_rm = date('Ym') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $pasien = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'no_rm' => $no_rm,
            'role' => 'pasien',
        ]);

        return redirect()->route('pasien.index')
            ->with('message', 'Pasien berhasil ditambahkan dengan No. RM: ' . $no_rm)
            ->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pasien = User::findOrFail($id);
        return view('admin.pasien.edit', compact('pasien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pasien = User::findOrFail($id);
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $pasien->id,
            'password' => 'nullable|min:6',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp,' . $pasien->id,
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        $updateData = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ];
        if($request->filled('password')){
            $updateData['password'] = Hash::make($request->password);
        }

        $pasien->update($updateData);

        return redirect()->route('pasien.index')
            ->with('message','Data Pasien berhasil diupdate')
            ->with('type','success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pasien = User::findOrFail($id);
        $pasien->delete();
        return redirect()->route('pasien.index')
            ->with('message','Data Pasien berhasil dihapus')
            ->with('type','success');
    }
}
