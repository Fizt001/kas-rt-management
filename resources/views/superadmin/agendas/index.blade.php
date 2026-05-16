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

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
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
                                <button @click="openEditModal('{{ $a->id }}', '{{ addslashes($a->judul) }}', '{{ addslashes($a->deskripsi) }}', '{{ $a->tanggal }}', '{{ $a->waktu }}', '{{ addslashes($a->lokasi) }}', '{{ $a->status }}')" class="p-2 text-blue-600 bg-blue-50 rounded-lg border border-blue-100">
                                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-12 text-center text-slate-300 font-black uppercase text-[10px]">Data Kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="showModalReschedule" style="display: none;" class="fixed inset-0 z-[110] overflow-y-auto" x-cloak>
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

        @php $roleNormalized = strtolower(str_replace(' ', '', auth()->user()->role)); @endphp

        @if(in_array($roleNormalized, ['rt', 'superadmin']) && $expiredAgendas->count() > 0)
            <script>
                document.addEventListener('DOMContentLoaded', async () => {
                    const expired = @json($expiredAgendas);
                    
                    // Loop lewat Javascript agar muncul berurutan (Queue)
                    for (const agenda of expired) {
                        const { value: action } = await Swal.fire({
                            title: 'Agenda Terlewat!',
                            html: `Agenda <b>${agenda.judul}</b> sudah lewat jadwal.<br><small class="text-slate-400">Jadwal asli: ${agenda.tanggal}</small>`,
                            icon: 'warning',
                            showDenyButton: true,
                            showCancelButton: true,
                            confirmButtonText: 'Selesai',
                            denyButtonText: 'Tunda',
                            cancelButtonText: 'Abaikan',
                            confirmButtonColor: '#10b981',
                            denyButtonColor: '#f59e0b',
                        });

                        if (action) {
                            // Jika klik Selesai
                            document.getElementById(`form-selesai-${agenda.id}`).submit();
                            break; // Berhenti dulu karena halaman akan refresh
                        } else if (Swal.getDenyButton() === document.activeElement) {
                            // Jika klik Tunda
                            window.dispatchEvent(new CustomEvent('open-reschedule', { detail: { id: agenda.id } }));
                            break; // Buka modal tunda
                        }
                    }
                });
            </script>

            @foreach($expiredAgendas as $expired)
                <form id="form-selesai-{{ $expired->id }}" action="{{ route('agendas.update-status', $expired->id) }}" method="POST" class="hidden">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="selesai">
                </form>
            @endforeach
        @endif
    </div>
</x-app-layout>