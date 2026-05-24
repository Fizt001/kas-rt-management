<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Laporan <span class="text-rose-600">Penggunaan Dana</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic">Realisasi Anggaran & Bukti Kegiatan RT</p>
        </div>
    </x-slot>

    @php
        $role = strtolower(str_replace(' ', '', auth()->user()->role));
        $canEdit = in_array($role, ['rt', 'superadmin']);
        $canView = in_array($role, ['rt', 'bendahara', 'superadmin']);
    @endphp

    <div x-data="{ 
        showModalFoto: false,
        showModalNota: false,
        showModalPreview: false,
        actionUrl: '',
        agendaName: '',
        previewUrl: '',
        previewTitle: '',
        currentNominal: 0,
        
        openFotoModal(id, name) {
            this.actionUrl = `/expenditures/foto/${id}`;
            this.agendaName = name;
            this.showModalFoto = true;
        },

        openNotaModal(id, name, nominal) {
            this.actionUrl = `/expenditures/nota/${id}`;
            this.agendaName = name;
            this.currentNominal = nominal;
            this.showModalNota = true;
        },

        openPreview(url, title) {
            this.previewUrl = url;
            this.previewTitle = title;
            this.showModalPreview = true;
        }
    }" class="p-4 sm:p-5 max-w-7xl mx-auto space-y-4 md:space-y-5">

        <div class="bg-blue-50 dark:bg-slate-800/50 rounded-2xl p-4 border border-blue-100 dark:border-slate-700 flex items-start gap-3">
            <svg class="size-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs text-blue-800 dark:text-slate-300 font-medium leading-relaxed">
                Bendahara & RT dapat melihat bukti kegiatan. Khusus <strong>RT</strong> dapat mengunggah atau mengubah data laporan setelah kegiatan terlaksana.
            </p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-5 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="space-y-4">
                @forelse($agendas as $agenda)
                    @php
                        $tanggalAcara = \Carbon\Carbon::parse($agenda->tanggal);
                        $isLocked = $tanggalAcara->startOfDay()->isFuture(); 
                    @endphp

                    <div x-data="{ expanded: false }" class="flex flex-col rounded-2xl border transition-all overflow-hidden {{ $agenda->status === 'batal' ? 'border-dashed border-rose-200 bg-rose-50/30 opacity-70' : ($isLocked ? 'border-dashed border-slate-200' : 'border-slate-100 bg-slate-50/50') }}">
                        
                        <!-- Header (Clickable for Mobile, Static for Desktop) -->
                        <div @click="expanded = !expanded" class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-4 p-4 cursor-pointer sm:cursor-default active:bg-slate-100 sm:active:bg-transparent dark:active:bg-slate-800">
                            <div class="flex-1 w-full pr-4 sm:pr-0">
                                <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider truncate">{{ $agenda->judul }}</h4>
                                <span class="text-[10px] font-bold text-slate-500">{{ $tanggalAcara->translatedFormat('d F Y') }}</span>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto mt-2 sm:mt-0">
                                <div class="flex flex-col sm:items-end min-w-[120px]">
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Dana Terpakai</p>
                                    <p class="text-sm font-black text-rose-600">Rp{{ number_format($agenda->realisasi_dana ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <svg class="size-4 text-slate-400 sm:hidden transform transition-transform duration-300" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>

                        <!-- Content (Collapsible on Mobile, Visible on Desktop) -->
                        <div class="sm:block" :class="expanded ? 'block border-t border-slate-200 dark:border-slate-700' : 'hidden'">
                            <div class="p-4 pt-2 sm:pt-4 flex flex-col sm:flex-row items-center sm:justify-end gap-4 bg-slate-50/30 sm:bg-transparent">
                                
                                <div class="flex gap-4 sm:gap-2 w-full sm:w-auto justify-center sm:justify-start">
                                    <button type="button" 
                                        @if($agenda->bukti_kegiatan) @click="openPreview('{{ asset('storage/' . $agenda->bukti_kegiatan) }}', 'Foto Kegiatan: {{ addslashes($agenda->judul) }}')" @endif
                                        class="flex flex-col items-center group">
                                        <div class="w-10 h-10 sm:w-8 sm:h-8 rounded-full flex items-center justify-center transition-all shadow-sm {{ $agenda->bukti_kegiatan ? 'bg-emerald-500 text-white shadow-lg cursor-pointer hover:scale-110' : 'bg-slate-200 text-slate-400' }}">
                                            <svg class="size-5 sm:size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </div>
                                        <span class="text-[8px] sm:text-[7px] font-black text-slate-500 sm:text-slate-400 mt-1.5 sm:mt-1 uppercase">Foto</span>
                                    </button>

                                    <button type="button" 
                                        @if($agenda->nota_belanja) @click="openPreview('{{ asset('storage/' . $agenda->nota_belanja) }}', 'Nota Belanja: {{ addslashes($agenda->judul) }}')" @endif
                                        class="flex flex-col items-center group">
                                        <div class="w-10 h-10 sm:w-8 sm:h-8 rounded-full flex items-center justify-center transition-all shadow-sm {{ $agenda->nota_belanja ? 'bg-blue-500 text-white shadow-lg cursor-pointer hover:scale-110' : 'bg-slate-200 text-slate-400' }}">
                                            <svg class="size-5 sm:size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <span class="text-[8px] sm:text-[7px] font-black text-slate-500 sm:text-slate-400 mt-1.5 sm:mt-1 uppercase">Nota</span>
                                    </button>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto sm:ml-4 border-t sm:border-t-0 border-slate-200 pt-3 sm:pt-0">
                                    @if($agenda->status === 'batal')
                                        <span class="px-4 py-2 sm:py-2 bg-rose-100 text-rose-600 rounded-xl text-[10px] sm:text-[9px] font-black uppercase italic border border-rose-200 text-center">Dibatalkan</span>
                                    @elseif(!$isLocked && $canEdit)
                                        <button @click="openFotoModal('{{ $agenda->id }}', '{{ addslashes($agenda->judul) }}')" class="flex-1 sm:flex-none px-4 py-2.5 sm:py-2 bg-emerald-500 text-white rounded-xl text-[10px] sm:text-[9px] font-black uppercase tracking-widest text-center shadow-sm active:scale-95 transition-all">Update Foto</button>
                                        <button @click="openNotaModal('{{ $agenda->id }}', '{{ addslashes($agenda->judul) }}', '{{ $agenda->realisasi_dana }}')" class="flex-1 sm:flex-none px-4 py-2.5 sm:py-2 bg-blue-600 text-white rounded-xl text-[10px] sm:text-[9px] font-black uppercase tracking-widest text-center shadow-sm active:scale-95 transition-all">Update Nota</button>
                                    @elseif($isLocked)
                                        <span class="px-4 py-2 sm:py-2 bg-slate-100 text-slate-400 rounded-xl text-[10px] sm:text-[9px] font-black uppercase italic text-center">Belum Terlaksana</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10"><p class="text-xs font-bold text-slate-400 italic uppercase">Data Kosong</p></div>
                @endforelse
            </div>
        </div>

        <div x-show="showModalPreview" @keydown.window.escape="showModalPreview = false" style="display: none;" class="fixed inset-0 z-[150] overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="showModalPreview = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white dark:bg-slate-900 rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl">
                    <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight" x-text="previewTitle"></h3>
                        <button @click="showModalPreview = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div class="p-2 bg-slate-100 dark:bg-slate-800 flex justify-center">
                        <img :src="previewUrl" class="max-h-[70vh] rounded-xl object-contain shadow-lg">
                    </div>
                    <div class="p-4 text-center">
                        <a :href="previewUrl" download class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Unduh Gambar Asli</a>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showModalFoto" @keydown.window.escape="showModalFoto = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalFoto = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white dark:bg-slate-900 rounded-3xl w-full max-w-sm p-6 shadow-2xl border border-slate-200">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white italic mb-1 uppercase">Upload <span class="text-emerald-500">Foto</span></h3>
                    <p class="text-[10px] font-bold text-slate-400 mb-5" x-text="agendaName"></p>
                    <form :action="actionUrl" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="file" name="bukti_kegiatan" accept="image/*" required class="w-full text-xs font-bold file:bg-emerald-500 file:text-white file:rounded-full file:border-0 file:px-4 file:py-2">
                        <div class="flex gap-2 pt-2">
                            <button type="button" @click="showModalFoto = false" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl font-black text-[10px] uppercase">Batal</button>
                            <button type="submit" class="flex-[2] py-3 bg-emerald-500 text-white rounded-xl font-black text-[10px] uppercase shadow-lg shadow-emerald-200">Simpan Foto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showModalNota" @keydown.window.escape="showModalNota = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalNota = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white dark:bg-slate-900 rounded-3xl w-full max-w-sm p-6 shadow-2xl border border-slate-200">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white italic mb-1 uppercase">Input <span class="text-blue-600">Nota & Dana</span></h3>
                    <p class="text-[10px] font-bold text-slate-400 mb-5" x-text="agendaName"></p>
                    <form :action="actionUrl" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="bg-rose-50 p-4 rounded-2xl">
                            <label class="text-[10px] font-black text-rose-600 uppercase mb-1.5 block">Total Dana Terpakai (Rp)</label>
                            <input type="number" name="realisasi_dana" x-model="currentNominal" required class="w-full border-none rounded-xl py-2 px-4 font-black text-sm">
                        </div>
                        <input type="file" name="nota_belanja" accept="image/*" class="w-full text-xs font-bold file:bg-blue-600 file:text-white file:rounded-full file:border-0 file:px-4 file:py-2">
                        <div class="flex gap-2 pt-2">
                            <button type="button" @click="showModalNota = false" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl font-black text-[10px] uppercase">Batal</button>
                            <button type="submit" class="flex-[2] py-3 bg-blue-600 text-white rounded-xl font-black text-[10px] uppercase shadow-lg shadow-blue-200">Simpan Nota</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>