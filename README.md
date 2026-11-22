# Bimbel Enrollment Portal

A responsive tutoring center registration portal built with **Laravel 13**, featuring real-time class quota tracking, atomic seat reservation (`lockForUpdate`), Midtrans Snap payment integration, automated WhatsApp & Email notifications, and an operational Admin Dashboard.

## Features

### Public Portal (Parents / Guardians & Students)
* **Landing Page & Program Catalog:** Interactive level tabs on the landing page, dedicated 4-step enrollment flow, catalog level filtering (`SD`, `SMP`, `SMA`, `UTBK`, `UMUM`), and real-time class details (schedule, instructor, room, and remaining seats).
* **Flexible Authentication:** Email/Password registration & login (instantly active with password reset support) alongside **Google OAuth** via Laravel Socialite.
* **Enrollment Form & Non-Refund Policy:** Student and guardian data collection, selected class summary, and explicit agreement to terms and the non-refundable payment policy (with exceptions for class cancellation by the center or duplicate payment).
* **24-Hour Midtrans Snap Checkout:** Full upfront payment supporting Virtual Accounts, QRIS, and E-Wallets with a live 24-hour countdown timer.
* **Confirmation & Registration History:** Instant confirmation page (with one-click registration number copy) and a personal dashboard to track registration statuses and payment receipts.

### Admin Dashboard
* **Dedicated Admin Authentication:** Supports single-method authentication (`password` or `google` via `ADMIN_AUTH_METHOD`) with role-based access control (**Super Admin** and **Staff Admin**).
* **Operational Overview:** KPI summary cards (total registrations, confirmed enrollments, paid revenue, nearly-full classes) and a live class quota table with progress bars.
* **Program & Class Management:** Full CRUD for programs, classes, schedules, rooms, instructors, and seat capacities.
* **Registration & Payment Management:** Search, multi-criteria filtering (program, status, date range), manual payment verification, status updates (including refund exception notes), and **CSV / Excel (`.xlsx`)** export.

## Technical Highlights
* **Concurrency-Safe Quota Reservation:** Uses `DB::transaction()` with `lockForUpdate()` during checkout to prevent overbooking under concurrent requests.
* **Secure & Idempotent Midtrans Webhook:** Validates SHA-512 `signature_key`, enforces `order_id` idempotency, and automatically flags duplicate payments for admin refund review.
* **Automated 24-Hour Expiration:** Runs `registrations:expire-pending` every 5 minutes via Laravel Scheduler to cancel unpaid registrations and atomically release reserved seats.
* **Queued Notifications:** Dispatches confirmation Emails (Resend/SMTP) and WhatsApp messages (Fonnte API) asynchronously after payment settlement.

## Tech Stack
* **Backend:** Laravel 13 (PHP 8.4), Eloquent ORM, Queue, Task Scheduler
* **Frontend:** Laravel Blade, custom modular CSS (`public/assets/`), `Instrument Sans`, vanilla ES modules
* **Database:** MySQL (recommended for production) / SQLite (local testing)
* **Integrations:** Laravel Socialite, Midtrans PHP SDK, Maatwebsite Excel, Fonnte WhatsApp API, Resend / SMTP

## Local Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/h4monn/bimbel-enrollment-portal.git
   cd bimbel-enrollment-portal
   ```

2. **Install PHP & frontend dependencies:**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Configure environment variables:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Update `.env` with your database (`mysql` or `sqlite`) and third-party credentials:
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

4. **Run database migrations and seed initial data:**
   ```bash
   php artisan migrate --seed
   ```
   This seeds the sample programs/classes and the default Super Admin account:
   * **Email:** `admin@bimbel.test`
   * **Password:** `password123`

5. **Start the local server, queue worker, and scheduler:**
   ```bash
   php artisan serve
   php artisan queue:work
   php artisan schedule:work
   ```

## Key Endpoints
* **Public Portal:** `/` and `/program`
* **User Auth:** `/login`, `/register`
* **Admin Portal:** `/admin/login` and `/admin`
* **Midtrans Webhook:** `POST /api/webhooks/midtrans`
