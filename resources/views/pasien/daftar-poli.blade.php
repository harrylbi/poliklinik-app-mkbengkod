<x-layouts.app title="Daftar Poli">
    <div class="space-y-6 max-w-3xl mx-auto">
        {{-- Header Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Daftar Poliklinik</h1>
                <p class="text-slate-500 mt-1">Silakan pilih jadwal dan dokter untuk pemeriksaan Anda.</p>
            </div>
            <a href="{{ route('pasien.dashboard') }}" class="btn btn-ghost btn-sm text-slate-500 hover:bg-slate-100">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
        <div class="alert alert-error shadow-sm rounded-xl mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        {{-- Registration Form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6">
                <form action="{{ route('pasien.daftar-poli.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-control w-full mb-4">
                        <label class="label font-semibold text-slate-700">
                            <span class="label-text">Pilih Poliklinik & Jadwal</span>
                        </label>
                        <select name="id_jadwal" class="select select-bordered w-full @error('id_jadwal') select-error @enderror" required>
                            <option disabled selected>Pilih jadwal...</option>
                            @foreach($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}">
                                    {{ $jadwal->dokter->poli->nama_poli ?? '-' }} - dr. {{ $jadwal->dokter->nama }} 
                                    ({{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_jadwal')
                            <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                        @enderror
                    </div>

                    <div class="form-control w-full mb-6">
                        <label class="label font-semibold text-slate-700">
                            <span class="label-text">Keluhan</span>
                        </label>
                        <textarea name="keluhan" class="textarea textarea-bordered h-32 @error('keluhan') textarea-error @enderror" placeholder="Jelaskan keluhan yang Anda rasakan..." required>{{ old('keluhan') }}</textarea>
                        @error('keluhan')
                            <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                        <button type="submit" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 border-none text-white px-8 rounded-xl shadow-md">
                            <i class="fas fa-paper-plane mr-1"></i> Daftar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
