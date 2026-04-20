<x-layouts.app title="Riwayat Pendaftaran">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Riwayat Pendaftaran Poli
        </h2>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Poli & Dokter</th>
                            <th class="px-6 py-4">Jadwal Periksa</th>
                            <th class="px-6 py-4">No. Antrian</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($riwayats as $riwayat)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-semibold text-slate-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli }}</div>
                                <div class="text-xs text-slate-500">Dr. {{ $riwayat->jadwalPeriksa->dokter->nama }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold">{{ $riwayat->jadwalPeriksa->hari }}</div>
                                <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($riwayat->jadwalPeriksa->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($riwayat->jadwalPeriksa->jam_selesai)->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-lg text-primary text-center">
                                {{ $riwayat->no_antrian }}
                            </td>
                            <td class="px-6 py-4">
                                @if($riwayat->periksas->count() > 0)
                                    <span class="badge bg-green-100 text-green-700 font-bold">Sudah Diperiksa</span>
                                @else
                                    <span class="badge bg-amber-100 text-amber-700 font-bold">Belum Diperiksa</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($riwayat->periksas->count() > 0)
                                    <a href="{{ route('pasien.riwayat.detail', $riwayat->id) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg text-xs font-semibold transition border border-indigo-200">
                                        <i class="fas fa-file-medical text-xs"></i> Detail
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic">Menunggu...</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <i class="fas fa-notes-medical text-3xl mb-3 block"></i>
                                Belum ada riwayat pendaftaran.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>
