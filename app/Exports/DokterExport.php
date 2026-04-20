<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DokterExport implements FromCollection, WithHeadings, WithMapping
{
    private $counter = 0;

    public function collection()
    {
        return User::where('role', 'dokter')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Dokter',
            'Email',
            'No HP',
            'Alamat'
        ];
    }

    public function map($dokter): array
    {
        $this->counter++;
        return [
            $this->counter,
            $dokter->nama,
            $dokter->email,
            $dokter->no_hp,
            $dokter->alamat
        ];
    }
}
