<x-layouts.app title="Jadwal Periksa">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Jadwal Periksa
        </h2>

        <a href="{{ route('dokter.jadwal.create') }}" class="btn bg-[#2d4499] hover:bg-[#1e2d6b] 
                  text-white border-none rounded-lg px-5">
            <i class="fas fa-plus"></i>
            Tambah Jadwal Periksa
        </a>
    </div>

    {{-- Alert Flash Message --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible mb-4 rounded-xl shadow-sm text-white" role="alert">
        <i class="fas fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-error alert-dismissible mb-4 rounded-xl shadow-sm text-white" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">

                    {{-- Head --}}
                    <thead class="bg-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Hari</th>
                            <th class="px-6 py-4">Jam Mulai</th>
                            <th class="px-6 py-4">Jam Selesai</th>
                            <th class="px-6 py-4 text-center">Status Antrian</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Body --}}
                    <tbody>
                        @forelse ($jadwals as $jadwal)
                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-6 py-4 text-slate-500">
                                {{ $jadwal->id }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $jadwal->hari }}
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($jadwal->aktif)
                                    <span class="badge bg-green-100 text-green-700 border-green-200 px-3 py-1 font-bold">Aktif (Dibuka)</span>
                                @else
                                    <span class="badge bg-slate-100 text-slate-500 border-slate-200 px-3 py-1 font-bold">Tidak Aktif</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    {{-- Toggle --}}
                                    <form action="{{ route('dokter.jadwal.toggle', $jadwal->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @if($jadwal->aktif)
                                            <button type="submit" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white border-none rounded-lg px-4" onclick="return confirm('Tutup antrian ini?')">
                                                <i class="fas fa-lock"></i> Tutup
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm bg-green-600 hover:bg-green-700 text-white border-none rounded-lg px-4" onclick="return confirm('Buka antrian ini sekarang?')">
                                                <i class="fas fa-bullhorn"></i> Buka Antrian
                                            </button>
                                        @endif
                                    </form>

                                    {{-- Edit --}}
                                    <a href="{{ route('dokter.jadwal.edit', $jadwal->id) }}" class="btn btn-sm bg-amber-500 hover:bg-amber-600 
                                                  text-white border-none rounded-lg px-4">
                                        <i class="fas fa-pen-to-square"></i>
                                        Edit
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('dokter.jadwal.destroy', $jadwal->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus Data Jadwal Periksa ini?')" class="btn btn-sm bg-red-500 hover:bg-red-600 
                                                       text-white border-none rounded-lg px-4">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        @empty
<tr>
    <td colspan="6" class="text-center py-14 text-slate-400">
        <div class="flex flex-col items-center justify-center gap-2">
            <i class="fas fa-inbox text-3xl"></i>
            <span>Belum ada Jadwal Periksa</span>
        </div>
    </td>
</tr>
@endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 2000);
    </script>

</x-layouts.app>
