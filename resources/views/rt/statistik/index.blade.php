<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Statistik Warga 
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic">Demografi dan Data Perumahan RT</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-5 max-w-7xl mx-auto space-y-4 md:space-y-6">

        <!-- Top Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden group">
                <svg class="absolute -right-4 -bottom-4 opacity-20 w-24 h-24 group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                <p class="text-[10px] font-black text-white/80 uppercase tracking-widest relative z-10">Total Individu / Warga</p>
                <p class="text-3xl font-black text-white mt-1 relative z-10">{{ $totalWargaKeseluruhan }} <span class="text-sm font-medium">Jiwa</span></p>
            </div>
            
            <div class="bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden group">
                <svg class="absolute -right-4 -bottom-4 opacity-20 w-24 h-24 group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                <p class="text-[10px] font-black text-white/80 uppercase tracking-widest relative z-10">Total Kartu Keluarga (KK)</p>
                <p class="text-3xl font-black text-white mt-1 relative z-10">{{ $totalKK }} <span class="text-sm font-medium">KK</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            
            <!-- Daftar Rumah -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-3xl p-5 shadow-sm border border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1.5 h-5 bg-blue-600 rounded-full"></div>
                    <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Data Warga Per Rumah</h3>
                </div>
                
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 border-b border-slate-100 dark:border-slate-800">
                                <th class="px-4 py-3">Blok / No Rumah</th>
                                <th class="px-4 py-3">Nama (Akun Utama)</th>
                                <th class="px-4 py-3 text-center">Jml KK</th>
                                <th class="px-4 py-3 text-center">Jml Penghuni</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                            @foreach($rumahStats as $rumah)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2 py-1 rounded-md text-xs font-black uppercase border border-slate-200 dark:border-slate-700">
                                        {{ $rumah['no_rumah'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase">{{ $rumah['nama_akun'] }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs font-black text-slate-600 dark:text-slate-400">{{ $rumah['jumlah_kk'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs font-black text-blue-600 dark:text-blue-400">{{ $rumah['jumlah_penghuni'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Statistik Umur -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-5 shadow-sm border border-slate-100 dark:border-slate-800 h-full flex flex-col">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1.5 h-5 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Demografi Umur</h3>
                </div>

                <div class="flex-1 space-y-4">
                    @php
                        $colors = [
                            'Balita (0-5)' => 'bg-pink-500',
                            'Anak-anak (6-12)' => 'bg-amber-400',
                            'Remaja (13-17)' => 'bg-blue-400',
                            'Dewasa (18-59)' => 'bg-emerald-500',
                            'Lansia (60+)' => 'bg-purple-500'
                        ];
                    @endphp

                    @foreach($umurStats as $kategori => $jumlah)
                    @php 
                        $persentase = $totalWargaKeseluruhan > 0 ? round(($jumlah / $totalWargaKeseluruhan) * 100) : 0;
                        $colorClass = $colors[$kategori] ?? 'bg-slate-500';
                    @endphp
                    <div>
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest">{{ $kategori }}</span>
                            <span class="text-xs font-black text-slate-800 dark:text-slate-200">{{ $jumlah }} <span class="text-[9px] font-medium text-slate-400">Jiwa</span></span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2">
                            <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $persentase }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <p class="text-[9px] text-slate-400 font-medium italic text-center">Statistik dihitung secara otomatis berdasarkan tanggal lahir yang diinput oleh warga.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
