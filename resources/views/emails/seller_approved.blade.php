<!doctype html>
<html>
<body>
    <p>Halo {{ $seller->pic_name }},</p>
    <p>Selamat — pendaftaran toko <strong>{{ $seller->store_name }}</strong> telah diverifikasi dan akun Anda telah diaktifkan.</p>
    <p>Anda sekarang dapat masuk menggunakan email ini: <strong>{{ $seller->pic_email }}</strong>.</p>
    <p>Terima kasih,</p>
    <p>Tim KoMa</p>
</body>
</html>
