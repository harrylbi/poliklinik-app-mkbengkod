<x-layouts.app title="Dashboard Pasien">
    <div class="space-y-6">
        {{-- Welcome Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h1 class="text-2xl font-bold text-slate-800">Selamat Datang, {{ Auth::user()->nama }}! 👋</h1>
            <p class="text-slate-500 mt-1">Anda login sebagai Pasien. Silakan akses menu di sidebar untuk melakukan pendaftaran poli.</p>
        </div>

        {{-- Stats Section --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="stats shadow bg-white border border-slate-100 rounded-2xl p-2">
                <div class="stat">
                    <div class="stat-figure text-primary">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                    <div class="stat-title text-slate-500">Total Kunjungan</div>
                    <div class="stat-value text-primary">0</div>
                    <div class="stat-desc text-slate-400">Pemeriksaan sejauh ini</div>
                </div>
            </div>

            <div class="stats shadow bg-white border border-slate-100 rounded-2xl p-2">
                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="fas fa-notes-medical text-2xl"></i>
                    </div>
                    <div class="stat-title text-slate-500">Antrian Aktif</div>
                    <div class="stat-value text-secondary">0</div>
                    <div class="stat-desc text-slate-400">Menunggu giliran</div>
                </div>
            </div>

            <div class="stats shadow bg-white border border-slate-100 rounded-2xl p-2">
                <div class="stat">
                    <div class="stat-figure text-accent">
                        <i class="fas fa-id-card text-2xl"></i>
                    </div>
                    <div class="stat-title text-slate-500">No. Rekam Medis</div>
                    <div class="stat-value text-accent text-2xl">{{ Auth::user()->no_rm ?? '-' }}</div>
                    <div class="stat-desc text-slate-400">Gunakan untuk pendaftaran pendaftaran</div>
                </div>
            </div>
        </div>

        {{-- Action Card --}}
        <div class="card bg-gradient-to-br from-[#1e2d6b] to-[#2d4499] text-white shadow-xl rounded-2xl">
            <div class="card-body">
                <h2 class="card-title text-xl">Daftar Poli Sekarang</h2>
                <p class="opacity-80">Ingin berkonsultasi dengan dokter? Klik tombol di bawah untuk mendaftar layanan poliklinik kami.</p>
                <div class="card-actions justify-end mt-4">
                    <button class="btn btn-white text-[#1e2d6b] font-bold border-none hover:bg-slate-100 rounded-xl">
                        Daftar Poli <i class="fas fa-chevron-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>