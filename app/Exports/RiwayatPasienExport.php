<?php

namespace App\Exports;

use App\Models\Periksa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RiwayatPasienExport implements FromCollection, WithHeadings, WithMapping
{
    private $id_dokter;
    private $counter = 0;

    public function __construct($id_dokter)
    {
        $this->id_dokter = $id_dokter;
    }

    public function collection()
    {
        // Get all checking history done by this doctor
        return Periksa::with(['daftarPoli.pasien'])
            ->whereHas('daftarPoli.jadwalPeriksa', function ($query) {
                $query->where('id_dokter', $this->id_dokter);
            })
            ->orderBy('tgl_periksa', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Periksa',
            'Nama Pasien',
            'No RM',
            'Keluhan Pasien',
            'Catatan Dokter',
            'Total Biaya (Rp)'
        ];
    }

    public function map($periksa): array
    {
        $this->counter++;
        return [
            $this->counter,
            \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d-m-Y'),
            $periksa->daftarPoli->pasien->nama ?? '-',
            $periksa->daftarPoli->pasien->no_rm ?? '-',
            $periksa->daftarPoli->keluhan ?? '-',
            $periksa->catatan,
            $periksa->biaya_periksa
        ];
    }
}
