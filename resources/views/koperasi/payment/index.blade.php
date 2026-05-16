<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Metode <span class="text-amber-500">Pembayaran</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic">Pengaturan Rekening Kas Koperasi RT</p>
        </div>
    </x-slot>

    <div class="min-h-screen pb-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            
            @if(session('success'))
            <div class="mb-4 bg-amber-500 text-white px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3 shadow-lg shadow-amber-500/20">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-sm rounded-[2rem] overflow-hidden">
                <form action="{{ route('koperasi.admin.payment.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6 lg:p-8 space-y-6">
                        
                        <div class="group">
                            <label class="text-[11px] font-black text-slate-400 uppercase mb-2 block ml-1 tracking-widest">Nama Bank / Dompet Digital</label>
                            <input type="text" name="nama_bank" value="{{ $setting->nama_bank ?? '' }}" 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-3.5 px-5 text-sm font-bold focus:ring-2 focus:ring-amber-500 transition-all dark:text-white" 
                                placeholder="Contoh: BCA, BRI, atau GoPay">
                        </div>

                        <div class="group">
                            <label class="text-[11px] font-black text-slate-400 uppercase mb-2 block ml-1 tracking-widest">Nomor Rekening / No. HP</label>
                            <input type="text" name="nomor_rekening" value="{{ $setting->nomor_rekening ?? '' }}" 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-3.5 px-5 text-sm font-bold focus:ring-2 focus:ring-amber-500 transition-all dark:text-white" 
                                placeholder="Masukkan nomor tanpa spasi">
                        </div>

                        <div class="group">
                            <label class="text-[11px] font-black text-slate-400 uppercase mb-2 block ml-1 tracking-widest">Nama Penerima (A.N)</label>
                            <input type="text" name="atas_nama" value="{{ $setting->atas_nama ?? '' }}" 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-3.5 px-5 text-sm font-bold focus:ring-2 focus:ring-amber-500 transition-all dark:text-white" 
                                placeholder="Nama sesuai buku tabungan">
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                            <label class="text-[11px] font-black text-slate-400 uppercase mb-4 block ml-1 tracking-widest">Barcode QRIS Koperasi (Opsional)</label>
                            
                            <div class="flex flex-col sm:flex-row items-center gap-6">
                                <div class="relative group/qris">
                                    <div class="w-32 h-32 bg-slate-100 dark:bg-slate-800 rounded-3xl flex items-center justify-center overflow-hidden border-2 border-dashed border-slate-200 dark:border-slate-700">
                                        @if(isset($setting) && $setting->qris_image)
                                            <img src="{{ asset('storage/' . $setting->qris_image) }}" 
                                                 class="w-full h-full object-cover transition-transform group-hover/qris:scale-110" 
                                                 alt="QRIS Koperasi">
                                        @else
                                            <svg class="size-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.125 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5c-.621 0-1.125-.504-1.125-1.125v-4.5zM13.125 13.125v3.75m0 3.75h.008v.008h-.008v-.008zm3.75 0h.008v.008h-.008v-.008zm3.75-3.75h.008v.008h-.008v-.008zm0 3.75h.008v.008h-.008v-.008zm-3.75-3.75h.008v.008h-.008v-.008zm0-3.75h.008v.008h-.008v-.008zm3.75 0h.008v.008h-.008v-.008z"/></svg>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex-1 w-full">
                                    <input type="file" name="qris_image" 
                                        class="block w-full text-xs text-slate-500
                                        file:mr-4 file:py-2.5 file:px-4
                                        file:rounded-xl file:border-0
                                        file:text-xs file:font-black
                                        file:bg-amber-50 file:text-amber-600
                                        hover:file:bg-amber-100 transition-all cursor-pointer">
                                    <p class="mt-2 text-[10px] text-slate-400 font-medium italic">Format: JPG, PNG. Maksimal 2MB.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-slate-50/50 dark:bg-slate-800/50 border-t dark:border-slate-800 flex justify-end">
                        <button type="submit" class="w-full sm:w-auto py-3.5 px-10 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-2xl font-black text-sm shadow-xl shadow-amber-200 dark:shadow-none transition-all active:scale-95">
                            Simpan Rekening Koperasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>