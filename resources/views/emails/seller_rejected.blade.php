<!doctype html>
<html>
<body>
    <p>Halo {{ $seller->pic_name }},</p>
    <p>Terima kasih telah mendaftar sebagai penjual di KoMa. Setelah kami tinjau, pendaftaran Anda <strong>ditolak</strong>.</p>
    @if(!empty($reason))
    <p>Alasan: {{ $reason }}</p>
    @endif
    <p>Silakan perbaiki dokumen atau informasi Anda dan daftarkan kembali.</p>
    <p>Terima kasih,</p>
    <p>Tim KoMa</p>
</body>
</html>
