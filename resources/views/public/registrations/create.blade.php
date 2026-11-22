@extends('layouts.public')

@section('title', 'Formulir Pendaftaran Siswa — Bimbel Cendekia')
@section('meta_description', 'Lengkapi data siswa, data wali, dan persetujuan ketentuan pembayaran untuk mengunci kuota kelas.')

@section('content')
<div class="wrap">
  <div class="breadcrumb">
    <a href="{{ route('programs.index') }}">Program</a>
    <span aria-hidden="true">/</span>
    <a href="{{ route('programs.show', $classRoom->program) }}">{{ $classRoom->program->name }}</a>
    <span aria-hidden="true">/</span>
    <span>Formulir pendaftaran</span>
  </div>

  <ol class="steps-bar" aria-label="Tahapan pendaftaran">
    <li class="steps-bar__item is-current"><span class="mono">1.</span> Data siswa dan wali</li>
    <li class="steps-bar__item"><span class="mono">2.</span> Pembayaran lunas 24 jam</li>
    <li class="steps-bar__item"><span class="mono">3.</span> Bukti pendaftaran</li>
  </ol>

  <div class="grid grid--top">
    <div class="span-7">
      <div class="page-header" style="margin-bottom: var(--s-5); padding-bottom: var(--s-4);">
        <div>
          <h1>Formulir pendaftaran siswa</h1>
          <p>Kuota kelas akan dikunci otomatis selama 24 jam setelah formulir dikirim.</p>
        </div>
      </div>

      @error('class_id')
        <div class="alert alert--err" role="alert">{{ $message }}</div>
      @enderror

      <form id="registration-form" method="POST" action="{{ route('registrations.store') }}" class="form">
        @csrf
        <input type="hidden" name="class_id" value="{{ $classRoom->id }}">

        <p class="t-label muted field--full" style="border-bottom: 1px solid var(--line); padding-bottom: var(--s-2);">1. Data siswa</p>

        <div class="field field--full">
          <label for="student_full_name">Nama lengkap siswa</label>
          <input id="student_full_name" name="student_full_name" type="text" value="{{ old('student_full_name') }}" required @error('student_full_name') aria-invalid="true" aria-describedby="err-sname" @enderror>
          @error('student_full_name') <span id="err-sname" class="field__error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
          <label for="birth_date">Tanggal lahir</label>
          <input id="birth_date" name="birth_date" type="date" value="{{ old('birth_date') }}" required @error('birth_date') aria-invalid="true" aria-describedby="err-bdate" @enderror>
          @error('birth_date') <span id="err-bdate" class="field__error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
          <label for="school_name">Asal sekolah</label>
          <input id="school_name" name="school_name" type="text" value="{{ old('school_name') }}" required @error('school_name') aria-invalid="true" aria-describedby="err-school" @enderror>
          @error('school_name') <span id="err-school" class="field__error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
          <label for="education_level">Jenjang</label>
          <select id="education_level" name="education_level" required>
            @foreach (['SD', 'SMP', 'SMA', 'UTBK', 'UMUM'] as $lvl)
              <option value="{{ $lvl }}" @selected(old('education_level', $classRoom->program->education_level) === $lvl)>{{ $lvl }}</option>
            @endforeach
          </select>
          @error('education_level') <span class="field__error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
          <label for="grade">Kelas atau tingkat</label>
          <input id="grade" name="grade" type="text" value="{{ old('grade') }}" required @error('grade') aria-invalid="true" aria-describedby="err-grade" @enderror>
          @error('grade') <span id="err-grade" class="field__error">{{ $message }}</span> @enderror
        </div>

        <p class="t-label muted field--full" style="border-bottom: 1px solid var(--line); padding-bottom: var(--s-2); margin-top: var(--s-4);">2. Data orang tua atau wali</p>

        <div class="field">
          <label for="parent_name_display">Nama wali (dari akun)</label>
          <input id="parent_name_display" type="text" value="{{ $user->name }}" disabled style="background: var(--surface); color: var(--muted);">
        </div>

        <div class="field">
          <label for="parent_email_display">Email penerima bukti</label>
          <input id="parent_email_display" type="email" value="{{ $user->email }}" disabled style="background: var(--surface); color: var(--muted);">
        </div>

        <div class="field field--full">
          <label for="whatsapp">Nomor WhatsApp aktif</label>
          <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp', $user->whatsapp) }}" required @error('whatsapp') aria-invalid="true" aria-describedby="err-wa" @enderror>
          @error('whatsapp') <span id="err-wa" class="field__error">{{ $message }}</span> @enderror
        </div>

        <div class="field field--full surface-box" style="margin-top: var(--s-2);">
          <label class="check-row">
            <input type="checkbox" name="agree_terms" value="1" @checked(old('agree_terms')) required>
            <span>Saya menyatakan data di atas benar dan menyetujui ketentuan pendaftaran. Pembayaran wajib lunas di muka dalam 24 jam dan tidak dapat dikembalikan, kecuali jika kelas dibatalkan oleh pihak bimbel atau terjadi pembayaran ganda.</span>
          </label>
          @error('agree_terms') <span class="field__error">{{ $message }}</span> @enderror
        </div>

        <div class="btn-row" style="margin-top: var(--s-2);">
          <button type="submit" id="submit-registration-btn" class="btn btn--primary">Kirim dan lanjut ke pembayaran</button>
          <a href="{{ route('programs.show', $classRoom->program) }}" class="btn btn--secondary">Ubah kelas</a>
        </div>
      </form>
    </div>

    <aside class="span-4 start-9">
      <div class="surface-box stack-4">
        <p class="t-label muted">Ringkasan kelas terpilih</p>
        <div>
          <h2 style="font-size: var(--t-h3);">{{ $classRoom->program->name }}</h2>
          <p class="subline">{{ $classRoom->name }}</p>
        </div>
        <ul class="rows">
          <li><span class="rows__name">Jadwal</span><span class="rows__value">{{ $classRoom->schedule_days }}</span></li>
          <li><span class="rows__name">Waktu</span><span class="rows__value">{{ $classRoom->schedule_time }}</span></li>
          <li><span class="rows__name">Pengajar</span><span class="rows__value">{{ $classRoom->teacher_name }}</span></li>
          <li><span class="rows__name">Ruang</span><span class="rows__value">{{ $classRoom->room }}</span></li>
          <li><span class="rows__name">Sisa kuota</span><span class="rows__value">{{ $classRoom->remaining_quota }} dari {{ $classRoom->quota }}</span></li>
          <li><span class="rows__name">Total tagihan</span><span class="rows__value"><strong>Rp {{ number_format($classRoom->program->price, 0, ',', '.') }}</strong></span></li>
        </ul>
      </div>
    </aside>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('registration-form')?.addEventListener('submit', function () {
  const btn = document.getElementById('submit-registration-btn');
  if (btn) {
    btn.disabled = true;
    btn.textContent = 'Memproses pendaftaran...';
  }
});
</script>
@endpush
