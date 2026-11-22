@extends('layouts.admin')

@section('title', 'Pendaftar dan Pembayaran')
@section('page_title', 'Data pendaftar dan pembayaran')

@section('content')
<form method="GET" action="{{ route('admin.registrations.index') }}" class="surface-box stack-4">
  <div class="admin-filter-grid">
    <div class="field">
      <label for="search">Cari nomor, nama, atau WhatsApp</label>
      <input id="search" name="search" type="text" value="{{ $filters['search'] ?? '' }}">
    </div>
    <div class="field">
      <label for="program_id">Program</label>
      <select id="program_id" name="program_id">
        <option value="">Semua program</option>
        @foreach ($programs as $program)
          <option value="{{ $program->id }}" @selected(($filters['program_id'] ?? '') == $program->id)>{{ $program->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="field">
      <label for="status">Status</label>
      <select id="status" name="status">
        <option value="">Semua status</option>
        @foreach (['pending_payment' => 'Menunggu pembayaran', 'paid' => 'Lunas', 'confirmed' => 'Dikonfirmasi', 'cancelled' => 'Dibatalkan', 'refunded' => 'Refund'] as $key => $label)
          <option value="{{ $key }}" @selected(($filters['status'] ?? '') === $key)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
    <div class="field">
      <label for="date_from">Dari tanggal</label>
      <input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? '' }}">
    </div>
    <div class="field">
      <label for="date_to">Sampai tanggal</label>
      <input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? '' }}">
    </div>
  </div>

  <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:var(--s-3);">
    <div class="btn-row">
      <button type="submit" class="btn btn--primary btn--sm">Terapkan saringan</button>
      <a href="{{ route('admin.registrations.index') }}" class="btn btn--secondary btn--sm">Atur ulang</a>
    </div>
    <div class="btn-row">
      <a href="{{ route('admin.registrations.export', array_merge($filters, ['format' => 'csv'])) }}" class="btn btn--secondary btn--sm">Ekspor CSV</a>
      <a href="{{ route('admin.registrations.export', array_merge($filters, ['format' => 'xlsx'])) }}" class="btn btn--primary btn--sm">Ekspor Excel</a>
    </div>
  </div>
</form>

<div class="table-wrap">
  <table class="data-table">
    <thead>
      <tr>
        <th>Nomor</th>
        <th>Siswa dan wali</th>
        <th>Program dan kelas</th>
        <th class="num">Tagihan</th>
        <th>Status pembayaran</th>
        <th>Ubah status</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($registrations as $reg)
        @php
            $statusMap = [
                'pending_payment' => ['label' => 'Menunggu bayar', 'class' => 'tag--warn'],
                'paid' => ['label' => 'Lunas', 'class' => 'tag--ok'],
                'confirmed' => ['label' => 'Dikonfirmasi', 'class' => 'tag--ok'],
                'cancelled' => ['label' => 'Dibatalkan', 'class' => 'tag--err'],
                'refunded' => ['label' => 'Refund', 'class' => 'tag--err'],
            ];
            $st = $statusMap[$reg->status] ?? ['label' => $reg->status, 'class' => ''];
        @endphp
        <tr>
          <td>
            <span class="mono">{{ $reg->registration_number }}</span>
            <span class="subline">{{ $reg->created_at?->format('d/m/Y H.i') }}</span>
          </td>
          <td>
            <strong>{{ $reg->student?->full_name }}</strong>
            <span class="subline">{{ $reg->student?->school_name }} ({{ $reg->student?->grade }})</span>
            <span class="subline">Wali: {{ $reg->user?->name }} &middot; {{ $reg->user?->whatsapp }}</span>
          </td>
          <td>
            <strong>{{ $reg->classRoom?->program?->name }}</strong>
            <span class="subline">{{ $reg->classRoom?->name }}</span>
          </td>
          <td class="num"><strong>Rp {{ number_format($reg->total_amount, 0, ',', '.') }}</strong></td>
          <td>
            <span class="tag {{ $st['class'] }}">{{ $st['label'] }}</span>
            <span class="subline">Gateway: {{ $reg->latestPayment?->status ?? '-' }}</span>
            @if ($reg->latestPayment?->refund_notes)
              <span class="subline">Catatan: {{ $reg->latestPayment->refund_notes }}</span>
            @endif
            @if (in_array($reg->status, ['pending_payment', 'cancelled'], true))
              <form method="POST" action="{{ route('admin.registrations.verify-payment', $reg) }}" style="margin-top:var(--s-2);" onsubmit="return confirm('Verifikasi pembayaran manual dan konfirmasi pendaftaran ini?')">
                @csrf
                <button type="submit" class="btn btn--primary btn--sm">Verifikasi manual</button>
              </form>
            @endif
          </td>
          <td>
            <form method="POST" action="{{ route('admin.registrations.update-status', $reg) }}" style="display:grid;gap:var(--s-2);min-width:190px;">
              @csrf
              @method('PATCH')
              <select name="status" aria-label="Status pendaftaran" style="height:36px;padding:0 8px;border:1px solid var(--line-strong);border-radius:2px;background:var(--bg);font-size:var(--t-small);">
                @foreach (['pending_payment' => 'Menunggu bayar', 'paid' => 'Lunas', 'confirmed' => 'Dikonfirmasi', 'cancelled' => 'Dibatalkan', 'refunded' => 'Refund'] as $key => $label)
                  <option value="{{ $key }}" @selected($reg->status === $key)>{{ $label }}</option>
                @endforeach
              </select>
              <input type="text" name="refund_notes" placeholder="Catatan refund" style="height:36px;padding:0 8px;border:1px solid var(--line-strong);border-radius:2px;background:var(--bg);font-size:var(--t-small);">
              <button type="submit" class="btn btn--secondary btn--sm">Simpan status</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6">
            <p class="muted">Tidak ada data pendaftar yang cocok dengan saringan.</p>
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@if ($registrations->hasPages())
  <div>{{ $registrations->links() }}</div>
@endif
@endsection
