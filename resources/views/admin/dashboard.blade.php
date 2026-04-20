<x-layouts.app title="Dashboard Admin">
    <div class="space-y-6">
        {{-- Welcome Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h1 class="text-2xl font-bold text-slate-800">Selamat Datang, Admin {{ Auth::user()->nama }}! ⚙️</h1>
            <p class="text-slate-500 mt-1">Anda login sebagai Administrator. Kelola data master poliklinik di sini.</p>
        </div>

        {{-- Stats Section --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="stats shadow bg-white border border-slate-100 rounded-2xl p-2">
                <div class="stat">
                    <div class="stat-figure text-primary">
                        <i class="fas fa-users-cog text-xl"></i>
                    </div>
                    <div class="stat-title text-slate-500">Total Dokter</div>
                    <div class="stat-value text-primary text-3xl">{{ $totalDokter }}</div>
                </div>
            </div>

            <div class="stats shadow bg-white border border-slate-100 rounded-2xl p-2">
                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="fas fa-hospital text-xl"></i>
                    </div>
                    <div class="stat-title text-slate-500">Total Poli</div>
                    <div class="stat-value text-secondary text-3xl">{{ $totalPoli }}</div>
                </div>
            </div>

            <div class="stats shadow bg-white border border-slate-100 rounded-2xl p-2">
                <div class="stat">
                    <div class="stat-figure text-accent">
                        <i class="fas fa-id-card text-xl"></i>
                    </div>
                    <div class="stat-title text-slate-500">Total Pasien</div>
                    <div class="stat-value text-accent text-3xl">{{ $totalPasien }}</div>
                </div>
            </div>

            <div class="stats shadow bg-white border border-slate-100 rounded-2xl p-2">
                <div class="stat">
                    <div class="stat-figure text-success">
                        <i class="fas fa-pills text-xl"></i>
                    </div>
                    <div class="stat-title text-slate-500">Total Obat</div>
                    <div class="stat-value text-success text-3xl">{{ $totalObat }}</div>
                </div>
            </div>
        </div>

        {{-- Main Dashboard Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6">
            <div class="card bg-white shadow-sm border border-slate-100 rounded-2xl p-6">
                 <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i class="fas fa-user-md text-sm"></i>
                    </div>
                    Kelola Dokter Baru
                </h2>
                <p class="text-slate-500 mb-6">Tambah data dokter baru, atau update dokter yang sudah ada dengan mudah di sini.</p>
                <button class="btn btn-primary bg-[#1e2d6b] border-none hover:bg-[#2d4499] text-white rounded-xl">
                    Kelola Dokter <i class="fas fa-chevron-right ml-1"></i>
                </button>
            </div>

            <div class="card bg-white shadow-sm border border-slate-100 rounded-2xl p-6">
                <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-heart-pulse text-sm"></i>
                    </div>
                    Kelola Poliklinik
                </h2>
                <p class="text-slate-500 mb-6">Kelola data poliklinik, jadwal pemeriksaan, dan alur layanan di poliklinik kami.</p>
                <button class="btn btn-secondary bg-[#2d4499] border-none hover:bg-[#1e2d6b] text-white rounded-xl">
                    Kelola Poliklinik <i class="fas fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>
</x-layouts.app>