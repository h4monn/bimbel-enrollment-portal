<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pendaftaran</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <h2>Pendaftaran Berhasil Dikonfirmasi</h2>
    <p>Halo <strong>{{ $registration->user->name }}</strong>,</p>
    <p>Pembayaran Anda telah kami terima dan pendaftaran siswa telah resmi <strong>Dikonfirmasi</strong>.</p>

    <table style="border-collapse: collapse; width: 100%; max-width: 520px; margin: 16px 0;">
        <tr>
            <td style="padding: 6px 0; color: #6b7280;">Nomor Pendaftaran</td>
            <td style="padding: 6px 0;"><strong>{{ $registration->registration_number }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #6b7280;">Nama Siswa</td>
            <td style="padding: 6px 0;">{{ $registration->student->full_name }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #6b7280;">Program & Kelas</td>
            <td style="padding: 6px 0;">{{ $registration->classRoom->program->name }} — {{ $registration->classRoom->name }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #6b7280;">Jadwal & Ruang</td>
            <td style="padding: 6px 0;">{{ $registration->classRoom->schedule_days }}, {{ $registration->classRoom->schedule_time }} (Ruang {{ $registration->classRoom->room }})</td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #6b7280;">Total Dibayar</td>
            <td style="padding: 6px 0;"><strong>Rp {{ number_format($registration->total_amount, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <p>Anda dapat melihat status dan bukti pembayaran kapan saja melalui dashboard akun Anda.</p>
    <p>Terima kasih telah mendaftar.</p>
</body>
</html>
