<?php

namespace App\Exports;

use App\Models\JadwalPeriksa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JadwalPeriksaExport implements FromCollection, WithHeadings, WithMapping
{
    private $id_dokter;
    private $counter = 0;

    public function __construct($id_dokter)
    {
        $this->id_dokter = $id_dokter;
    }

    public function collection()
    {
        return JadwalPeriksa::where('id_dokter', $this->id_dokter)->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Hari',
            'Jam Mulai',
            'Jam Selesai',
            'Status Aktif'
        ];
    }

    public function map($jadwal): array
    {
        $this->counter++;
        return [
            $this->counter,
            $jadwal->hari,
            \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i'),
            \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i'),
            $jadwal->aktif ? 'Aktif' : 'Tidak Aktif'
        ];
    }
}
