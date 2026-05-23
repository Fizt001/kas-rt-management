<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Data <span class="text-blue-600">Keluarga</span>
            </h2>
        </div>
    </x-slot>

    @php
        function maskNikKk($str) {
            if (!$str) return '-';
            $len = strlen($str);
            if ($len <= 8) return str_repeat('*', $len);
            return substr($str, 0, 4) . str_repeat('*', $len - 8) . substr($str, -4);
        }
    @endphp

    <div x-data="{ 
        showModalAdd: false,
        showModalEdit: false,
        editUrl: '',
        editNama: '',
        editStatus: '',
        editNik: '',
        editKelompokKk: '',
        editNoKk: '',
        isNewKkAdd: false,
        isNewKkEdit: false,
        showModalEditKepala: false,
        editKepalaNik: '',
        editKepalaNoKk: '',
        addStatus: 'Istri',
        editTanggalLahir: '',
        editKepalaTanggalLahir: ''
    }" class="p-4 sm:p-5 max-w-6xl mx-auto space-y-4">

        @if($isAdmin)
        <div class="flex items-center gap-3 bg-blue-50 dark:bg-slate-800/50 p-3 rounded-xl border border-blue-100 dark:border-slate-700">
            <span class="text-xs font-black text-blue-800 dark:text-blue-400 uppercase tracking-widest pl-2">Pilih Warga:</span>
            <form action="{{ route('warga.profile') }}" method="GET" class="flex-1 max-w-sm">
                <select name="warga_id" onchange="this.form.submit()" class="w-full bg-white dark:bg-slate-900 border border-blue-200 dark:border-slate-600 rounded-lg py-2 px-3 text-xs font-bold focus:ring-2 focus:ring-blue-600 cursor-pointer">
                    <option value="{{ auth()->id() }}">Data Keluarga Saya</option>
                    @foreach($daftarWarga as $w)
                        <option value="{{ $w->id }}" {{ $targetUser->id == $w->id ? 'selected' : '' }}>
                            {{ $w->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        @endif

        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl shadow-sm p-4 sm:p-5 relative overflow-hidden flex flex-col xl:flex-row gap-5 items-start xl:items-center justify-between">
            <svg class="absolute -right-4 -top-4 opacity-10 size-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            
            <div class="relative z-10 w-full xl:w-auto">
                <p class="text-[10px] font-black text-blue-200 uppercase tracking-widest mb-1">Kepala Keluarga</p>
                <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">{{ $targetUser->name }}</h2>
                <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 bg-white/20 border border-white/30 text-white rounded-full text-[10px] font-black uppercase tracking-widest backdrop-blur-sm shadow-sm">
                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Total Serumah: {{ $targetUser->familyMembers->count() + 1 }} Jiwa
                </div>
            </div>

            <form action="{{ route('warga.profile.update') }}" method="POST" class="relative z-10 flex flex-col lg:flex-row gap-3 w-full xl:w-auto items-end">
                @csrf
                <input type="hidden" name="target_user_id" value="{{ $targetUser->id }}">
                <input type="hidden" name="no_kk" value="{{ $targetUser->no_kk }}">
                <input type="hidden" name="nik" value="{{ $targetUser->nik }}">
                
                <div class="flex gap-2 w-full lg:w-auto">
                    <div class="w-full lg:w-32">
                        <label class="text-[10px] font-black text-blue-100 uppercase tracking-widest block mb-1">Blok</label>
                        <input type="text" name="blok_rumah" value="{{ $targetUser->blok_rumah }}" placeholder="Cth: E1" class="w-full bg-white/10 border border-white/20 text-white placeholder:text-white/40 rounded-lg py-2 px-3 text-xs sm:text-sm font-bold focus:ring-2 focus:ring-white h-10 uppercase">
                    </div>
                    <div class="w-full lg:w-32">
                        <label class="text-[10px] font-black text-blue-100 uppercase tracking-widest block mb-1">No. Rumah</label>
                        <input type="text" name="no_rumah" value="{{ $targetUser->no_rumah }}" placeholder="Cth: 01" class="w-full bg-white/10 border border-white/20 text-white placeholder:text-white/40 rounded-lg py-2 px-3 text-xs sm:text-sm font-bold focus:ring-2 focus:ring-white h-10 uppercase">
                    </div>
                </div>
                <div class="mt-2 lg:mt-0 w-full lg:w-auto">
                    <button type="submit" class="w-full px-6 py-2.5 bg-white text-blue-600 rounded-lg text-xs font-black uppercase tracking-widest hover:bg-slate-100 transition-all shadow-sm active:scale-95 h-10">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

        @php
            $groupedMembers = $targetUser->familyMembers->groupBy(function($item) {
                return $item->kelompok_kk ?: 'KK Utama';
            });
            // Ensure KK Utama always exists
            if (!$groupedMembers->has('KK Utama')) {
                $groupedMembers->put('KK Utama', collect());
            }
            
            // Move KK Utama to the front
            $utama = $groupedMembers->pull('KK Utama');
            $groupedMembers->prepend($utama, 'KK Utama');
        @endphp

        @foreach($groupedMembers as $kelompok => $members)
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 p-4 sm:p-5 mb-5">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-xs sm:text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest flex items-center gap-2">
                    <svg class="size-4 sm:size-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Anggota Keluarga: <span class="text-blue-600">{{ $kelompok }}</span>
                    <span class="ml-2 px-2.5 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 rounded-full text-[9px] font-black tracking-widest">
                        Total: {{ $kelompok === 'KK Utama' ? $members->count() + 1 : $members->count() }} Jiwa
                    </span>
                </h3>
                @if($loop->first)
                <button @click="showModalAdd = true" class="px-4 py-2 bg-slate-800 dark:bg-slate-700 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 transition-all flex items-center gap-1.5 shadow-md">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </button>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-[10px] font-black uppercase tracking-widest text-slate-500">
                            <th class="px-4 py-2.5 rounded-l-lg">Nama Lengkap</th>
                            <th class="px-4 py-2.5 text-center">Status</th>
                            <th class="px-4 py-2.5">NIK / No KK</th>
                            <th class="px-4 py-2.5 text-right rounded-r-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @if($kelompok === 'KK Utama')
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors bg-blue-50/20 dark:bg-blue-900/10">
                                <td class="px-4 py-3 text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">
                                    {{ $targetUser->name }}<br>
                                    <span class="text-[9px] text-slate-400 font-medium uppercase tracking-widest">{{ $targetUser->tanggal_lahir ? \Carbon\Carbon::parse($targetUser->tanggal_lahir)->format('d M Y') . ' (' . \Carbon\Carbon::parse($targetUser->tanggal_lahir)->age . ' Tahun)' : 'TGL LAHIR: -' }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 px-2 py-1 rounded text-[10px] font-black uppercase tracking-widest border border-blue-200 dark:border-blue-700">
                                        Kepala Keluarga
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs font-bold text-slate-500">
                                    NIK: {{ maskNikKk($targetUser->nik) }}<br>
                                    <span class="text-[9px] text-slate-400 font-medium">KK: {{ maskNikKk($targetUser->no_kk) }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-1.5">
                                        <button type="button" @click="editKepalaNik = '{{ $targetUser->nik }}'; editKepalaNoKk = '{{ $targetUser->no_kk }}'; editKepalaTanggalLahir = '{{ $targetUser->tanggal_lahir }}'; showModalEditKepala = true" class="text-blue-600 hover:text-blue-800 bg-blue-50 dark:bg-blue-900/30 p-2 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        
                        @forelse($members as $anggota)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3 text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">
                                    {{ $anggota->nama }}<br>
                                    <span class="text-[9px] text-slate-400 font-medium uppercase tracking-widest">{{ $anggota->tanggal_lahir ? \Carbon\Carbon::parse($anggota->tanggal_lahir)->format('d M Y') . ' (' . \Carbon\Carbon::parse($anggota->tanggal_lahir)->age . ' Tahun)' : 'TGL LAHIR: -' }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2 py-1 rounded text-[10px] font-black uppercase tracking-widest border border-blue-100 dark:border-blue-800">
                                        {{ $anggota->status_hubungan }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs font-bold text-slate-500">
                                    NIK: {{ maskNikKk($anggota->nik) }}<br>
                                    @if($kelompok !== 'KK Utama' && $anggota->status_hubungan === 'Kepala Keluarga')
                                    <span class="text-[9px] text-slate-400 font-medium">KK: {{ maskNikKk($anggota->no_kk_kelompok) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-1.5">
                                        <button type="button" @click="editUrl = '{{ route('warga.family.update', $anggota->id) }}'; editNama = '{{ addslashes($anggota->nama) }}'; editStatus = '{{ $anggota->status_hubungan }}'; editNik = '{{ $anggota->nik }}'; editKelompokKk = '{{ $anggota->kelompok_kk ?: 'KK Utama' }}'; editNoKk = '{{ $anggota->no_kk_kelompok }}'; editTanggalLahir = '{{ $anggota->tanggal_lahir }}'; showModalEdit = true" class="p-2 text-blue-600 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        
                                        <form id="delete-anggota-{{ $anggota->id }}" action="{{ route('warga.family.destroy', $anggota->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeleteAnggota('{{ $anggota->id }}')" class="p-2 text-rose-600 bg-rose-50 dark:bg-rose-900/20 rounded-lg border border-rose-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center">
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest italic">Belum ada data anggota keluarga di {{ $kelompok }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

        <div x-show="showModalAdd" @keydown.window.escape="showModalAdd = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalAdd = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModalAdd" x-transition class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md p-6 z-10 shadow-2xl">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-4 text-center italic">Tambah <span class="text-blue-600">Anggota</span></h3>
                    <form action="{{ route('warga.family.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="target_user_id" value="{{ $targetUser->id }}">
                        <div>
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Nama Lengkap</label>
                            <input type="text" name="nama" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Hubungan</label>
                                <select name="status_hubungan" x-model="addStatus" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                                    <option value="Istri">Istri</option>
                                    <option value="Suami">Suami</option>
                                    <option value="Anak">Anak</option>
                                    <option value="Orang Tua">Orang Tua</option>
                                    <option value="Mertua">Mertua</option>
                                    <option value="Kepala Keluarga">Kepala Keluarga Tambahan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">NIK KTP</label>
                                <input type="text" name="nik" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Masuk KK Mana?</label>
                                <select name="kelompok_kk_select" @change="isNewKkAdd = ($event.target.value === 'NEW')" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                                    @foreach($groupedMembers->keys() as $k)
                                        <option value="{{ $k }}" {{ $k === 'KK Utama' ? 'selected' : '' }}>{{ $k }}</option>
                                    @endforeach
                                    <option value="NEW" class="text-blue-600 font-black">+ Buat Grup KK Baru</option>
                                </select>
                                <input x-show="isNewKkAdd" type="text" name="kelompok_kk_new" placeholder="Ketik nama grup KK baru..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white mt-2 border-t-2 border-blue-200">
                            </div>
                            <div x-show="addStatus === 'Kepala Keluarga'">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">No. KK Tambahan</label>
                                <input type="text" name="no_kk_kelompok" placeholder="Isi khusus Kepala KK" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                            </div>
                        </div>
                        <div class="pt-3 flex gap-2">
                            <button type="button" @click="showModalAdd = false" class="w-1/3 py-3 bg-slate-100 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200">Batal</button>
                            <button type="submit" class="w-2/3 py-3 bg-blue-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 active:scale-95 transition-all">Simpan Baru</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showModalEdit" @keydown.window.escape="showModalEdit = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalEdit = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModalEdit" x-transition class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md p-6 z-10 shadow-2xl">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-4 text-center italic">Edit <span class="text-blue-600">Anggota</span></h3>
                    <form :action="editUrl" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Nama Lengkap</label>
                            <input type="text" name="nama" x-model="editNama" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Hubungan</label>
                                <select name="status_hubungan" x-model="editStatus" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                                    <option value="Istri">Istri</option>
                                    <option value="Suami">Suami</option>
                                    <option value="Anak">Anak</option>
                                    <option value="Orang Tua">Orang Tua</option>
                                    <option value="Mertua">Mertua</option>
                                    <option value="Kepala Keluarga">Kepala Keluarga Tambahan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">NIK KTP</label>
                                <input type="text" name="nik" x-model="editNik" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" x-model="editTanggalLahir" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Masuk KK Mana?</label>
                                <select name="kelompok_kk_select" x-model="editKelompokKk" @change="isNewKkEdit = ($event.target.value === 'NEW')" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                                    @foreach($groupedMembers->keys() as $k)
                                        <option value="{{ $k }}">{{ $k }}</option>
                                    @endforeach
                                    <option value="NEW" class="text-blue-600 font-black">+ Buat Grup KK Baru</option>
                                </select>
                                <input x-show="isNewKkEdit" type="text" name="kelompok_kk_new" placeholder="Ketik nama grup KK baru..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white mt-2 border-t-2 border-blue-200">
                            </div>
                            <div x-show="editStatus === 'Kepala Keluarga'">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">No. KK Tambahan</label>
                                <input type="text" name="no_kk_kelompok" x-model="editNoKk" placeholder="Isi khusus Kepala KK" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                            </div>
                        </div>
                        <div class="pt-3 flex gap-2">
                            <button type="button" @click="showModalEdit = false" class="w-1/3 py-3 bg-slate-100 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200">Batal</button>
                            <button type="submit" class="w-2/3 py-3 bg-blue-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 active:scale-95 transition-all">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showModalEditKepala" @keydown.window.escape="showModalEditKepala = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalEditKepala = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModalEditKepala" x-transition class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md p-6 z-10 shadow-2xl">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-4 text-center italic">Edit <span class="text-blue-600">Kepala Keluarga</span></h3>
                    <form action="{{ route('warga.profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="target_user_id" value="{{ $targetUser->id }}">
                        <input type="hidden" name="blok_rumah" value="{{ $targetUser->blok_rumah }}">
                        <input type="hidden" name="no_rumah" value="{{ $targetUser->no_rumah }}">
                        
                        <div>
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Nama Kepala Keluarga (Hanya Tampilan)</label>
                            <input type="text" value="{{ $targetUser->name }}" disabled class="w-full bg-slate-100 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold text-slate-400 cursor-not-allowed">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Nomor KK</label>
                                <input type="text" name="no_kk" x-model="editKepalaNoKk" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">NIK KTP</label>
                                <input type="text" name="nik" x-model="editKepalaNik" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" x-model="editKepalaTanggalLahir" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                        </div>
                        <div class="pt-3 flex gap-2">
                            <button type="button" @click="showModalEditKepala = false" class="w-1/3 py-3 bg-slate-100 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200">Batal</button>
                            <button type="submit" class="w-2/3 py-3 bg-blue-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 active:scale-95 transition-all">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function confirmDeleteAnggota(id) {
                Swal.fire({
                    title: 'Hapus Anggota Keluarga?',
                    text: 'Data anggota keluarga ini akan dihapus secara permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-anggota-' + id).submit();
                    }
                })
            }
        </script>
    @endpush
</x-app-layout>