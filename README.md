# Website Pendaftaran Bimbel

Aplikasi web *mobile-first* untuk pendaftaran bimbingan belajar tatap muka secara online—mulai dari eksplorasi katalog program, pemilihan kelas & jadwal dengan kuota *real-time*, reservasi kuota *atomic*, hingga pembayaran lunas otomatis via Midtrans dan pengelolaan operasional di Dashboard Admin.

## Fitur Utama

### Sisi Publik (Wali / Siswa)
* **Landing Page & Katalog Program:** Profil bimbel, alur pendaftaran 4 langkah, filter program berdasarkan jenjang (`SD`, `SMP`, `SMA`, `UTBK`, `UMUM`), serta detail kelas (jadwal, pengajar, ruang, dan sisa kuota *real-time*).
* **Autentikasi Fleksibel:** Registrasi dan login dengan Email/Password (akun langsung aktif, dilengkapi *Reset Password*) serta login **Google OAuth** via Laravel Socialite.
* **Formulir Pendaftaran & Kebijakan Refund:** Input data siswa dan wali, ringkasan kelas terpilih, serta persetujuan syarat ketentuan dan kebijakan *non-refund* (kecuali kelas dibatalkan bimbel atau terjadi pembayaran ganda).
* **Pembayaran Lunas 24 Jam (Midtrans Snap):** Mendukung Virtual Account, QRIS, dan E-Wallet dengan *countdown timer* batas waktu bayar 24 jam.
* **Riwayat & Bukti Pendaftaran:** Halaman konfirmasi sukses (dengan tombol salin nomor pendaftaran) serta dashboard riwayat & status pendaftaran pengguna.

### Sisi Admin
* **Portal Admin Terpisah:** Mendukung autentikasi tunggal (`password` atau `google` via `ADMIN_AUTH_METHOD`) dengan pemisahan peran **Super Admin** dan **Staf Admin**.
* **Dashboard Ringkas:** Metrik total pendaftar, pendapatan lunas, kelas hampir/sudah penuh, dan tabel sisa kuota per kelas.
* **Manajemen Program & Kelas:** CRUD program, kelas, jadwal, ruang, pengajar, dan kapasitas kuota.
* **Manajemen Pendaftar & Pembayaran:** Pencarian, filter (program, status, rentang tanggal), verifikasi pembayaran manual, ubah status (termasuk pencatatan pengecualian *refund*), dan ekspor data ke **CSV / Excel (`.xlsx`)**.

## Highlight Teknis
* **Anti-Overbooking (`lockForUpdate`):** Penguncian baris kelas di dalam transaksi database (`DB::transaction`) saat *checkout* untuk mencegah *double booking* kuota.
* **Webhook Midtrans Aman & Idempoten:** Validasi SHA-512 `signature_key`, *idempotency check* berbasis `order_id`, dan deteksi otomatis kasus pembayaran ganda.
* **Auto-Expire 24 Jam:** Command `registrations:expire-pending` yang dijadwalkan setiap 5 menit melalui Laravel Scheduler untuk membatalkan tagihan kedaluwarsa dan mengembalikan kuota kelas secara *atomic*.
* **Notifikasi Antrean (Queue Jobs):** Pengiriman konfirmasi pendaftaran otomatis via Email (Resend/SMTP) dan WhatsApp (Fonnte API) setelah pembayaran lunas.

## Tech Stack
* **Backend:** Laravel 13 (PHP 8.4), Eloquent ORM, Queue, Task Scheduler
* **Frontend:** Laravel Blade, Tailwind CSS v4, Vite
* **Database:** MySQL (disarankan untuk produksi) / SQLite (pengujian lokal)
* **Integrasi:** Laravel Socialite (Google OAuth), Midtrans PHP SDK, Maatwebsite Excel, Fonnte WhatsApp API, Resend / SMTP

## Instalasi Lokal

1. **Clone repository & masuk ke direktori proyek:**
   ```bash
   git clone <url-repository-anda>
   cd website-pendaftaran-bimbel
   ```

2. **Install dependency PHP & JavaScript:**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Salin file environment & generate application key:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi `.env`:**
   Atur koneksi database (`DB_CONNECTION=mysql` atau `sqlite`), serta kredensial layanan pihak ketiga:
   ```env
   ADMIN_AUTH_METHOD=password

   GOOGLE_CLIENT_ID=
   GOOGLE_CLIENT_SECRET=
   GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
   GOOGLE_ADMIN_REDIRECT_URI="${APP_URL}/admin/auth/google/callback"

   MIDTRANS_SERVER_KEY=
   MIDTRANS_CLIENT_KEY=
   MIDTRANS_IS_PRODUCTION=false

   FONNTE_TOKEN=
   ```

5. **Jalankan database migration:**
   ```bash
   php artisan migrate
   ```

6. **Buat akun Super Admin awal (via Tinker):**
   ```bash
   php artisan tinker
   ```
   ```php
   \App\Models\Admin::create([
       'name' => 'Super Admin',
       'email' => 'admin@bimbel.test',
       'password' => 'password123',
       'role' => 'super_admin',
       'is_active' => true,
   ]);
   ```

7. **Jalankan server pengembangan, queue worker, dan scheduler:**
   ```bash
   php artisan serve
   php artisan queue:work
   php artisan schedule:work
   ```

## Endpoint Penting
* Halaman Publik: `/` & `/program`
* Autentikasi Pendaftar: `/login`, `/register`
* Portal Admin: `/admin/login` & `/admin`
* Webhook Midtrans: `POST /api/webhooks/midtrans`
