<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Metode <span class="text-blue-600">Pembayaran</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic">Pengaturan Rekening Kas RT</p>
        </div>
    </x-slot>

    <div class="min-h-screen pb-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-sm rounded-[2rem] overflow-hidden">
                <form action="{{ route('settings.payment.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-4 sm:p-6 lg:p-8 space-y-4">
                        
                        <div class="group">
                            <label class="text-[10px] sm:text-[11px] font-black text-slate-400 uppercase mb-1.5 block ml-1 tracking-widest">Nama Bank / Dompet Digital</label>
                            <input type="text" name="nama_bank" value="{{ $setting->nama_bank ?? '' }}" 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-2.5 px-4 text-xs sm:text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all dark:text-white" 
                                placeholder="Contoh: BCA, Mandiri, atau OVO">
                        </div>

                        <div class="group">
                            <label class="text-[10px] sm:text-[11px] font-black text-slate-400 uppercase mb-1.5 block ml-1 tracking-widest">Nomor Rekening / No. HP</label>
                            <input type="text" name="nomor_rekening" value="{{ $setting->nomor_rekening ?? '' }}" 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-2.5 px-4 text-xs sm:text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all dark:text-white" 
                                placeholder="8976xxxxxx">
                        </div>

                        <div class="group">
                            <label class="text-[10px] sm:text-[11px] font-black text-slate-400 uppercase mb-1.5 block ml-1 tracking-widest">Nama Penerima (A.N)</label>
                            <input type="text" name="nama_penerima" value="{{ $setting->nama_penerima ?? '' }}" 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-2.5 px-4 text-xs sm:text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all dark:text-white" 
                                placeholder="Nama sesuai rekening">
                        </div>

                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                            <label class="text-[10px] sm:text-[11px] font-black text-slate-400 uppercase mb-3 block ml-1 tracking-widest">Barcode QRIS (Opsional)</label>
                            
                            <div class="flex flex-col sm:flex-row items-center gap-4">
                                <div class="relative group/qris shrink-0">
                                    <div class="size-20 sm:size-24 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center overflow-hidden border-2 border-dashed border-slate-200 dark:border-slate-700">
                                        @if($setting && $setting->qris_path)
                                            <img src="{{ asset('storage/' . $setting->qris_path) }}" 
                                                 class="w-full h-full object-cover transition-transform group-hover/qris:scale-110" 
                                                 alt="QRIS">
                                        @else
                                            <svg class="size-6 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.125 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5c-.621 0-1.125-.504-1.125-1.125v-4.5zM13.125 13.125v3.75m0 3.75h.008v.008h-.008v-.008zm3.75 0h.008v.008h-.008v-.008zm3.75-3.75h.008v.008h-.008v-.008zm0 3.75h.008v.008h-.008v-.008zm-3.75-3.75h.008v.008h-.008v-.008zm0-3.75h.008v.008h-.008v-.008zm3.75 0h.008v.008h-.008v-.008z"/></svg>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex-1 w-full text-center sm:text-left">
                                    <input type="file" name="qris_image" 
                                        class="block w-full text-[10px] sm:text-xs text-slate-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-xl file:border-0
                                        file:text-[10px] sm:file:text-xs file:font-black
                                        file:bg-blue-50 file:text-blue-600
                                        hover:file:bg-blue-100 transition-all cursor-pointer">
                                    <p class="mt-1.5 text-[9px] text-slate-400 font-medium italic">Format: JPG, PNG. Maksimal 2MB.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 sm:p-6 bg-slate-50/50 dark:bg-slate-800/50 border-t dark:border-gray-800 flex justify-end">
                        <button type="submit" class="w-full sm:w-auto py-2.5 px-8 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-black text-xs sm:text-sm shadow-lg shadow-blue-200 dark:shadow-none transition-all active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>