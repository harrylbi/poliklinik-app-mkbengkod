<x-layouts.app title="Riwayat Pasien">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Riwayat Pasien</h2>
            <p class="text-slate-500 text-sm mt-1">Daftar rekam medis pasien yang pernah Anda periksa</p>
        </div>
        <a href="{{ route('dokter.riwayat.export') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-500 hover:bg-green-600 
                text-white rounded-xl text-sm font-semibold transition">
            <i class="fas fa-file-excel text-sm"></i>
            Export Excel
        </a>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama Pasien</th>
                            <th class="px-6 py-4">Tanggal Periksa</th>
                            <th class="px-6 py-4">Keluhan</th>
                            <th class="px-6 py-4">Catatan Dokter</th>
                            <th class="px-6 py-4 text-right">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($riwayats as $riwayat)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-semibold text-slate-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $riwayat->daftarPoli->pasien->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-600">
                                {{ \Carbon\Carbon::parse($riwayat->tgl_periksa)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 truncate max-w-xs">
                                {{ $riwayat->daftarPoli->keluhan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 truncate max-w-xs">
                                {{ $riwayat->catatan }}
                            </td>
                            <td class="px-6 py-4 font-bold text-primary text-right">
                                Rp {{ number_format($riwayat->biaya_periksa, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <i class="fas fa-notes-medical text-3xl mb-3 block"></i>
                                Belum ada riwayat pemeriksaan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>
