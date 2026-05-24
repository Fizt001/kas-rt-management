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
        showModalWa: false,
        showModalDetail: false,
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

        <div class="bg-blue-600 dark:bg-slate-800 rounded-xl shadow-md p-3 sm:p-4 relative overflow-hidden flex flex-col xl:flex-row gap-3 sm:gap-4 items-start xl:items-center justify-between text-white">
            <svg class="absolute -right-4 -top-4 opacity-10 size-24 sm:size-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            
            <div class="relative z-10 w-full xl:w-auto flex items-center gap-3">
                <div class="size-10 sm:size-12 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                    <svg class="size-5 sm:size-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>
                <div>
                    <p class="text-[9px] sm:text-[10px] font-black text-blue-200 uppercase tracking-widest mb-0.5">Kepala Keluarga</p>
                    <h2 class="text-base sm:text-xl font-black text-white tracking-tight leading-none mb-1.5">{{ $targetUser->name }}</h2>
                    <div class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-white/20 border border-white/30 text-white rounded-md text-[8px] sm:text-[9px] font-black uppercase tracking-widest backdrop-blur-sm">
                        Total: {{ $targetUser->familyMembers->count() + 1 }} Jiwa
                    </div>
                </div>
            </div>

            <form action="{{ route('warga.profile.update') }}" method="POST" class="relative z-10 w-full xl:w-auto bg-white/10 dark:bg-black/20 p-2.5 sm:p-3 rounded-lg flex flex-col sm:flex-row gap-2 sm:gap-3 items-end sm:items-center">
                @csrf
                <input type="hidden" name="target_user_id" value="{{ $targetUser->id }}">
                <input type="hidden" name="no_kk" value="{{ $targetUser->no_kk }}">
                <input type="hidden" name="nik" value="{{ $targetUser->nik }}">
                
                <div class="flex gap-2 w-full sm:w-auto">
                    <div class="w-full sm:w-24">
                        <label class="text-[8px] sm:text-[9px] font-black text-blue-200 uppercase tracking-widest block mb-0.5">Blok</label>
                        <input type="text" name="blok_rumah" value="{{ $targetUser->blok_rumah }}" placeholder="E1" class="w-full bg-white/20 border-none text-white placeholder:text-white/40 rounded-md py-1.5 px-2.5 text-xs font-bold focus:ring-2 focus:ring-white uppercase">
                    </div>
                    <div class="w-full sm:w-24">
                        <label class="text-[8px] sm:text-[9px] font-black text-blue-200 uppercase tracking-widest block mb-0.5">No Rumah</label>
                        <input type="text" name="no_rumah" value="{{ $targetUser->no_rumah }}" placeholder="01" class="w-full bg-white/20 border-none text-white placeholder:text-white/40 rounded-md py-1.5 px-2.5 text-xs font-bold focus:ring-2 focus:ring-white uppercase">
                    </div>
                </div>
                <button type="submit" class="w-full sm:w-auto px-4 py-1.5 bg-amber-400 text-slate-900 rounded-md text-[9px] sm:text-[10px] font-black uppercase tracking-widest hover:bg-amber-300 transition-all shadow-sm active:scale-95 h-full mt-2 sm:mt-0 self-stretch flex items-center justify-center">
                    Simpan
                </button>
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

        <div class="flex justify-end gap-3">
            @if($isAdmin)
                <button @click="showModalDetail = true" class="w-full sm:w-auto px-5 py-3 sm:py-2.5 bg-blue-600 text-white rounded-xl text-[11px] sm:text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all flex items-center justify-center gap-2 shadow-md active:scale-95">
                    <svg class="size-4 sm:size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Detail Warga
                </button>
            @else
                <button @click="showModalWa = true" class="w-full sm:w-auto px-5 py-3 sm:py-2.5 bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-400 rounded-xl text-[11px] sm:text-xs font-black uppercase tracking-widest hover:bg-emerald-100 transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                    <svg class="size-4 sm:size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    {{ $targetUser->phone ? 'Update WA' : '+ No WA' }}
                </button>
                <button @click="showModalAdd = true" class="w-full sm:w-auto px-5 py-3 sm:py-2.5 bg-slate-800 dark:bg-slate-700 text-white rounded-xl text-[11px] sm:text-xs font-black uppercase tracking-widest hover:bg-slate-900 transition-all flex items-center justify-center gap-2 shadow-md active:scale-95">
                    <svg class="size-4 sm:size-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Anggota Baru
                </button>
            @endif
        </div>

        @foreach($groupedMembers as $kelompok => $members)
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 p-4 sm:p-5 mb-5">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-start gap-3">
                    <div class="size-8 sm:size-10 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                        <svg class="size-4 sm:size-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[10px] sm:text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest leading-tight">
                            Grup Anggota<br/>
                            <span class="text-blue-600 text-xs sm:text-sm">{{ $kelompok }}</span>
                        </h3>
                        <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest bg-slate-50 dark:bg-slate-800 px-2 py-0.5 rounded-md inline-block border border-slate-100 dark:border-slate-700">Total {{ $kelompok === 'KK Utama' ? $members->count() + 1 : $members->count() }} Jiwa</p>
                    </div>
                </div>
            </div>

            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-[10px] font-black uppercase tracking-widest text-slate-500">
                            <th class="px-4 py-2.5 rounded-l-lg">Nama Lengkap</th>
                            <th class="px-4 py-2.5 text-center">Status</th>
                            <th class="px-4 py-2.5">NIK / No KK</th>
                            @if(!$isAdmin)
                            <th class="px-4 py-2.5 text-right rounded-r-lg">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @if($kelompok === 'KK Utama')
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors bg-blue-50/20 dark:bg-blue-900/10">
                                <td class="px-4 py-3 text-sm font-bold text-slate-800 dark:text-slate-200">
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
                                @if(!$isAdmin)
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-1.5">
                                        <button type="button" @click="editKepalaNik = '{{ $targetUser->nik }}'; editKepalaNoKk = '{{ $targetUser->no_kk }}'; editKepalaTanggalLahir = '{{ $targetUser->tanggal_lahir }}'; showModalEditKepala = true" class="text-blue-600 hover:text-blue-800 bg-blue-50 dark:bg-blue-900/30 p-2 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endif
                        
                        @forelse($members as $anggota)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3 text-sm font-bold text-slate-800 dark:text-slate-200">
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
                                @if(!$isAdmin)
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
                                @endif
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

            <!-- MOBILE ACCORDION VIEW -->
            <div class="sm:hidden space-y-2.5">
                @if($kelompok === 'KK Utama')
                    <div x-data="{ expanded: false }" class="flex flex-col bg-blue-50/20 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-slate-800 transition-all overflow-hidden">
                        <div @click="expanded = !expanded" class="flex flex-row items-center justify-between p-4 cursor-pointer active:bg-slate-50 dark:active:bg-slate-800">
                            <div class="flex-1 min-w-0 pr-3">
                                <h4 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider truncate">{{ $targetUser->name }}</h4>
                                <span class="text-[9px] font-bold text-blue-600 dark:text-blue-400 mt-0.5 block uppercase tracking-widest">Kepala Keluarga</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="size-4 text-slate-400 transform transition-transform duration-300" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <div x-show="expanded" x-collapse x-cloak>
                            <div class="p-4 pt-1 bg-white/50 dark:bg-transparent border-t border-blue-100/50 dark:border-slate-800">
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div>
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Tgl Lahir</p>
                                        <p class="text-[10px] font-bold text-slate-800 dark:text-slate-200">{{ $targetUser->tanggal_lahir ? \Carbon\Carbon::parse($targetUser->tanggal_lahir)->format('d M Y') : '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Usia</p>
                                        <p class="text-[10px] font-bold text-slate-800 dark:text-slate-200">{{ $targetUser->tanggal_lahir ? \Carbon\Carbon::parse($targetUser->tanggal_lahir)->age . ' Tahun' : '-' }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">NIK</p>
                                        <p class="text-[10px] font-bold text-slate-800 dark:text-slate-200">{{ maskNikKk($targetUser->nik) }}</p>
                                    </div>
                                </div>
                                @if(!$isAdmin)
                                <button type="button" @click="editKepalaNik = '{{ $targetUser->nik }}'; editKepalaNoKk = '{{ $targetUser->no_kk }}'; editKepalaTanggalLahir = '{{ $targetUser->tanggal_lahir }}'; showModalEditKepala = true" class="w-full flex justify-center items-center gap-2 text-blue-600 bg-blue-50 dark:bg-blue-900/30 p-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Edit NIK
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                
                @forelse($members as $anggota)
                    <div x-data="{ expanded: false }" class="flex flex-col bg-slate-50/50 dark:bg-slate-800/30 rounded-2xl border border-slate-100 dark:border-slate-800 transition-all overflow-hidden">
                        <div @click="expanded = !expanded" class="flex flex-row items-center justify-between p-4 cursor-pointer active:bg-slate-100 dark:active:bg-slate-800/60">
                            <div class="flex-1 min-w-0 pr-3">
                                <h4 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider truncate">{{ $anggota->nama }}</h4>
                                <span class="text-[9px] font-bold text-slate-500 mt-0.5 block uppercase tracking-widest">{{ $anggota->status_hubungan }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="size-4 text-slate-400 transform transition-transform duration-300" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <div x-show="expanded" x-collapse x-cloak>
                            <div class="p-4 pt-1 bg-white dark:bg-transparent border-t border-slate-100 dark:border-slate-800">
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div>
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Tgl Lahir</p>
                                        <p class="text-[10px] font-bold text-slate-800 dark:text-slate-200">{{ $anggota->tanggal_lahir ? \Carbon\Carbon::parse($anggota->tanggal_lahir)->format('d M Y') : '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Usia</p>
                                        <p class="text-[10px] font-bold text-slate-800 dark:text-slate-200">{{ $anggota->tanggal_lahir ? \Carbon\Carbon::parse($anggota->tanggal_lahir)->age . ' Tahun' : '-' }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">NIK</p>
                                        <p class="text-[10px] font-bold text-slate-800 dark:text-slate-200">{{ maskNikKk($anggota->nik) }}</p>
                                    </div>
                                </div>
                                @if(!$isAdmin)
                                <div class="flex gap-2">
                                    <button type="button" @click="editUrl = '{{ route('warga.family.update', $anggota->id) }}'; editNama = '{{ addslashes($anggota->nama) }}'; editStatus = '{{ $anggota->status_hubungan }}'; editNik = '{{ $anggota->nik }}'; editKelompokKk = '{{ $anggota->kelompok_kk ?: 'KK Utama' }}'; editNoKk = '{{ $anggota->no_kk_kelompok }}'; editTanggalLahir = '{{ $anggota->tanggal_lahir }}'; showModalEdit = true" class="flex-1 flex justify-center items-center gap-2 text-blue-600 bg-blue-50 dark:bg-blue-900/20 p-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Edit
                                    </button>
                                    
                                    <form action="{{ route('warga.family.destroy', $anggota->id) }}" method="POST" class="flex-1" id="delete-mob-{{ $anggota->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteAnggotaMob('{{ $anggota->id }}')" class="w-full flex justify-center items-center gap-2 text-rose-600 bg-rose-50 dark:bg-rose-900/20 p-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Belum ada data anggota keluarga</p>
                    </div>
                @endforelse
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

        <div x-show="showModalWa" @keydown.window.escape="showModalWa = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalWa = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModalWa" x-transition class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-sm p-6 z-10 shadow-2xl">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-4 text-center italic">Update <span class="text-emerald-600">Nomor WhatsApp</span></h3>
                    <form action="{{ route('warga.profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="target_user_id" value="{{ $targetUser->id }}">
                        
                        <div>
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Nomor HP/WA Aktif</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-bold text-slate-400 border-r border-slate-200 dark:border-slate-700 pr-3">
                                    +62
                                </span>
                                <input type="text" name="phone" value="{{ str_starts_with($targetUser->phone ?? '', '62') ? substr($targetUser->phone, 2) : ($targetUser->phone ?? '') }}" placeholder="81234567890" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 pl-16 pr-4 text-sm font-bold focus:ring-2 focus:ring-emerald-600 dark:text-white">
                            </div>
                            <p class="text-[9px] text-slate-400 mt-2 italic">*Digunakan bendahara/RT untuk mengirim tagihan atau notifikasi penting.</p>
                        </div>
                        <div class="pt-3 flex gap-2">
                            <button type="button" @click="showModalWa = false" class="w-1/3 py-3 bg-slate-100 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200">Batal</button>
                            <button type="submit" class="w-2/3 py-3 bg-emerald-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-700 active:scale-95 transition-all">Simpan Nomor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($isAdmin)
        <div x-show="showModalDetail" @keydown.window.escape="showModalDetail = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalDetail = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModalDetail" x-transition class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-2xl p-6 z-10 shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                    
                    <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-100 dark:border-slate-800 shrink-0">
                        <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight uppercase">Detail Warga</h3>
                        <button type="button" @click="showModalDetail = false" class="text-slate-400 hover:text-slate-600 bg-slate-50 hover:bg-slate-100 p-2 rounded-lg transition-colors dark:bg-slate-800 dark:hover:bg-slate-700">
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="overflow-y-auto pr-2 custom-scrollbar flex-1 space-y-6">
                        <!-- Info Rumah -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Blok & No Rumah</p>
                                <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $targetUser->blok_rumah ?? '-' }} / {{ $targetUser->no_rumah ?? '-' }}</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">WA Penanggung Jawab</p>
                                <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $targetUser->phone ? '+'.$targetUser->phone : 'Belum Terdaftar' }}</p>
                            </div>
                        </div>

                        <!-- Data Anggota -->
                        @foreach($groupedMembers as $kelompok => $membersList)
                            <div>
                                <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                    {{ $kelompok }}
                                </h4>
                                <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left whitespace-nowrap text-xs">
                                            <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                                                <tr>
                                                    <th class="px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-slate-500">Nama Lengkap</th>
                                                    <th class="px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-slate-500 text-center">Status</th>
                                                    <th class="px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-slate-500">NIK / KK</th>
                                                    <th class="px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-slate-500">Tgl Lahir</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                                @if($kelompok === 'KK Utama')
                                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                                        <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200">{{ $targetUser->name }}</td>
                                                        <td class="px-4 py-3 text-center"><span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest border border-blue-200 dark:border-blue-800">Kepala Keluarga</span></td>
                                                        <td class="px-4 py-3 text-[10px] text-slate-500 dark:text-slate-400 font-bold">NIK: {{ maskNikKk($targetUser->nik) }}<br><span class="text-[9px] font-medium opacity-70">KK: {{ maskNikKk($targetUser->no_kk) }}</span></td>
                                                        <td class="px-4 py-3 text-[10px] font-bold text-slate-500 dark:text-slate-400">{{ $targetUser->tanggal_lahir ? \Carbon\Carbon::parse($targetUser->tanggal_lahir)->format('d M Y') : '-' }}</td>
                                                    </tr>
                                                @endif
                                                @foreach($membersList as $anggota)
                                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                                        <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200">{{ $anggota->nama }}</td>
                                                        <td class="px-4 py-3 text-center"><span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest border border-slate-200 dark:border-slate-600">{{ $anggota->status_hubungan }}</span></td>
                                                        <td class="px-4 py-3 text-[10px] text-slate-500 dark:text-slate-400 font-bold">NIK: {{ maskNikKk($anggota->nik) }}<br><span class="text-[9px] font-medium opacity-70">KK: {{ maskNikKk($anggota->no_kk_kelompok ?? $targetUser->no_kk) }}</span></td>
                                                        <td class="px-4 py-3 text-[10px] font-bold text-slate-500 dark:text-slate-400">{{ $anggota->tanggal_lahir ? \Carbon\Carbon::parse($anggota->tanggal_lahir)->format('d M Y') : '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end shrink-0">
                        <a href="{{ route('warga.print', $targetUser->id) }}" target="_blank" class="px-5 py-3 bg-slate-800 text-white rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-widest hover:bg-slate-900 transition-all flex items-center justify-center gap-2 shadow-md active:scale-95 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100">
                            <svg class="size-4 sm:size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak Document
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

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

            function confirmDeleteAnggotaMob(id) {
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
                        document.getElementById('delete-mob-' + id).submit();
                    }
                })
            }
        </script>
    @endpush
</x-app-layout>