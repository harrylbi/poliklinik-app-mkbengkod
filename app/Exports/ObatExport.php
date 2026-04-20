<?php

namespace App\Exports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ObatExport implements FromCollection, WithHeadings, WithMapping
{
    private $counter = 0;

    public function collection()
    {
        return Obat::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Obat',
            'Kemasan',
            'Harga (Rp)',
            'Stok'
        ];
    }

    public function map($obat): array
    {
        $this->counter++;
        return [
            $this->counter,
            $obat->nama_obat,
            $obat->kemasan,
            $obat->harga,
            $obat->stok
        ];
    }
}
