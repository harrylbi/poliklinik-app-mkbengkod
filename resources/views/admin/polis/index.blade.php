<x-layouts.app title="Manajemen Poli">

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="alert alert-success mb-5 shadow-sm rounded-xl">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Header Content --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Poli</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola data poliklinik rumah sakit.</p>
        </div>

        <a href="{{ route('polis.create') }}" 
           class="btn bg-[#2d4499] hover:bg-[#1e2d6b] text-white border-none rounded-xl px-5">
            <i class="fas fa-plus mr-1"></i> Tambah Poli Baru
        </a>
    </div>

    {{-- Card Table --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0 overflow-hidden">
            
            <div class="overflow-x-auto">
                <table class="table w-full">
                    
                    {{-- Table Head --}}
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="font-semibold text-sm w-16 text-center">No</th>
                            <th class="font-semibold text-sm">Nama Poli</th>
                            <th class="font-semibold text-sm">Keterangan</th>
                            <th class="font-semibold text-sm w-32 text-center">Aksi</th>
                        </tr>
                    </thead>
                    
                    {{-- Table Body --}}
                    <tbody>
                        @forelse($polis as $poli)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="text-center font-medium text-slate-600">
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                <div class="font-bold text-slate-800">{{ $poli->nama_poli }}</div>
                            </td>
                            <td class="text-slate-600 text-sm">
                                {{ $poli->keterangan }}
                            </td>
                            <td>
                                <div class="flex justify-center gap-2">
                                    {{-- Edit Button --}}
                                    <a href="{{ route('polis.edit', $poli->id) }}" 
                                       class="btn btn-sm btn-ghost text-amber-500 hover:bg-amber-50 hover:text-amber-600">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('polis.destroy', $poli->id) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus poli ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-ghost text-red-500 hover:bg-red-50 hover:text-red-600">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <i class="fas fa-hospital text-4xl mb-3"></i>
                                    <p class="text-sm font-medium">Belum ada data poli.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</x-layouts.app>
