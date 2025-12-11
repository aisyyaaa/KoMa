<!doctype html>
<html>
<body>
    <p>Halo {{ $seller->pic_name }},</p>
    <p>Selamat — pendaftaran toko <strong>{{ $seller->store_name }}</strong> telah diverifikasi dan akun Anda telah diaktifkan.</p>
    <p>Anda sekarang dapat masuk menggunakan email ini: <strong>{{ $seller->pic_email }}</strong>.</p>
    <p>Untuk melanjutkan, silakan kunjungi halaman berikut:</p>
    <p><a href="{{ $verificationUrl }}">Lihat status pendaftaran (Terverifikasi)</a></p>
    <p>Atau langsung masuk sebagai penjual di: <a href="{{ url('/seller/login') }}">Masuk Penjual</a></p>
    <p>Terima kasih,</p>
    <p>Tim KoMa</p>
</body>
</html>
