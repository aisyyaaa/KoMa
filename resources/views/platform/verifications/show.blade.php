@extends('layouts.platform')

@section('title', 'Detail Verifikasi Penjual')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Detail Calon Penjual</h1>
        <p class="text-sm text-gray-500">Periksa data administrasi dan pilih tindakan.</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 gap-4">
            <table class="w-full table-auto">
                <tbody>
                    <tr>
                        <td class="font-semibold w-48">Nama Toko</td>
                        <td>{{ $seller->store_name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Nama PIC</td>
                        <td>{{ $seller->pic_name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Email PIC</td>
                        <td>{{ $seller->pic_email }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Telepon</td>
                        <td>{{ $seller->pic_phone }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">No. KTP</td>
                        <td>{{ $seller->pic_ktp_number }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Status</td>
                        <td>{{ $seller->status }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Terdaftar</td>
                        <td>{{ optional($seller->created_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Foto PIC</td>
                        <td>
                            @if($seller->pic_photo_path)
                                <a href="{{ asset($seller->pic_photo_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Foto</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="font-semibold">File KTP</td>
                        <td>
                            @if($seller->pic_ktp_file_path)
                                <a href="{{ asset($seller->pic_ktp_file_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File KTP</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-6 flex items-center space-x-3">
                <form action="{{ route('platform.verifications.sellers.approve', $seller) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Setujui</button>
                </form>

                <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="px-4 py-2 bg-red-600 text-white rounded">Tolak</button>
                <a href="{{ route('platform.verifications.sellers.index') }}" class="px-4 py-2 bg-gray-200 rounded">Kembali</a>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-bold mb-3">Alasan Penolakan</h3>
            <form action="{{ route('platform.verifications.sellers.reject', $seller) }}" method="POST">
                @csrf
                <textarea name="reason" rows="4" class="w-full border rounded p-2" placeholder="Jelaskan alasan penolakan (opsional)"></textarea>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="px-3 py-2 bg-gray-200 rounded">Batal</button>
                    <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded">Kirim & Tolak</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
@extends('layouts.app')

@section('title', 'Seller Verification')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Verify Seller: {{ $seller->store_name }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold">Info Penjual</h3>
            <p>{{ $seller->pic_name }} - {{ $seller->pic_email }}</p>
            <p>{{ $seller->pic_province }}, {{ $seller->pic_city }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold">Dokumen</h3>
            <p><a href="#">Lihat Foto KTP</a></p>
            <p><a href="#">Lihat Foto Diri</a></p>
        </div>
    </div>

    <div class="mt-4">
        @include('platform.verifications._verification-form', ['seller' => $seller])
    </div>
</div>
@endsection
