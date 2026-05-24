<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Manajemen <span class="text-blue-600">Aktor Sistem</span></h2>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider italic">Total: {{ $users->total() }} User Terdaftar</p>
        </div>
    </x-slot>

    <div x-data="{ 
        search: '{{ request('search') }}',
        showModalCreate: false,
        showModalEdit: false,
        editFormAction: '',
        
        openEditModal(id, name, email, role) {
            this.editFormAction = `/users/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            this.showModalEdit = true;
        },

        confirmDelete(id) {
            Swal.fire({
                title: 'Hapus User Permanen?',
                text: 'Semua data yang terkait dengan user ini akan dihapus secara permanen!',
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
            });
        }
    }" class="p-5 space-y-4 pb-10 relative">

        <div class="flex flex-wrap justify-between items-center bg-white dark:bg-slate-900 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 gap-4">
            <form action="{{ route('users.index') }}" method="GET" class="relative flex-1 max-w-sm">
                <input type="text" name="search" x-model="search" placeholder="Cari nama warga..." 
                    class="w-full bg-slate-100 dark:bg-slate-800 border-none rounded-lg py-2.5 pl-10 pr-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 transition-all">
                <svg class="absolute left-3 top-3 size-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </form>

            <div class="flex items-center gap-2">
                <a href="{{ route('users.template') }}" class="py-2.5 px-4 bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase rounded-lg hover:bg-slate-200 transition-all dark:text-slate-400 border border-slate-200 dark:border-slate-700">Template</a>
                
                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" class="inline">
                    @csrf
                    <input type="file" name="file_excel" class="hidden" id="import_file" onchange="this.form.submit()">
                    <label for="import_file" class="cursor-pointer py-2.5 px-4 bg-blue-50 text-blue-600 text-[10px] font-black uppercase rounded-lg hover:bg-blue-100 transition-all border border-blue-100">Import</label>
                </form>

                <button type="button" @click="showModalCreate = true" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-black text-xs uppercase tracking-widest hover:bg-blue-700 shadow-md transition-all active:scale-95">
                    + Tambah User
                </button>
            </div>
        </div>

        {{-- BAGIAN TABEL --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl sm:rounded-3xl overflow-hidden shadow-sm flex flex-col">
            <div class="overflow-x-auto custom-scrollbar flex-1">
                <table class="w-full text-left border-collapse min-w-[350px]">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-y border-slate-100 dark:border-slate-800 text-[8px] sm:text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">
                            <th class="px-3 py-2">Nama Aktor</th>
                            <th class="px-3 py-2 text-center">Level Akses</th>
                            <th class="px-3 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        @forelse($users as $u)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                                <td class="px-3 py-2">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] sm:text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight truncate max-w-[120px] sm:max-w-xs">{{ $u->name }}</span>
                                        <span class="text-[8px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-tighter truncate max-w-[120px] sm:max-w-xs">{{ $u->email }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    @php
                                        $badgeColor = match(strtolower($u->role)) {
                                            'superadmin' => 'bg-rose-50 text-rose-600 dark:bg-rose-900/20 dark:text-rose-400 border-rose-100',
                                            'rt' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400 border-indigo-100',
                                            'bendahara' => 'bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400 border-amber-100',
                                            'warga' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400 border-emerald-100',
                                            'mesjid' => 'bg-cyan-50 text-cyan-600 dark:bg-cyan-900/20 dark:text-cyan-400 border-cyan-100',
                                            'koperasi' => 'bg-purple-50 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400 border-purple-100',
                                            default => 'bg-slate-50 text-slate-600 border-slate-100',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-md text-[8px] sm:text-[10px] font-black uppercase tracking-tighter border {{ $badgeColor }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-right">
                                    @php
                                        $isRestricted = in_array(strtolower($u->role), ['superadmin', 'rt', 'bendahara']);
                                        $canManage = auth()->user()->role === 'superadmin' || !$isRestricted;
                                    @endphp
                                    @if($canManage)
                                        <div class="flex justify-end gap-1.5 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-all duration-200">
                                            <button type="button" @click="openEditModal('{{ $u->id }}', '{{ addslashes($u->name) }}', '{{ $u->email }}', '{{ $u->role }}')" 
                                                class="p-1.5 sm:p-2 text-blue-600 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 hover:bg-blue-100 transition-colors">
                                                <svg class="size-3.5 sm:size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                                            </button>
                                            <form id="delete-form-{{ $u->id }}" action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" @click="confirmDelete('{{ $u->id }}')" 
                                                    class="p-1.5 sm:p-2 text-rose-600 bg-rose-50 dark:bg-rose-900/20 rounded-lg border border-rose-100 hover:bg-rose-100 transition-colors">
                                                    <svg class="size-3.5 sm:size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M14.74 9l-.34 12m-4.78 0-.34-12m10.32-4.74l-.38 3.42a2 2 0 0 1-1.99 1.74H6.42a2 2 0 0 1-1.99-1.74l-.38-3.42"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="flex justify-end opacity-100 sm:opacity-0 group-hover:opacity-100 transition-all duration-200">
                                            <span class="text-[8px] font-bold text-slate-400 italic px-2 py-1">Protected</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="p-8 text-center text-[9px] sm:text-xs font-black text-slate-400 uppercase tracking-widest bg-slate-50/50 dark:bg-slate-800/20">Data Tidak Ditemukan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FIX: TAMBAHKAN TOMBOL HALAMAN (PAGINATION) DI SINI --}}
            <div class="px-6 py-4 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
                {{-- Kita paksa teksnya di sini tanpa lewat file lang --}}
                {{ $users->links('pagination::tailwind', [
                    'previous' => 'Sebelumnya', 
                    'next' => 'Berikutnya'
                ]) }}
            </div>

        </div>

        <div x-show="showModalCreate" @keydown.window.escape="showModalCreate = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showModalCreate = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0 relative">
                <div x-show="showModalCreate" class="relative bg-white border border-slate-200 shadow-2xl rounded-xl w-full max-w-md p-6 text-left transform transition-all dark:bg-slate-900 dark:border-slate-800">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight italic">Tambah <span class="text-blue-600">Aktor</span></h3>
                        <button type="button" @click="showModalCreate = false" class="text-slate-400 hover:text-slate-600"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <form action="{{ route('users.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="text" name="name" placeholder="Nama Lengkap" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg py-2.5 px-4 text-xs font-bold focus:ring-2 focus:ring-blue-600 transition-all dark:text-white">
                        
                        {{-- PEMBATASAN ROLE UNTUK PAK RT --}}
                        <select name="role" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg py-2.5 px-4 text-xs font-bold focus:ring-2 focus:ring-blue-600 transition-all dark:text-white">
                            <option value="warga">Warga</option>
                            <option value="rt">RT</option>
                            <option value="bendahara">Bendahara</option>
                            
                            {{-- Jika bukan RT (artinya Superadmin), tampilkan pilihan ini --}}
                            @if(auth()->user()->role !== 'rt')
                                <option value="mesjid">Mesjid</option>
                                <option value="koperasi">Koperasi</option>
                                <option value="superadmin">Superadmin</option>
                            @endif
                        </select>

                        <input type="email" name="email" placeholder="Email Login" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg py-2.5 px-4 text-xs font-bold focus:ring-2 focus:ring-blue-600 transition-all dark:text-white">
                        <div class="grid grid-cols-2 gap-3">
                            <input type="password" name="password" placeholder="Password" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg py-2.5 px-4 text-xs font-bold focus:ring-2 focus:ring-blue-600 transition-all dark:text-white">
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg py-2.5 px-4 text-xs font-bold focus:ring-2 focus:ring-blue-600 transition-all dark:text-white">
                        </div>
                        <div class="pt-3 flex gap-2">
                            <button type="button" @click="showModalCreate = false" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-lg font-black text-[9px] uppercase tracking-widest">Batal</button>
                            <button type="submit" class="flex-[2] py-3 bg-blue-600 text-white rounded-lg font-black text-[9px] uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showModalEdit" @keydown.window.escape="showModalEdit = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showModalEdit = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0 relative">
                <div x-show="showModalEdit" class="relative bg-white border border-slate-200 shadow-xl rounded-xl w-full max-w-md p-6 text-left transform transition-all dark:bg-slate-900 dark:border-slate-800">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight italic">Edit <span class="text-blue-600">Aktor</span></h3>
                        <button type="button" @click="showModalEdit = false" class="text-slate-400 hover:text-slate-600"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <form :action="editFormAction" method="POST" class="space-y-3">
                        @csrf @method('PUT')
                        <input type="text" name="name" id="edit_name" placeholder="Nama Lengkap" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg py-2.5 px-4 text-xs font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                        
                        {{-- PEMBATASAN ROLE UNTUK PAK RT --}}
                        <select name="role" id="edit_role" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg py-2.5 px-4 text-xs font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                            <option value="warga">Warga</option>
                            <option value="rt">RT</option>
                            <option value="bendahara">Bendahara</option>
                            
                            {{-- Jika bukan RT, tampilkan pilihan ini --}}
                            @if(auth()->user()->role !== 'rt')
                                <option value="mesjid">Mesjid</option>
                                <option value="koperasi">Koperasi</option>
                                <option value="superadmin">Superadmin</option>
                            @endif
                        </select>

                        <input type="email" name="email" id="edit_email" placeholder="Email Login" required class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg py-2.5 px-4 text-xs font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                        <input type="password" name="password" placeholder="Password Baru (Kosongkan jika tetap)" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg py-2.5 px-4 text-xs font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                        <div class="pt-3 flex gap-2">
                            <button type="button" @click="showModalEdit = false" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-lg font-black text-[9px] uppercase tracking-widest">Batal</button>
                            <button type="submit" class="flex-[2] py-3 bg-blue-600 text-white rounded-lg font-black text-[9px] uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    @endpush
</x-app-layout>