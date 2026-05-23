<x-app-layout>
    <!-- Slot Header: Dikirim ke Navbar (Sticky) -->
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Master <span class="text-blue-600">Iuran</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic">Kelola jenis penagihan tetap warga</p>
        </div>
    </x-slot>

    <!-- Kontainer Utama dengan Alpine.js untuk kontrol modal -->
    <div x-data="{ 
            modalAdd: false, 
            modalEdit: false, 
            editData: { id: '', nama_iuran: '', nominal: '', deskripsi: '' } 
        }" 
        class="min-h-screen pb-10">
        
        <!-- View Konten (mt-2 agar naik ke atas) -->
        <div class="px-4 sm:px-6 lg:px-8 mt-2 space-y-4">
            
            <!-- Area Kontrol: Generate & Tambah -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-stretch">
                
                <!-- Card Generate (Sisi Kiri/Besar) -->
                <div class="lg:col-span-9 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 rounded-xl">
                            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                        </div>
                        <h3 class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-widest">Generate Tagihan Bulanan</h3>
                    </div>

                    <form action="{{ route('iuran.generate') }}" method="POST" class="flex flex-wrap items-end gap-3">
                        @csrf
                        <div class="flex-1 min-w-[140px]">
                            <label class="text-[10px] font-black text-slate-400 mb-1.5 block ml-1 uppercase">Bulan Tagihan</label>
                            <select name="bulan" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-2.5 px-4 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $m == date('m') ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 min-w-[110px]">
                            <label class="text-[10px] font-black text-slate-400 mb-1.5 block ml-1 uppercase">Tahun</label>
                            <select name="tahun" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-2.5 px-4 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all">
                                @foreach(range(date('Y')-1, date('Y')+1) as $y)
                                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-emerald-100 dark:shadow-none transition-all active:scale-95 flex items-center gap-2">
                            <span>Generate</span>
                        </button>
                    </form>
                </div>

                <!-- Card Tombol Tambah (Sisi Kanan/Kecil) -->
                <div class="lg:col-span-3">
                    <button @click="modalAdd = true" class="w-full h-full min-h-[100px] bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 text-white shadow-lg shadow-blue-200 dark:shadow-none transition-all hover:-translate-y-1 group">
                        <div class="p-3 bg-white/20 rounded-xl group-hover:scale-110 transition-transform">
                            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                        </div>
                        <span class="text-sm font-black tracking-tight uppercase">Tambah Iuran</span>
                    </button>
                </div>
            </div>

            <!-- Tabel Data Iuran -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b dark:border-slate-800">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Iuran</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                                <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($masters as $master)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-700 dark:text-slate-200">{{ $master->nama_iuran }}</span>
                                        <span class="text-xs text-slate-400 italic line-clamp-1">{{ $master->deskripsi ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-black text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400 px-3 py-1 rounded-lg">
                                        {{ $master->formatted_nominal }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <!-- Tombol Edit -->
                                        <button @click="editData = { id: '{{ $master->id }}', nama_iuran: '{{ $master->nama_iuran }}', nominal: '{{ $master->nominal }}', deskripsi: '{{ $master->deskripsi }}' }; modalEdit = true" 
                                            class="p-2 text-blue-600 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100">
                                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                                        </button>
                                        <!-- Tombol Hapus -->
                                        <button type="button" onclick="confirmDelete('{{ $master->id }}')" 
                                            class="p-2 text-rose-600 bg-rose-50 dark:bg-rose-900/20 rounded-lg border border-rose-100">
                                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m14.74 9-.34 12m-4.78 0-.34-12m10.32-4.74l-.38 3.42a2 2 0 0 1-1.99 1.74H6.42a2 2 0 0 1-1.99-1.74l-.38-3.42"/></svg>
                                        </button>
                                        <form action="{{ route('iuran.master.destroy', $master->id) }}" method="POST" id="delete-form-{{ $master->id }}" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400 text-sm italic">Data masih kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH (GABUNG) -->
        <div x-show="modalAdd" @keydown.window.escape="modalAdd = false" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="modalAdd = false"></div>
            <div class="relative bg-white dark:bg-slate-900 shadow-2xl rounded-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-200">
                <div class="p-6">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-blue-600 rounded-full"></span> Tambah Iuran Baru
                    </h3>
                    <form action="{{ route('iuran.master.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-xs font-black text-slate-400 uppercase ml-1 block mb-1">Nama Iuran</label>
                            <input type="text" name="nama_iuran" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Contoh: Iuran Kebersihan" required>
                        </div>
                        <div>
                            <label class="text-xs font-black text-slate-400 uppercase ml-1 block mb-1">Nominal (Rp)</label>
                            <input type="number" name="nominal" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-500" placeholder="50000" required>
                        </div>
                        <div>
                            <label class="text-xs font-black text-slate-400 uppercase ml-1 block mb-1">Deskripsi / Keterangan</label>
                            <textarea name="deskripsi" rows="2" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="modalAdd = false" class="flex-1 py-3 text-sm font-bold text-slate-400">Batal</button>
                            <button type="submit" class="flex-[2] py-3 bg-blue-600 text-white rounded-xl font-bold text-sm shadow-md transition-all">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT (GABUNG) -->
        <div x-show="modalEdit" @keydown.window.escape="modalEdit = false" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="modalEdit = false"></div>
            <div class="relative bg-white dark:bg-slate-900 shadow-2xl rounded-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-200">
                <div class="p-6">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-indigo-600 rounded-full"></span> Edit Jenis Iuran
                    </h3>
                    <form :action="'/iuran/master/' + editData.id" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="text-xs font-black text-slate-400 uppercase ml-1 block mb-1">Nama Iuran</label>
                            <input type="text" name="nama_iuran" x-model="editData.nama_iuran" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="text-xs font-black text-slate-400 uppercase ml-1 block mb-1">Nominal (Rp)</label>
                            <input type="number" name="nominal" x-model="editData.nominal" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="text-xs font-black text-slate-400 uppercase ml-1 block mb-1">Deskripsi / Keterangan</label>
                            <textarea name="deskripsi" x-model="editData.deskripsi" rows="2" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="modalEdit = false" class="flex-1 py-3 text-sm font-bold text-slate-400">Batal</button>
                            <button type="submit" class="flex-[2] py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-md transition-all">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Iuran?',
                text: "Data tagihan yang sudah terbit di warga tidak akan hilang.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => { 
                if (result.isConfirmed) {
                    const form = document.getElementById(`delete-form-${id}`);
                    if (form) form.submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>