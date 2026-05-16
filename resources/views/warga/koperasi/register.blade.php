<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">
            Pendaftaran <span class="text-amber-500">Koperasi RT</span>
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-8 md:p-12 shadow-2xl shadow-amber-500/10 border border-slate-100 dark:border-slate-800 text-center relative overflow-hidden">
            
            <div class="absolute -top-20 -right-20 size-64 bg-amber-50 dark:bg-amber-500/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 size-64 bg-orange-50 dark:bg-orange-500/5 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <div class="size-24 bg-amber-100 text-amber-600 rounded-3xl mx-auto flex items-center justify-center mb-8 rotate-3 shadow-inner">
                    <svg class="size-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v2m0 16v2m10-10h-2M4 12H2m15.364-7.364l-1.414 1.414M6.05 17.95l-1.414 1.414m12.728 0l-1.414-1.414M6.05 6.05L4.636 4.636"/></svg>
                </div>

                <h3 class="text-2xl md:text-3xl font-black text-slate-800 dark:text-white tracking-tight mb-4">Mari Maju Bersama Lingkungan Kita!</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed mb-10">
                    Koperasi RT adalah fasilitas simpan pinjam yang dikelola dari warga, oleh warga, dan untuk kesejahteraan warga. Dengan bergabung, Anda menyetujui asas kekeluargaan dan gotong royong yang berlaku di lingkungan kita.
                </p>

                <div class="grid sm:grid-cols-3 gap-6 max-w-3xl mx-auto mb-10 text-left">
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl">
                        <div class="size-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mb-3">
                            <span class="font-black text-xs">1</span>
                        </div>
                        <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-1">Simpanan Pokok</h4>
                        <p class="text-[10px] text-slate-500 leading-relaxed">Dibayarkan hanya satu kali saat pertama kali Anda mendaftar sebagai anggota.</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl">
                        <div class="size-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mb-3">
                            <span class="font-black text-xs">2</span>
                        </div>
                        <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-1">Simpanan Wajib</h4>
                        <p class="text-[10px] text-slate-500 leading-relaxed">Iuran rutin bulanan untuk memperkuat modal dan kas koperasi lingkungan.</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl">
                        <div class="size-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mb-3">
                            <span class="font-black text-xs">3</span>
                        </div>
                        <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-1">Bagi Hasil</h4>
                        <p class="text-[10px] text-slate-500 leading-relaxed">Sisa Hasil Usaha (SHU) akan dibagikan secara adil berdasarkan kontribusi anggota.</p>
                    </div>
                </div>

                <form action="{{ route('warga.koperasi.join') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-10 py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-amber-500/30 transition-all active:scale-95 group">
                        Ya, Saya Ingin Bergabung
                        <svg class="size-5 inline-block ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                    <p class="text-[10px] font-bold text-slate-400 mt-4 italic uppercase tracking-widest">*Tidak ada paksaan dalam keanggotaan ini</p>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>