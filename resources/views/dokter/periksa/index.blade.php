<x-layouts.app title="Daftar Antrian Pasien">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Daftar Antrian Pasien
        </h2>
        <a href="{{ route('dokter.jadwal.export') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-500 hover:bg-green-600 
                text-white rounded-xl text-sm font-semibold transition">
            <i class="fas fa-file-excel text-sm"></i>
            Export Jadwal Periksa
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success shadow-sm rounded-xl mb-4 text-white">
        <i class="fas fa-check-circle text-xl mr-2"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error shadow-sm rounded-xl mb-4 text-white">
        <i class="fas fa-exclamation-circle text-xl mr-2"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No. Antrian</th>
                            <th class="px-6 py-4">Nama Pasien</th>
                            <th class="px-6 py-4">Keluhan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($pasienAntrian as $antrian)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-lg text-primary">{{ $antrian->no_antrian }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $antrian->pasien->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $antrian->keluhan }}</td>
                            <td class="px-6 py-4">
                                @if($antrian->periksas->count() > 0)
                                    <span class="badge bg-green-100 text-green-700">Sudah Diperiksa</span>
                                @else
                                    <span class="badge bg-amber-100 text-amber-700">Belum Diperiksa</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($antrian->periksas->count() == 0)
                                <a href="{{ route('dokter.periksa.create', $antrian->id) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg text-xs font-semibold transition">
                                    <i class="fas fa-stethoscope text-xs"></i> Periksa
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">Belum ada antrian pasien.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
