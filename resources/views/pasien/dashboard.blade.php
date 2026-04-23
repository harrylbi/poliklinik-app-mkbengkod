<x-layouts.app title="Dashboard Pasien">
    <div class="space-y-6">
        {{-- Flash Messages --}}
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

        {{-- Welcome Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h1 class="text-2xl font-bold text-slate-800">Selamat Datang, {{ Auth::user()->nama }}! 👋</h1>
            <p class="text-slate-500 mt-1">Anda login sebagai Pasien. Silakan akses menu di sidebar untuk melakukan pendaftaran poli atau lihat antrian di bawah.</p>
        </div>

        {{-- Banner Antrian Aktif --}}
        @if($antrianAktif)
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-20">
                <i class="fas fa-notes-medical text-8xl"></i>
            </div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold mb-2">Antrian Aktif Anda</h2>
                    <ul class="space-y-1 text-blue-100">
                        <li><i class="fas fa-hospital-user w-5 text-center"></i> Poli: {{ $antrianAktif->jadwalPeriksa->dokter->poli->nama_poli }}</li>
                        <li><i class="fas fa-user-md w-5 text-center"></i> Dokter: {{ $antrianAktif->jadwalPeriksa->dokter->nama }}</li>
                        <li><i class="fas fa-clock w-5 text-center"></i> Jadwal: {{ $antrianAktif->jadwalPeriksa->hari }} ({{ \Carbon\Carbon::parse($antrianAktif->jadwalPeriksa->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($antrianAktif->jadwalPeriksa->jam_selesai)->format('H:i') }})</li>
                    </ul>
                </div>
                <div class="bg-white/20 backdrop-blur-md rounded-xl p-4 text-center min-w-[150px]">
                    <p class="text-sm font-medium text-blue-100 uppercase tracking-wider mb-1">No. Antrian Anda</p>
                    <p class="text-5xl font-black">{{ $antrianAktif->no_antrian }}</p>
                </div>
                <div class="bg-white text-indigo-600 rounded-xl p-4 text-center min-w-[150px] shadow-sm tracking-tight">
                    <p class="text-sm font-bold uppercase mb-1">Sedang Dilayani</p>
                    <p class="text-5xl font-black" id="active-queue-serving">{{ $antrianAktif->sedang_dilayani }}</p>
                </div>
            </div>
        </div>
        @else
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center shadow-sm">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-check-circle text-slate-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700">Tidak ada antrian aktif</h3>
            <p class="text-slate-500">Anda tidak memiliki jadwal pemeriksaan yang belum diselesaikan saat ini.</p>
        </div>
        @endif

        {{-- Tabel Daftar Jadwal --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="border-b border-slate-100 px-6 py-4 flex justify-between items-center bg-slate-50">
                <h2 class="font-bold text-slate-800"><i class="fas fa-calendar-alt mr-2 text-primary"></i> Jadwal Poliklinik & Dokter</h2>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="table table-zebra table-sm">
                    <thead class="bg-slate-100 text-slate-600 font-bold uppercase text-xs">
                        <tr>
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Poli</th>
                            <th class="py-3 px-4">Dokter</th>
                            <th class="py-3 px-4">Hari</th>
                            <th class="py-3 px-4">Jam Periksa</th>
                            <th class="py-3 px-4 text-center text-indigo-600"><i class="fas fa-headset mr-1"></i> Sedang Dilayani</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $index => $jadwal)
                        <tr class="hover">
                            <td class="font-medium px-4">{{ $index + 1 }}</td>
                            <td class="px-4"><span class="badge badge-accent badge-sm font-semibold">{{ $jadwal->dokter->poli->nama_poli ?? '-' }}</span></td>
                            <td class="px-4">{{ $jadwal->dokter->nama }}</td>
                            <td class="px-4 font-semibold">{{ $jadwal->hari }}</td>
                            <td class="px-4">
                                <i class="far fa-clock text-slate-400 mr-1"></i> 
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </td>
                            <td class="text-center px-4">
                                <div class="inline-flex items-center justify-center bg-indigo-50 text-indigo-700 font-bold text-lg rounded-lg py-1 px-3 min-w-[3rem] shadow-sm border border-indigo-100">
                                    <span id="serving-jadwal-{{ $jadwal->id }}">{{ $jadwal->sedang_dilayani }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-slate-400">Belum ada jadwal poliklinik yang tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Action Card --}}
        <div class="card bg-gradient-to-br from-[#1e2d6b] to-[#2d4499] text-white shadow-xl rounded-2xl mt-4">
            <div class="card-body">
                <h2 class="card-title text-xl">Daftar Poli Sekarang</h2>
                <p class="opacity-80">Ingin berkonsultasi dengan dokter? Klik tombol di bawah untuk mendaftar layanan poliklinik kami.</p>
                <div class="card-actions justify-end mt-4">
                    <a href="{{ url('/pasien/daftar') }}" class="btn btn-white text-[#1e2d6b] font-bold border-none hover:bg-slate-100 rounded-xl">
                        Daftar Poli <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            // Listen to WebSocket broadcasts from Reverb
            if (window.Echo) {
                window.Echo.channel('queue-updates')
                    .listen('.QueueUpdated', (e) => {
                        
                        console.log('Real-time queue update received:', e);

                        // If user is currently queuing for this specific schedule
                        @if($antrianAktif)
                            if (e.id_jadwal == {{ $antrianAktif->id_jadwal }}) {
                                const activeServingElem = document.getElementById('active-queue-serving');
                                if (activeServingElem) {
                                    activeServingElem.textContent = e.sedang_dilayani;
                                    
                                    // Add subtle flash animation
                                    activeServingElem.parentElement.classList.remove('animate-pulse');
                                    void activeServingElem.parentElement.offsetWidth; // trigger reflow
                                    activeServingElem.parentElement.classList.add('animate-pulse');
                                }
                            }
                        @endif
                        
                        // Update the schedule list row
                        const servingElem = document.getElementById('serving-jadwal-' + e.id_jadwal);
                        if (servingElem) {
                            servingElem.textContent = e.sedang_dilayani;
                            servingElem.parentElement.classList.remove('animate-pulse');
                            void servingElem.parentElement.offsetWidth;
                            servingElem.parentElement.classList.add('animate-pulse');
                        }
                    });
            } else {
                console.error('Laravel Echo is not initialized. Using fallback polling.');
                // Fallback polling if Echo fails
                setInterval(function() {
                    fetch('{{ route('pasien.dashboard.live_queue') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                    .then(r => r.json())
                    .then(data => {
                        if (data.antrian_aktif) {
                            const elem = document.getElementById('active-queue-serving');
                            if (elem) elem.textContent = data.antrian_aktif.sedang_dilayani;
                        }
                        data.jadwals.forEach(j => {
                            const elem = document.getElementById('serving-jadwal-' + j.id_jadwal);
                            if (elem) elem.textContent = j.sedang_dilayani;
                        });
                    }).catch(e => console.log(e));
                }, 10000); // 10 seconds fallback
            }
        });
    </script>
    @endpush
</x-layouts.app>