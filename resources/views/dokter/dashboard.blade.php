<x-layouts.app title="Dashboard Dokter">
    <div class="space-y-6">
        {{-- Welcome Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h1 class="text-2xl font-bold text-slate-800">Selamat Datang, dr. {{ Auth::user()->nama }}! 🏥</h1>
            <p class="text-slate-500 mt-1">Anda login sebagai Dokter. Semoga hari ini menyenangkan dalam melayani pasien.</p>
        </div>

        {{-- Stats Section --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="stats shadow bg-white border border-slate-100 rounded-2xl p-2">
                <div class="stat">
                    <div class="stat-figure text-primary">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <div class="stat-title text-slate-500">Pasien Terlayani</div>
                    <div class="stat-value text-primary">0</div>
                    <div class="stat-desc text-slate-400">Total pemeriksaan hari ini</div>
                </div>
            </div>

            <div class="stats shadow bg-white border border-slate-100 rounded-2xl p-2">
                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="fas fa-clipboard-list text-2xl"></i>
                    </div>
                    <div class="stat-title text-slate-500">Antrian Hari Ini</div>
                    <div class="stat-value text-secondary">0</div>
                    <div class="stat-desc text-slate-400">Pasien menunggu dipanggil</div>
                </div>
            </div>

            <div class="stats shadow bg-white border border-slate-100 rounded-2xl p-2">
                <div class="stat">
                    <div class="stat-figure text-accent">
                        <i class="fas fa-notes-medical text-2xl"></i>
                    </div>
                    <div class="stat-title text-slate-500">Poliklinik</div>
                    <div class="stat-value text-accent text-2xl">Poli Umum</div>
                    <div class="stat-desc text-slate-400">Lokasi praktik aktif</div>
                </div>
            </div>
        </div>


    </div>
</x-layouts.app>