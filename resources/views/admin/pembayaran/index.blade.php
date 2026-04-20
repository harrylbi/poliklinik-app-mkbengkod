<x-layouts.app title="Verifikasi Pembayaran">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Verifikasi Pembayaran</h2>
            <p class="text-slate-500 text-sm mt-1">Konfirmasi tagihan yang telah diunggah oleh pasien</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success shadow-sm rounded-xl mb-4 text-white">
        <i class="fas fa-check-circle text-xl mr-2"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error shadow-sm rounded-xl mb-4 text-white">
        <i class="fas fa-times-circle text-xl mr-2"></i>
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
                            <th class="px-6 py-4">Nama Pasien</th>
                            <th class="px-6 py-4">Dokter & Poli</th>
                            <th class="px-6 py-4">Tgl. Periksa</th>
                            <th class="px-6 py-4">Total Tagihan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($tagihans as $tagihan)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-semibold text-slate-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $tagihan->daftarPoli->pasien->nama ?? '-' }}
                                <div class="text-xs text-slate-500 font-normal">RM: {{ $tagihan->daftarPoli->pasien->no_rm ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $tagihan->daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}
                                <div class="text-xs text-primary font-bold">Poli {{ $tagihan->daftarPoli->poli->nama_poli ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-600">
                                {{ \Carbon\Carbon::parse($tagihan->tgl_periksa)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-primary">
                                Rp {{ number_format($tagihan->biaya_periksa, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($tagihan->status_pembayaran == 'menunggu_verifikasi')
                                    <span class="badge bg-amber-100 text-amber-700 border-amber-200 font-bold px-2 py-1">Menunggu</span>
                                @elseif($tagihan->status_pembayaran == 'lunas')
                                    <span class="badge bg-green-100 text-green-700 border-green-200 font-bold px-2 py-1"><i class="fas fa-check mr-1"></i> Lunas</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button class="btn btn-sm btn-outline btn-primary" onclick="document.getElementById('modal_bukti_{{ $tagihan->id }}').showModal()">
                                        <i class="fas fa-eye"></i> Lihat Bukti
                                    </button>
                                </div>

                                {{-- Modal Bukti --}}
                                <dialog id="modal_bukti_{{ $tagihan->id }}" class="modal text-left">
                                    <div class="modal-box">
                                        <h3 class="font-bold text-lg mb-4">Bukti Pembayaran</h3>
                                        
                                        <div class="bg-slate-100 rounded-xl p-2 mb-4 text-center">
                                            @if($tagihan->bukti_pembayaran)
                                                <img src="{{ asset('storage/' . $tagihan->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="max-w-full h-auto rounded-lg mx-auto max-h-96 object-contain">
                                            @else
                                                <span class="text-slate-400"><i class="fas fa-image text-3xl mb-2 block"></i> Tidak ada gambar</span>
                                            @endif
                                        </div>

                                        <div class="modal-action flex justify-between items-center">
                                            <form method="dialog">
                                                <button class="btn">Tutup</button>
                                            </form>
                                            
                                            @if($tagihan->status_pembayaran == 'menunggu_verifikasi')
                                            <form action="{{ route('admin.pembayaran.verifikasi', $tagihan->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success text-white" onclick="return confirm('Apakah Anda yakin tagihan ini sudah lunas?')">
                                                    <i class="fas fa-check-circle mr-1"></i> Konfirmasi Lunas
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                    <form method="dialog" class="modal-backdrop">
                                        <button>close</button>
                                    </form>
                                </dialog>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <i class="fas fa-check-double text-3xl mb-3 block"></i>
                                Belum ada tagihan yang perlu diverifikasi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>
