<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Warga - {{ $targetUser->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 p-8 min-h-screen font-sans" onload="window.print()">
    <div class="max-w-4xl mx-auto bg-white p-10 shadow-sm border border-slate-200">
        <!-- Header -->
        <div class="border-b-2 border-slate-800 pb-6 mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black uppercase tracking-widest text-slate-900">Data Rincian Warga</h1>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Sistem Manajemen Kas RT</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Dicetak Pada</p>
                <p class="text-xs font-bold text-slate-800">{{ now()->format('d F Y H:i') }}</p>
            </div>
        </div>

        @php
            function maskNikKk($str) {
                if (!$str) return '-';
                $len = strlen($str);
                if ($len <= 8) return str_repeat('*', $len);
                return substr($str, 0, 4) . str_repeat('*', $len - 8) . substr($str, -4);
            }
        @endphp

        <!-- Identitas Utama -->
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="bg-slate-50 p-5 border border-slate-200 rounded-lg">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Warga / Kepala Rumah Tangga</h3>
                <p class="text-lg font-bold text-slate-800">{{ $targetUser->name }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 p-5 border border-slate-200 rounded-lg">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Blok / No Rumah</h3>
                    <p class="text-lg font-bold text-slate-800">{{ $targetUser->blok_rumah ?? '-' }} / {{ $targetUser->no_rumah ?? '-' }}</p>
                </div>
                <div class="bg-slate-50 p-5 border border-slate-200 rounded-lg">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">WA Penanggung Jawab</h3>
                    <p class="text-base font-bold text-slate-800">{{ $targetUser->phone ? '+'.$targetUser->phone : '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Daftar Anggota -->
        <div class="mb-8">
            <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4 border-b border-slate-200 pb-2">Rincian Anggota Keluarga Dalam Satu Rumah</h2>
            
            @foreach($groupedMembers as $kelompok => $membersList)
                <div class="mb-6">
                    <h3 class="text-xs font-black text-blue-700 uppercase tracking-widest mb-3 bg-blue-50 py-1.5 px-3 inline-block rounded border border-blue-100">Grup: {{ $kelompok }}</h3>
                    <table class="w-full text-left border-collapse border border-slate-300 text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="border border-slate-300 px-4 py-2 text-[10px] font-black uppercase text-slate-600">No</th>
                                <th class="border border-slate-300 px-4 py-2 text-[10px] font-black uppercase text-slate-600">Nama Lengkap</th>
                                <th class="border border-slate-300 px-4 py-2 text-[10px] font-black uppercase text-slate-600">Status Keluarga</th>
                                <th class="border border-slate-300 px-4 py-2 text-[10px] font-black uppercase text-slate-600">NIK</th>
                                <th class="border border-slate-300 px-4 py-2 text-[10px] font-black uppercase text-slate-600">No. KK</th>
                                <th class="border border-slate-300 px-4 py-2 text-[10px] font-black uppercase text-slate-600">Tgl Lahir / Usia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @if($kelompok === 'KK Utama')
                                <tr>
                                    <td class="border border-slate-300 px-4 py-2 text-center">{{ $no++ }}</td>
                                    <td class="border border-slate-300 px-4 py-2 font-bold">{{ $targetUser->name }}</td>
                                    <td class="border border-slate-300 px-4 py-2">Kepala Keluarga</td>
                                    <td class="border border-slate-300 px-4 py-2 font-mono text-xs">{{ maskNikKk($targetUser->nik) }}</td>
                                    <td class="border border-slate-300 px-4 py-2 font-mono text-xs">{{ maskNikKk($targetUser->no_kk) }}</td>
                                    <td class="border border-slate-300 px-4 py-2">
                                        {{ $targetUser->tanggal_lahir ? \Carbon\Carbon::parse($targetUser->tanggal_lahir)->format('d/m/Y') . ' (' . \Carbon\Carbon::parse($targetUser->tanggal_lahir)->age . ' Thn)' : '-' }}
                                    </td>
                                </tr>
                            @endif
                            @foreach($membersList as $anggota)
                                <tr>
                                    <td class="border border-slate-300 px-4 py-2 text-center">{{ $no++ }}</td>
                                    <td class="border border-slate-300 px-4 py-2 font-bold">{{ $anggota->nama }}</td>
                                    <td class="border border-slate-300 px-4 py-2">{{ $anggota->status_hubungan }}</td>
                                    <td class="border border-slate-300 px-4 py-2 font-mono text-xs">{{ maskNikKk($anggota->nik) }}</td>
                                    <td class="border border-slate-300 px-4 py-2 font-mono text-xs">{{ maskNikKk($anggota->no_kk_kelompok ?? $targetUser->no_kk) }}</td>
                                    <td class="border border-slate-300 px-4 py-2">
                                        {{ $anggota->tanggal_lahir ? \Carbon\Carbon::parse($anggota->tanggal_lahir)->format('d/m/Y') . ' (' . \Carbon\Carbon::parse($anggota->tanggal_lahir)->age . ' Thn)' : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

        <!-- Footer Tanda Tangan -->
        <div class="mt-16 flex justify-end">
            <div class="text-center w-64">
                <p class="text-xs font-medium mb-16">Pengurus RT / Superadmin</p>
                <p class="text-sm font-bold border-b border-slate-800 pb-1">{{ auth()->user()->name }}</p>
            </div>
        </div>

        <div class="mt-10 text-center no-print">
            <button onclick="window.close()" class="px-6 py-2.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-slate-300 transition-colors mr-2">Tutup Tab</button>
            <button onclick="window.print()" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-blue-700 transition-colors shadow-md">Cetak Ulang</button>
        </div>
    </div>
</body>
</html>
