<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Statistik Warga 
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic">Demografi dan Data Perumahan RT</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-[90rem] mx-auto space-y-4 md:space-y-6">

        <!-- Top Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-2 gap-3 sm:gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-[1.25rem] sm:rounded-2xl p-4 sm:p-5 shadow-lg sm:shadow-xl shadow-blue-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-bottom-4 opacity-10 size-16 sm:w-24 sm:h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                <p class="text-[8px] sm:text-[10px] font-black text-white/80 uppercase tracking-widest relative z-10 line-clamp-1">Total Individu</p>
                <p class="text-xl sm:text-3xl font-black text-white mt-1 relative z-10">{{ $totalWargaKeseluruhan }} <span class="text-[9px] sm:text-sm font-bold opacity-60 uppercase">Jiwa</span></p>
            </div>
            
            <div class="bg-gradient-to-br from-emerald-400 to-teal-500 rounded-[1.25rem] sm:rounded-2xl p-4 sm:p-5 shadow-lg sm:shadow-xl shadow-emerald-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-bottom-4 opacity-10 size-16 sm:w-24 sm:h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                <p class="text-[8px] sm:text-[10px] font-black text-white/80 uppercase tracking-widest relative z-10 line-clamp-1">Total KK</p>
                <p class="text-xl sm:text-3xl font-black text-white mt-1 relative z-10">{{ $totalKK }} <span class="text-[9px] sm:text-sm font-bold opacity-60 uppercase">KK</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            
            <!-- Daftar Rumah -->
            <div x-data="{
                    search: '',
                    page: 1,
                    perPage: 10,
                    rows: @js($rumahStats),
                    get filteredRows() {
                        if (this.search === '') return this.rows;
                        const query = this.search.toLowerCase();
                        return this.rows.filter(row => 
                            row.no_rumah.toLowerCase().includes(query) || 
                            row.nama_akun.toLowerCase().includes(query)
                        );
                    },
                    get paginatedRows() {
                        let start = (this.page - 1) * this.perPage;
                        let end = start + this.perPage;
                        return this.filteredRows.slice(start, end);
                    },
                    get totalPages() {
                        if(this.filteredRows.length === 0) return 1;
                        return Math.ceil(this.filteredRows.length / this.perPage);
                    },
                    nextPage() {
                        if (this.page < this.totalPages) this.page++;
                    },
                    prevPage() {
                        if (this.page > 1) this.page--;
                    }
                }" 
                class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-[1.5rem] sm:rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col">
                
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-5 bg-blue-600 rounded-full"></div>
                        <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Data Warga Per Rumah</h3>
                    </div>
                    <div class="relative w-full sm:w-64">
                        <input x-model="search" @input="page = 1" type="text" placeholder="Cari nama atau no rumah..." class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg py-2 pl-9 pr-3 text-[10px] sm:text-xs font-bold focus:ring-2 focus:ring-blue-600 transition-all">
                        <svg class="absolute left-3 top-2.5 size-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>
                </div>
                
                <div class="overflow-x-auto custom-scrollbar flex-1">
                    <table class="w-full text-left border-collapse min-w-[300px]">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-[8px] sm:text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 border-y border-slate-100 dark:border-slate-800">
                                <th class="px-3 py-2">Blok/No</th>
                                <th class="px-3 py-2">Nama Akun</th>
                                <th class="px-3 py-2 text-center">KK</th>
                                <th class="px-3 py-2 text-center">Penghuni</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                            <template x-for="(rumah, index) in paginatedRows" :key="index">
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-3 py-2">
                                        <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2 py-0.5 rounded-md text-[9px] sm:text-[11px] font-black uppercase border border-slate-200 dark:border-slate-700" x-text="rumah.no_rumah"></span>
                                    </td>
                                    <td class="px-3 py-2">
                                        <p class="text-[9px] sm:text-xs font-bold text-slate-800 dark:text-slate-200 uppercase truncate max-w-[100px] sm:max-w-xs" x-text="rumah.nama_akun"></p>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="text-[10px] sm:text-xs font-black text-slate-600 dark:text-slate-400" x-text="rumah.jumlah_kk"></span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="text-[10px] sm:text-xs font-black text-blue-600 dark:text-blue-400" x-text="rumah.jumlah_penghuni"></span>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="filteredRows.length === 0" x-cloak>
                                <td colspan="4" class="px-3 py-8 text-center text-[9px] sm:text-xs font-black text-slate-400 uppercase tracking-widest bg-slate-50/50 dark:bg-slate-800/20">
                                    Pencarian tidak ditemukan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 gap-3">
                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-500 order-2 sm:order-1">
                        Menampilkan <span class="text-blue-600" x-text="filteredRows.length > 0 ? ((page - 1) * perPage) + 1 : 0"></span> - <span class="text-blue-600" x-text="Math.min(page * perPage, filteredRows.length)"></span> dari <span class="text-slate-800 dark:text-white" x-text="filteredRows.length"></span> warga
                    </p>
                    <div class="flex items-center gap-2 order-1 sm:order-2">
                        <select x-model.number="perPage" @change="page = 1" class="text-[9px] sm:text-[10px] font-bold bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg py-1 px-2 focus:ring-2 focus:ring-blue-600 cursor-pointer">
                            <option value="10">10 Baris</option>
                            <option value="15">15 Baris</option>
                            <option value="25">25 Baris</option>
                        </select>
                        <div class="flex items-center gap-1">
                            <button @click="prevPage()" :disabled="page === 1" class="p-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button @click="nextPage()" :disabled="page >= totalPages" class="p-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Umur -->
            <div class="bg-white dark:bg-slate-900 rounded-[1.5rem] sm:rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-100 dark:border-slate-800 h-full flex flex-col">
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
