<x-layouts.app title="Detail Pemeriksaan">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('pasien.riwayat.index') }}" class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">
            Detail Hasil Pemeriksaan
        </h2>
    </div>

    @php
        $periksa = $riwayat->periksas->first();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Kolom Kiri: Info Pendaftaran & Pemeriksaan --}}
        <div class="col-span-1 md:col-span-1 space-y-6">
            <div class="card bg-base-100 shadow-md border border-slate-200 rounded-2xl">
                <div class="card-body p-6">
                    <h3 class="font-bold text-slate-800 mb-4 border-b pb-2"><i class="fas fa-clipboard-list mr-2 text-primary"></i> Info Pendaftaran</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-slate-500 text-xs">Poliklinik</p>
                            <p class="font-semibold text-slate-800">{{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs">Dokter Pemeriksa</p>
                            <p class="font-semibold text-slate-800">Dr. {{ $riwayat->jadwalPeriksa->dokter->nama }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs">Jadwal</p>
                            <p class="font-semibold text-slate-800">{{ $riwayat->jadwalPeriksa->hari }} ({{ \Carbon\Carbon::parse($riwayat->jadwalPeriksa->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($riwayat->jadwalPeriksa->jam_selesai)->format('H:i') }})</p>
                        </div>
                        <div class="pt-2 border-t mt-2">
                            <p class="text-slate-500 text-xs">No. Antrian Anda</p>
                            <p class="font-bold text-2xl text-primary">{{ $riwayat->no_antrian }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Catatan Dokter, Obat, dan Biaya --}}
        <div class="col-span-1 md:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-md border border-slate-200 rounded-2xl">
                <div class="card-body p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="font-bold text-slate-800"><i class="fas fa-user-md mr-2 text-primary"></i> Hasil Pemeriksaan</h3>
                        <span class="text-xs font-semibold px-2 py-1 bg-slate-100 text-slate-600 rounded">
                            Tgl: {{ \Carbon\Carbon::parse($periksa->tgl_periksa)->translatedFormat('d F Y') }}
                        </span>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Catatan Dari Dokter</h4>
                        <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl text-indigo-900 leading-relaxed font-medium">
                            {!! nl2br(e($periksa->catatan)) !!}
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Resep Obat</h4>
                        
                        @if($periksa->detailPeriksas->count() > 0)
                        <div class="overflow-hidden border border-slate-200 rounded-xl">
                            <table class="table w-full text-sm">
                                <thead class="bg-slate-50 text-slate-500">
                                    <tr>
                                        <th class="px-4 py-2 font-semibold">Nama Obat</th>
                                        <th class="px-4 py-2 font-semibold text-right">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalObat = 0; @endphp
                                    @foreach($periksa->detailPeriksas as $detail)
                                    @php $totalObat += $detail->obat->harga; @endphp
                                    <tr class="border-t border-slate-100">
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-slate-800">{{ $detail->obat->nama_obat }}</div>
                                            <div class="text-xs text-slate-500">{{ $detail->obat->kemasan }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-right text-slate-600 font-medium">
                                            Rp {{ number_format($detail->obat->harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-indigo-50">
                                    <tr>
                                        <td class="px-4 py-3 font-bold text-indigo-900 text-right">Total Resep:</td>
                                        <td class="px-4 py-3 font-bold text-indigo-900 text-right">Rp {{ number_format($totalObat, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-bold text-indigo-900 text-right">Jasa Dokter:</td>
                                        <td class="px-4 py-3 font-bold text-indigo-900 text-right">Rp 150.000</td>
                                    </tr>
                                    <tr class="border-t-2 border-indigo-200 text-base">
                                        <td class="px-4 py-4 font-black text-indigo-900 text-right">GRAND TOTAL:</td>
                                        <td class="px-4 py-4 font-black text-indigo-900 text-right">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="p-4 border border-dashed border-slate-300 rounded-xl text-center text-slate-400">
                            Tidak ada resep obat yang diberikan.
                        </div>
                        <div class="mt-4 flex justify-between px-4">
                            <span class="font-bold text-slate-700">Jasa Dokter:</span>
                            <span class="font-bold text-primary">Rp 150.000</span>
                        </div>
                        <div class="mt-2 text-xl flex justify-between px-4 pt-2 border-t">
                            <span class="font-black text-slate-800">TOTAL BIAYA:</span>
                            <span class="font-black text-primary">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
