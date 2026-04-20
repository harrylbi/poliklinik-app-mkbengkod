<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PasienExport implements FromCollection, WithHeadings, WithMapping
{
    private $counter = 0;

    public function collection()
    {
        return User::where('role', 'pasien')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No Rekam Medis (RM)',
            'KTP',
            'Nama Pasien',
            'Email',
            'No HP',
            'Alamat'
        ];
    }

    public function map($pasien): array
    {
        $this->counter++;
        return [
            $this->counter,
            $pasien->no_rm ?? '-',
            $pasien->ktp ?? '-',
            $pasien->nama,
            $pasien->email,
            $pasien->no_hp,
            $pasien->alamat
        ];
    }
}
