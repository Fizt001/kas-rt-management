<x-app-layout>
    {{-- Tambahkan CDN SweetAlert jika belum ada di app.blade.php --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                Manajemen <span class="text-blue-600">Agenda & Pengumuman</span>
            </h2>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider italic">Konfirmasi agenda yang telah terlewat</p>
        </div>
    </x-slot>

    <div x-data="{ 
        showModalCreate: false,
        showModalEdit: false,
        showModalReschedule: false,
        editFormAction: '',
        rescheduleFormAction: '',
        
        openEditModal(id, judul, deskripsi, tanggal, waktu, lokasi, status) {
            this.editFormAction = `/agendas/${id}`;
            document.getElementById('edit_judul').value = judul;
            document.getElementById('edit_deskripsi').value = deskripsi;
            document.getElementById('edit_tanggal').value = tanggal;
            document.getElementById('edit_waktu').value = waktu;
            document.getElementById('edit_lokasi').value = lokasi;
            document.getElementById('edit_status').value = status;
            this.showModalEdit = true;
        },

        openRescheduleModal(id) {
            this.rescheduleFormAction = `/agendas/${id}/reschedule`;
            this.showModalReschedule = true;
        }
    }" 
    @open-reschedule.window="openRescheduleModal($event.detail.id)"
    class="p-5 space-y-4 pb-10 relative">

        <div class="flex flex-wrap justify-between items-center bg-white dark:bg-slate-900 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 gap-4">
            <div class="relative flex-1 max-w-sm">
                <input type="text" placeholder="Cari agenda..." class="w-full bg-slate-100 dark:bg-slate-800 border-none rounded-lg py-2.5 pl-10 pr-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 transition-all">
                <svg class="absolute left-3 top-3 size-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </div>
            <button type="button" @click="showModalCreate = true" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-black text-xs uppercase tracking-widest hover:bg-blue-700 shadow-md active:scale-95 transition-all">
                + Tambah Agenda
            </button>
        </div>

        <!-- Desktop View: Table -->
        <div class="hidden lg:block bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-left table-fixed">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-[10px] font-black uppercase tracking-widest text-slate-400">
                        <th class="px-6 py-4 w-1/3">Detail Kegiatan</th>
                        <th class="px-6 py-4 w-1/4">Jadwal & Lokasi</th>
                        <th class="px-6 py-4 w-1/6 text-center">Status</th>
                        <th class="px-6 py-4 w-1/6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($agendas as $a)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all group">
                        <td class="px-6 py-3.5">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-800 dark:text-slate-200 tracking-tight truncate uppercase">{{ $a->judul }}</span>
                                <span class="text-[10px] font-bold text-slate-400 truncate mt-0.5">{{ $a->deskripsi }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 text-xs font-bold text-blue-600">
                            {{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-3.5 text-center">
                            <span class="px-3 py-1 rounded-md text-[9px] font-black uppercase border {{ $a->status == 'aktif' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                {{ $a->status }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                <button @click="openEditModal('{{ $a->id }}', '{{ addslashes($a->judul) }}', '{{ addslashes($a->deskripsi) }}', '{{ $a->tanggal }}', '{{ $a->waktu }}', '{{ addslashes($a->lokasi) }}', '{{ $a->status }}')" class="p-2 text-blue-600 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100">
                                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                                </button>
                                <form id="delete-form-{{ $a->id }}" action="{{ route('agendas.destroy', $a->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDeleteAgenda('{{ $a->id }}')" class="p-2 text-rose-600 bg-rose-50 dark:bg-rose-900/20 rounded-lg border border-rose-100 hover:bg-rose-100 transition-colors" title="Hapus">
                                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M14.74 9l-.34 12m-4.78 0-.34-12m10.32-4.74l-.38 3.42a2 2 0 0 1-1.99 1.74H6.42a2 2 0 0 1-1.99-1.74l-.38-3.42"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-12 text-center text-slate-300 font-black uppercase text-[10px]">Data Kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View: Expandable Cards -->
        <div class="block lg:hidden space-y-3">
            @forelse($agendas as $a)
            <div x-data="{ expanded: false }" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm transition-all duration-300">
                <!-- Header (Minimal Info) -->
                <div @click="expanded = !expanded" class="p-4 flex items-center justify-between cursor-pointer active:bg-slate-50 dark:active:bg-slate-800/50">
                    <div class="flex-1 min-w-0 pr-4">
                        <h3 class="text-xs font-black text-slate-800 dark:text-slate-200 truncate uppercase">{{ $a->judul }}</h3>
                        <p class="text-[10px] font-bold text-slate-400 mt-1">{{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase border {{ $a->status == 'aktif' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                            {{ $a->status }}
                        </span>
                        <svg class="size-4 text-slate-400 transform transition-transform duration-300" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <!-- Expanded Detail & Actions -->
                <div x-show="expanded" x-collapse x-cloak>
                    <div class="p-4 pt-0 border-t border-slate-100 dark:border-slate-800 mt-2">
                        <div class="space-y-3 mt-3">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Deskripsi</p>
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $a->deskripsi ?: '-' }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Waktu</p>
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $a->waktu ?: '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</p>
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $a->lokasi ?: '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex gap-2 mt-5">
                            <button @click="openEditModal('{{ $a->id }}', '{{ addslashes($a->judul) }}', '{{ addslashes($a->deskripsi) }}', '{{ $a->tanggal }}', '{{ $a->waktu }}', '{{ addslashes($a->lokasi) }}', '{{ $a->status }}')" class="flex-1 flex justify-center items-center gap-1.5 p-2.5 text-blue-600 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 text-[10px] font-black uppercase tracking-widest hover:bg-blue-100 transition-colors">
                                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                                Edit
                            </button>
                            <form id="delete-form-mobile-{{ $a->id }}" action="{{ route('agendas.destroy', $a->id) }}" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDeleteAgendaMob('{{ $a->id }}')" class="w-full flex justify-center items-center gap-1.5 p-2.5 text-rose-600 bg-rose-50 dark:bg-rose-900/20 rounded-lg border border-rose-100 text-[10px] font-black uppercase tracking-widest hover:bg-rose-100 transition-colors">
                                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M14.74 9l-.34 12m-4.78 0-.34-12m10.32-4.74l-.38 3.42a2 2 0 0 1-1.99 1.74H6.42a2 2 0 0 1-1.99-1.74l-.38-3.42"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl">
                <p class="text-[10px] font-black uppercase text-slate-400">Data Kosong</p>
            </div>
            @endforelse
        </div>

        <!-- Modal Create -->
        <div x-show="showModalCreate" @keydown.window.escape="showModalCreate = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalCreate = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModalCreate" x-transition class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl rounded-3xl w-full max-w-lg p-6 lg:p-8">
                    <h3 class="text-xl font-black text-slate-800 dark:text-white mb-6 uppercase tracking-tight">Tambah <span class="text-blue-600">Agenda</span></h3>
                    <form action="{{ route('agendas.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="status" value="aktif">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Judul Agenda</label>
                            <input type="text" name="judul" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Deskripsi Singkat</label>
                            <textarea name="deskripsi" rows="3" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-blue-600 resize-none dark:text-white"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Tanggal</label>
                                <input type="date" name="tanggal" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Waktu (Jam)</label>
                                <input type="time" name="waktu" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Lokasi / Tempat</label>
                            <input type="text" name="lokasi" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                        </div>
                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="showModalCreate = false" class="flex-1 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">Batal</button>
                            <button type="submit" class="flex-[2] py-3.5 bg-blue-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-200 dark:shadow-none hover:bg-blue-700 active:scale-95 transition-all">Simpan Agenda</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div x-show="showModalEdit" @keydown.window.escape="showModalEdit = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalEdit = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModalEdit" x-transition class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl rounded-3xl w-full max-w-lg p-6 lg:p-8">
                    <h3 class="text-xl font-black text-slate-800 dark:text-white mb-6 uppercase tracking-tight">Edit <span class="text-blue-600">Agenda</span></h3>
                    <form :action="editFormAction" method="POST" class="space-y-5">
                        @csrf @method('PUT')
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Judul Agenda</label>
                            <input type="text" name="judul" id="edit_judul" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Deskripsi Singkat</label>
                            <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-blue-600 resize-none dark:text-white"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Tanggal</label>
                                <input type="date" name="tanggal" id="edit_tanggal" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Waktu (Jam)</label>
                                <input type="time" name="waktu" id="edit_waktu" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Lokasi / Tempat</label>
                                <input type="text" name="lokasi" id="edit_lokasi" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Status</label>
                                <select name="status" id="edit_status" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                                    <option value="aktif">Aktif</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="batal">Batal</option>
                                </select>
                            </div>
                        </div>
                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="showModalEdit = false" class="flex-1 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">Batal</button>
                            <button type="submit" class="flex-[2] py-3.5 bg-blue-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-200 dark:shadow-none hover:bg-blue-700 active:scale-95 transition-all">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showModalReschedule" @keydown.window.escape="showModalReschedule = false" style="display: none;" class="fixed inset-0 z-[110] overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalReschedule = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModalReschedule" class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl rounded-2xl w-full max-w-sm p-6 text-left">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white mb-4 italic uppercase">Tunda <span class="text-blue-600">Jadwal</span></h3>
                    <form :action="rescheduleFormAction" method="POST" class="space-y-4">
                        @csrf @method('PATCH')
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Tanggal Baru</label>
                            <input type="date" name="tanggal_baru" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 rounded-lg py-2.5 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600">
                        </div>
                        <div class="pt-2 flex gap-2">
                            <button type="button" @click="showModalReschedule = false" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl font-black text-[9px] uppercase tracking-widest">Batal</button>
                            <button type="submit" class="flex-[2] py-3 bg-blue-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-lg shadow-blue-200">Simpan Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>

    @push('scripts')
        <script>
            function confirmDeleteAgenda(id) {
                Swal.fire({
                    title: 'Hapus Agenda?',
                    text: 'Agenda ini akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                })
            }

            function confirmDeleteAgendaMob(id) {
                Swal.fire({
                    title: 'Hapus Agenda?',
                    text: 'Agenda ini akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-mobile-' + id).submit();
                    }
                })
            }
        </script>
    @endpush
</x-app-layout>