<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'KAS-RT') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-900" x-data="{ sidebarOpen: false, sidebarMini: localStorage.getItem('sidebarMini') === 'true' }" x-init="$watch('sidebarMini', val => localStorage.setItem('sidebarMini', val))">

    {{-- Overlay & Mobile Sidebar (Sama seperti sebelumnya) --}}
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/80 lg:hidden" x-cloak></div>

    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform" 
         x-transition:enter-start="-translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in-out duration-300 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="-translate-x-full" 
         class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-slate-100 shadow-2xl lg:hidden" x-cloak>
        @include('layouts.sidebar')
    </div>

    {{-- DESKTOP SIDEBAR (FIXED & NO SCROLL PADA WRAPPER) --}}
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:flex-col transition-all duration-300 ease-in-out" :class="sidebarMini ? 'lg:w-20' : 'lg:w-64'">
        {{-- Pastikan h-full dan overflow-hidden agar pembungkus tidak ikut scroll --}}
        <div class="flex flex-col h-full overflow-hidden border-r border-slate-200 bg-slate-100 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
            @include('layouts.sidebar')
        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="flex flex-col h-screen transition-all duration-300 ease-in-out" :class="sidebarMini ? 'lg:pl-20' : 'lg:pl-64'">
        @include('layouts.navigation')
        <main class="flex-1 overflow-y-auto bg-slate-50">
            <div class="py-8 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- GLOBAL TOAST NOTIFICATION --}}
    @if(session('success') || session('error') || session('status') || $errors->any())
        <div x-data="{ show: true }" 
             x-init="setTimeout(() => show = false, 3000)" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-x-10"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-10"
             class="fixed top-5 right-5 z-[100] max-w-sm w-full p-4 rounded-xl shadow-2xl border {{ session('error') || $errors->any() ? 'bg-rose-50 border-rose-200 text-rose-700' : 'bg-emerald-50 border-emerald-200 text-emerald-700' }}"
             x-cloak>
            <div class="flex items-start gap-3">
                @if(session('error') || $errors->any())
                    <svg class="size-6 text-rose-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                @else
                    <svg class="size-6 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @endif
                <div class="flex-1">
                    <h3 class="text-sm font-black uppercase tracking-widest">{{ session('error') || $errors->any() ? 'Gagal!' : 'Berhasil!' }}</h3>
                    <p class="text-xs font-medium mt-0.5 opacity-90">
                        @if(session('success'))
                            {{ session('success') }}
                        @elseif(session('status'))
                            {{ session('status') }}
                        @elseif(session('error'))
                            {{ session('error') }}
                        @elseif($errors->any())
                            {{ $errors->first() }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    @php
        $roleNormalized = strtolower(str_replace(' ', '', auth()->user()->role ?? ''));
        $globalExpiredAgendas = collect();
        if (in_array($roleNormalized, ['rt', 'superadmin'])) {
            $globalExpiredAgendas = \App\Models\Agenda::where('tanggal', '<=', now()->format('Y-m-d'))
                                    ->where('status', 'aktif')
                                    ->get();
        }
    @endphp

    @if($globalExpiredAgendas->count() > 0)
        <!-- Formulir tersembunyi untuk Global Agenda -->
        @foreach($globalExpiredAgendas as $agenda)
            <form id="global-form-status-{{ $agenda->id }}" action="{{ route('agendas.update-status', $agenda->id) }}" method="POST" class="hidden">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="">
            </form>
            <form id="global-form-reschedule-{{ $agenda->id }}" action="{{ route('agendas.reschedule', $agenda->id) }}" method="POST" class="hidden">
                @csrf @method('PATCH')
                <input type="hidden" name="tanggal_baru" value="">
            </form>
        @endforeach

        <script>
            document.addEventListener('DOMContentLoaded', async () => {
                const expired = @json($globalExpiredAgendas);
                for (const agenda of expired) {
                    const { value: action, dismiss } = await Swal.fire({
                        title: 'Agenda Kegiatan!',
                        html: `Kegiatan <b>${agenda.judul}</b> dijadwalkan pada ${agenda.tanggal}.<br><br><b>Apa status pelaksanaannya?</b>`,
                        icon: 'info',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Laksanakan',
                        denyButtonText: 'Tunda',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#10b981',
                        denyButtonColor: '#3b82f6',
                        cancelButtonColor: '#ef4444',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });

                    if (action) { 
                        // Laksanakan
                        document.querySelector(`#global-form-status-${agenda.id} input[name="status"]`).value = 'selesai';
                        document.getElementById(`global-form-status-${agenda.id}`).submit();
                        break;
                    } else if (Swal.getDenyButton() === document.activeElement) {
                        // Tunda
                        const { value: tanggalBaru } = await Swal.fire({
                            title: 'Pilih Tanggal Baru',
                            input: 'date',
                            inputValidator: (value) => {
                                if (!value) return 'Anda harus memilih tanggal!';
                            }
                        });
                        if (tanggalBaru) {
                            document.querySelector(`#global-form-reschedule-${agenda.id} input[name="tanggal_baru"]`).value = tanggalBaru;
                            document.getElementById(`global-form-reschedule-${agenda.id}`).submit();
                            break;
                        } else {
                            location.reload();
                            break;
                        }
                    } else if (dismiss === Swal.DismissReason.cancel) {
                        // Batal
                        const { isConfirmed } = await Swal.fire({
                            title: 'Batalkan Kegiatan?',
                            text: "Apakah Anda yakin ingin membatalkan kegiatan ini? Aksi ini akan dicatat di laporan.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Batalkan',
                            cancelButtonText: 'Kembali'
                        });
                        if (isConfirmed) {
                            document.querySelector(`#global-form-status-${agenda.id} input[name="status"]`).value = 'batal';
                            document.getElementById(`global-form-status-${agenda.id}`).submit();
                            break;
                        } else {
                            location.reload();
                            break;
                        }
                    }
                }
            });
        </script>
    @endif

    <!-- Load SweetAlert globally -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('scripts')
</body>
</html>