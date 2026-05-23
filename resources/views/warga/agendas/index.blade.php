<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col -mb-1">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                Kegiatan & <span class="text-blue-600">Pengumuman RT</span>
            </h2>
            <p class="text-[10px] text-slate-500 font-bold tracking-[0.2em] uppercase mt-0.5">Informasi terbaru seputar lingkungan warga</p>
        </div>
    </x-slot>

    <div class="px-3 sm:px-5 pb-10 max-w-7xl mx-auto space-y-6">
        
        {{-- BAGIAN AGENDA AKTIF / AKAN DATANG --}}
        <div>
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1.5 h-5 bg-blue-600 rounded-full"></div>
                <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Jadwal Mendatang</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($agendasAktif as $agenda)
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-3">
                        <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-wider">
                            {{ \Carbon\Carbon::parse($agenda->tanggal)->translatedFormat('d M Y') }}
                        </span>
                        @if($agenda->waktu)
                        <span class="text-[10px] font-bold text-slate-400">
                            🕒 {{ $agenda->waktu }}
                        </span>
                        @endif
                    </div>
                    <h4 class="text-base font-black text-slate-800 dark:text-slate-200 mb-1 leading-tight">{{ $agenda->judul }}</h4>
                    <p class="text-xs font-medium text-slate-500 mb-4 line-clamp-3">{{ $agenda->deskripsi }}</p>
                    
                    <div class="mt-auto pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2">
                        <svg class="size-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-[11px] font-bold text-slate-600 dark:text-slate-400 truncate">{{ $agenda->lokasi ?? 'Lokasi Menyusul' }}</span>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-8 border border-dashed border-slate-200 dark:border-slate-800 text-center">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum Ada Agenda Mendatang</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- BAGIAN RIWAYAT KEGIATAN --}}
        @if($agendasSelesai->count() > 0)
        <div class="pt-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1.5 h-5 bg-slate-400 rounded-full"></div>
                <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Riwayat Kegiatan Selesai</h3>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 border-b border-slate-100 dark:border-slate-800">
                                <th class="px-4 py-3 whitespace-nowrap">Nama Kegiatan</th>
                                <th class="px-4 py-3 whitespace-nowrap">Tanggal & Lokasi</th>
                                <th class="px-4 py-3 whitespace-nowrap text-right">Dana Terpakai</th>
                                <th class="px-4 py-3 text-center whitespace-nowrap">Laporan Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                            @foreach($agendasSelesai as $selesai)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3">
                                    <p class="text-xs font-black text-slate-800 dark:text-slate-200">{{ $selesai->judul }}</p>
                                    <p class="text-[10px] font-medium text-slate-500 line-clamp-1 mt-0.5">{{ $selesai->deskripsi }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ \Carbon\Carbon::parse($selesai->tanggal)->translatedFormat('d M Y') }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">{{ $selesai->lokasi ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if($selesai->status === 'batal')
                                        <span class="text-[9px] font-black text-rose-600 bg-rose-100 px-2 py-1 rounded-md uppercase border border-rose-200">Batal</span>
                                    @elseif($selesai->realisasi_dana)
                                        <span class="text-xs font-black text-rose-500">- Rp{{ number_format($selesai->realisasi_dana, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-400 italic">Rp 0</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($selesai->status === 'batal')
                                            <span class="text-[8px] font-bold text-rose-400 italic border-b border-rose-200">Tidak ada laporan</span>
                                        @elseif($selesai->bukti_kegiatan)
                                            <a href="{{ asset('storage/' . $selesai->bukti_kegiatan) }}" target="_blank" class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md text-[8px] font-black uppercase border border-blue-100 hover:bg-blue-100" title="Foto Kegiatan">📸 Foto</a>
                                        @endif
                                        @if($selesai->status !== 'batal' && $selesai->nota_belanja)
                                            <a href="{{ asset('storage/' . $selesai->nota_belanja) }}" target="_blank" class="bg-amber-50 text-amber-600 px-2.5 py-1 rounded-md text-[8px] font-black uppercase border border-amber-100 hover:bg-amber-100" title="Nota Belanja">🧾 Nota</a>
                                        @endif
                                        @if($selesai->status !== 'batal' && !$selesai->bukti_kegiatan && !$selesai->nota_belanja)
                                            <span class="text-[8px] font-bold text-slate-400 italic">Belum Ada</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
