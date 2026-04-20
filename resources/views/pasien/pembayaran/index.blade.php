<x-layouts.app title="Pembayaran">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Tagihan Pembayaran</h2>
            <p class="text-slate-500 text-sm mt-1">Selesaikan administrasi dari layanan pemeriksaan Anda</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success shadow-sm rounded-xl mb-4 text-white">
        <i class="fas fa-check-circle text-xl mr-2"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="space-y-4">
        @forelse($tagihans as $tagihan)
        <div class="flex flex-col md:flex-row bg-white p-5 rounded-2xl border border-slate-200 shadow-sm gap-6 items-start md:items-center justify-between">
            
            <div class="flex gap-4 items-start">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Poli {{ $tagihan->daftarPoli->poli->nama_poli ?? '-' }}</h3>
                    <p class="text-slate-500 text-sm">Dokter: {{ $tagihan->daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</p>
                    <p class="text-slate-400 text-xs mt-1"><i class="far fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($tagihan->tgl_periksa)->format('d M Y') }}</p>
                </div>
            </div>

            <div class="mb-4 md:mb-0 md:text-right w-full md:w-auto">
                <p class="text-slate-500 text-sm">Total Tagihan:</p>
                <p class="text-2xl font-bold text-primary">Rp {{ number_format($tagihan->biaya_periksa, 0, ',', '.') }}</p>
                
                @if($tagihan->status_pembayaran == 'belum')
                    <span class="badge bg-red-100 text-red-700 border-red-200 font-bold px-2 py-1 mt-2">Belum Bayar</span>
                @elseif($tagihan->status_pembayaran == 'menunggu_verifikasi')
                    <span class="badge bg-amber-100 text-amber-700 border-amber-200 font-bold px-2 py-1 mt-2">Menunggu Verifikasi</span>
                @elseif($tagihan->status_pembayaran == 'lunas')
                    <span class="badge bg-green-100 text-green-700 border-green-200 font-bold px-2 py-1 mt-2"><i class="fas fa-check mr-1"></i> Lunas</span>
                @endif
            </div>

            <div class="w-full md:w-auto mt-4 md:mt-0 flex gap-2">
                @if($tagihan->status_pembayaran == 'belum')
                    <button class="btn btn-primary w-full md:w-auto disabled:opacity-50" onclick="document.getElementById('modal_upload_{{ $tagihan->id }}').showModal()">
                        <i class="fas fa-upload mr-1"></i> Upload Bukti
                    </button>
                @elseif($tagihan->status_pembayaran == 'menunggu_verifikasi' || $tagihan->status_pembayaran == 'lunas')
                    <a href="{{ asset('storage/' . $tagihan->bukti_pembayaran) }}" target="_blank" class="btn btn-outline btn-primary w-full md:w-auto">
                        <i class="fas fa-eye mr-1"></i> Lihat Bukti
                    </a>
                @endif
            </div>

        </div>

        {{-- Modal Upload --}}
        @if($tagihan->status_pembayaran == 'belum')
        <dialog id="modal_upload_{{ $tagihan->id }}" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-2">Upload Bukti Pembayaran</h3>
                <p class="text-sm text-slate-500 mb-4">Poli {{ $tagihan->daftarPoli->poli->nama_poli ?? '-' }} (Rp {{ number_format($tagihan->biaya_periksa, 0, ',', '.') }})</p>
                
                <form action="{{ route('pasien.pembayaran.store', $tagihan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-bold">Pilih File Foto (JPG/PNG)</span>
                        </label>
                        <input type="file" name="bukti_pembayaran" class="file-input file-input-bordered file-input-primary w-full" accept="image/jpeg, image/png, image/jpg" required />
                    </div>
                    <div class="modal-action">
                        <button type="button" class="btn" onclick="document.getElementById('modal_upload_{{ $tagihan->id }}').close()">Batal</button>
                        <button type="submit" class="btn btn-primary text-white">Upload Sekarang</button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
        @endif

        @empty
        <div class="text-center py-16 bg-white rounded-2xl border border-slate-200">
            <i class="fas fa-receipt text-4xl text-slate-300 mb-3 block"></i>
            <p class="text-slate-500">Belum ada tagihan pembayaran.</p>
        </div>
        @endforelse

    </div>

</x-layouts.app>
