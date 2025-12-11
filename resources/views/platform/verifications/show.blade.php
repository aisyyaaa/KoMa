@extends('layouts.platform')

@section('title', 'Detail Verifikasi Penjual')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Detail Calon Penjual</h1>
        <p class="text-sm text-gray-500">Periksa data administrasi dan pilih tindakan.</p>
    </div>

    {{-- Notifikasi Sukses/Error --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-2 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        
        {{-- Data Detail Penjual --}}
        <div class="grid grid-cols-1 gap-4 mb-6">
            <table class="w-full table-auto text-sm">
                <tbody>
                    <tr>
                        <td class="font-semibold w-48 py-1">Nama Toko</td>
                        <td class="py-1">{{ $seller->store_name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold py-1">Nama PIC</td>
                        <td class="py-1">{{ $seller->pic_name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold py-1">Email PIC</td>
                        <td class="py-1">{{ $seller->email }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold py-1">Telepon</td>
                        <td class="py-1">{{ $seller->phone_number }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold py-1">No. KTP</td>
                        <td class="py-1">{{ $seller->ktp_number }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold py-1">Status</td>
                        <td class="py-1">
                            <span class="font-bold text-{{ $seller->status === 'ACTIVE' ? 'green-600' : ($seller->status === 'REJECTED' ? 'red-600' : 'yellow-600') }}">
                                {{ strtoupper($seller->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-semibold py-1">Terdaftar</td>
                        <td class="py-1">{{ optional($seller->created_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold py-1">Foto PIC</td>
                        <td class="py-1">
                            @if($seller->pic_photo_path)
                                <a href="{{ Storage::url($seller->pic_photo_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Foto</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="font-semibold py-1">File KTP</td>
                        <td class="py-1">
                            @if($seller->ktp_file_path)
                                <a href="{{ Storage::url($seller->ktp_file_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File KTP</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Logika Tombol Aksi (Hanya muncul jika status PENDING) --}}
        @if ($seller->status === 'PENDING')
            <h3 class="text-lg font-semibold border-t pt-4 mt-4">Pilih Tindakan Verifikasi:</h3>
            <div class="mt-4 flex items-center space-x-3">
                
                {{-- Tombol Setujui (Langsung Submit) --}}
                <form action="{{ route('platform.verifications.sellers.approve', $seller) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2 bg-green-600 text-white font-semibold rounded hover:bg-green-700 transition">Setujui</button>
                </form>

                {{-- Tombol Tolak (Membuka Modal) --}}
                <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="px-5 py-2 bg-red-600 text-white font-semibold rounded hover:bg-red-700 transition">Tolak</button>
                
                {{-- Tombol Kembali --}}
                <a href="{{ route('platform.verifications.sellers.index') }}" class="px-5 py-2 bg-gray-200 font-semibold rounded hover:bg-gray-300 transition">Kembali</a>
            </div>
        @else
            <div class="border-t pt-4 mt-4 text-center">
                <p class="text-sm text-gray-500">Status sudah **{{ strtoupper($seller->status) }}**. Tidak ada tindakan yang tersedia.</p>
                <a href="{{ route('platform.verifications.sellers.index') }}" class="mt-3 inline-block px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Kembali ke Daftar</a>
            </div>
        @endif
    </div>

    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96 max-w-full">
            <h3 class="text-lg font-bold mb-3">Alasan Penolakan</h3>
            <form action="{{ route('platform.verifications.sellers.reject', $seller) }}" method="POST">
                @csrf
                <textarea name="reason" rows="4" class="w-full border rounded p-2 text-sm focus:ring-red-500 focus:border-red-500" placeholder="Jelaskan alasan penolakan (wajib)" required></textarea>
                @if ($errors->has('reason'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('reason') }}</p>
                @endif
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">Kirim & Tolak</button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- Script untuk menampilkan modal jika ada error validasi reason saat reject --}}
@if ($errors->has('reason'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('rejectModal').classList.remove('hidden');
    });
</script>
@endif

@endsection