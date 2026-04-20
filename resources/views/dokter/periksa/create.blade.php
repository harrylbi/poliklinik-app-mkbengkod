<x-layouts.app title="Pemeriksaan Pasien">
    <div class="space-y-6 max-w-4xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('dokter.periksa.index') }}" class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-800">Pemeriksaan Pasien</h2>
        </div>

        @if(session('error'))
        <div class="alert alert-error shadow-sm rounded-xl mb-4 text-white">
            <i class="fas fa-exclamation-circle text-xl mr-2"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
            <div class="card-body p-8">
                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-6 flex justify-between items-center">
                    <div>
                        <p class="text-indigo-900 font-bold mb-1">Nama Pasien: {{ $antrian->pasien->nama }}</p>
                        <p class="text-indigo-700 text-sm">Keluhan: {{ $antrian->keluhan }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-indigo-500 uppercase font-bold tracking-widest">No Antrian</p>
                        <h3 class="text-4xl text-indigo-700 font-extrabold">{{ $antrian->no_antrian }}</h3>
                    </div>
                </div>

                <form action="{{ route('dokter.periksa.store', $antrian->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="form-control w-full">
                            <label class="label font-semibold text-slate-700">Tanggal Periksa <span class="text-red-500">*</span></label>
                            <input type="date" name="tgl_periksa" value="{{ old('tgl_periksa', date('Y-m-d')) }}" class="w-full px-4 py-2 rounded-lg border-2 focus:border-primary focus:outline-none @error('tgl_periksa') border-red-500 @enderror" required>
                            @error('tgl_periksa')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="form-control w-full mb-6">
                        <label class="label font-semibold text-slate-700">Catatan Pemeriksaan <span class="text-red-500">*</span></label>
                        <textarea name="catatan" rows="4" class="textarea textarea-bordered border-2 focus:border-primary focus:outline-none w-full @error('catatan') border-red-500 @enderror" placeholder="Catatan hasil pemeriksaan, diagnosa, tindakan, dll." required>{{ old('catatan') }}</textarea>
                        @error('catatan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-control w-full mb-8">
                        <label class="label font-semibold text-slate-700 block mb-2">
                            Resep Obat
                        </label>
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 max-h-64 overflow-y-auto">
                            @forelse($obats as $obat)
                                <label class="cursor-pointer flex items-center justify-between p-3 border-b border-slate-200 hover:bg-white last:border-b-0 {{ $obat->stok < 1 ? 'opacity-50 grayscale' : '' }}">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="obats[]" value="{{ $obat->id }}" class="checkbox checkbox-primary" {{ $obat->stok < 1 ? 'disabled' : '' }} {{ in_array($obat->id, old('obats', [])) ? 'checked' : '' }} />
                                        <span>
                                            <span class="font-bold text-slate-800">{{ $obat->nama_obat }}</span> 
                                            <span class="text-slate-500 text-sm">({{ $obat->kemasan }}) - Rp {{ number_format($obat->harga, 0, ',', '.') }}</span>
                                        </span>
                                    </div>
                                    <div>
                                        @if($obat->stok < 1)
                                            <span class="badge bg-red-100 text-red-700 font-bold border-red-200">Habis</span>
                                        @elseif($obat->stok < 10)
                                            <span class="badge bg-orange-100 text-orange-700 font-bold border-orange-200">Sisa {{ $obat->stok }}</span>
                                        @else
                                            <span class="badge bg-green-100 text-green-700 font-bold border-green-200">Stok: {{ $obat->stok }}</span>
                                        @endif
                                    </div>
                                </label>
                            @empty
                                <div class="text-center py-4 text-slate-400">Belum ada data obat.</div>
                            @endforelse
                        </div>
                        @error('obats')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@error('obats.*')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex flex-row-reverse gap-3 mt-6 pt-4 border-t border-slate-100">
                        <button type="submit" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 border-none text-white px-8 rounded-xl shadow-md">
                            <i class="fas fa-save mr-1"></i> Simpan Periksa & Resep
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
