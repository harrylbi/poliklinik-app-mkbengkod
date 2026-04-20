<x-layouts.app title="Manajemen Jadwal">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Jadwal</h2>
            <p class="text-slate-500 text-sm mt-1">Kelola jam kerja Anda dan buka antrian untuk pasien</p>
        </div>
        <button class="btn btn-primary" onclick="document.getElementById('modal_create_jadwal').showModal()">
            <i class="fas fa-plus mr-1"></i> Tambah Jadwal
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success shadow-sm rounded-xl mb-4 text-white">
        <i class="fas fa-check-circle text-xl mr-2"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error shadow-sm rounded-xl mb-4 text-white">
        <i class="fas fa-exclamation-triangle text-xl mr-2"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Hari</th>
                            <th class="px-6 py-4">Jam Mulai</th>
                            <th class="px-6 py-4">Jam Selesai</th>
                            <th class="px-6 py-4 text-center">Status Antrian</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($jadwals as $jadwal)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-slate-500 font-semibold">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $jadwal->hari }}</td>
                            <td class="px-6 py-4 font-mono text-slate-600">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</td>
                            <td class="px-6 py-4 font-mono text-slate-600">{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($jadwal->aktif)
                                    <span class="badge bg-green-100 text-green-700 border-green-200 px-3 py-1 font-bold">Aktif (Dibuka)</span>
                                @else
                                    <span class="badge bg-slate-100 text-slate-500 border-slate-200 px-3 py-1 font-bold">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- Tombol Toggle Buka/Tutup Antrian --}}
                                    <form action="{{ route('dokter.jadwal.toggle', $jadwal->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @if($jadwal->aktif)
                                            <button type="submit" class="btn btn-sm btn-error" onclick="return confirm('Tutup antrian ini?')">
                                                <i class="fas fa-lock text-xs mr-1"></i> Tutup
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Buka antrian ini sekarang?')">
                                                <i class="fas fa-bullhorn text-xs mr-1"></i> Buka Antrian
                                            </button>
                                        @endif
                                    </form>

                                    {{-- Tombol Edit --}}
                                    <button class="btn btn-sm btn-outline btn-primary" onclick="document.getElementById('modal_edit_jadwal_{{ $jadwal->id }}').showModal()">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('dokter.jadwal.destroy', $jadwal->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline btn-error" onclick="return confirm('Hapus jadwal ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit Jadwal --}}
                        <dialog id="modal_edit_jadwal_{{ $jadwal->id }}" class="modal text-left">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg mb-4">Edit Jadwal: {{ $jadwal->hari }}</h3>
                                <form action="{{ route('dokter.jadwal.update', $jadwal->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-control mb-4">
                                        <label class="label"><span class="label-text font-bold">Hari</span></label>
                                        <select name="hari" class="select select-bordered w-full" required>
                                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                                                <option value="{{ $h }}" {{ $jadwal->hari == $h ? 'selected' : '' }}>{{ $h }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="form-control">
                                            <label class="label"><span class="label-text font-bold">Jam Mulai</span></label>
                                            <input type="time" name="jam_mulai" class="input input-bordered w-full" value="{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}" required />
                                        </div>
                                        <div class="form-control">
                                            <label class="label"><span class="label-text font-bold">Jam Selesai</span></label>
                                            <input type="time" name="jam_selesai" class="input input-bordered w-full" value="{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}" required />
                                        </div>
                                    </div>
                                    <div class="modal-action">
                                        <button type="button" class="btn" onclick="document.getElementById('modal_edit_jadwal_{{ $jadwal->id }}').close()">Batal</button>
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                                    </div>
                                </form>
                            </div>
                            <form method="dialog" class="modal-backdrop"><button>close</button></form>
                        </dialog>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400">
                                <i class="fas fa-calendar-times text-3xl mb-3 block"></i>
                                Belum ada jadwal. Klik "Tambah Jadwal" untuk membuat jadwal baru.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Create Jadwal --}}
    <dialog id="modal_create_jadwal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Tambah Jadwal Baru</h3>
            <form action="{{ route('dokter.jadwal.store') }}" method="POST">
                @csrf
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-bold">Hari</span></label>
                    <select name="hari" class="select select-bordered w-full" required>
                        <option value="" disabled selected>Pilih Hari...</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Jam Mulai</span></label>
                        <input type="time" name="jam_mulai" class="input input-bordered w-full" required />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Jam Selesai</span></label>
                        <input type="time" name="jam_selesai" class="input input-bordered w-full" required />
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="document.getElementById('modal_create_jadwal').close()">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

</x-layouts.app>
