@php
    $ctaGelombangAktif = \App\Models\Gelombang::aktif()->orderBy('urutan')->first();
@endphp
<section class="pt-0">
  <div class="container">
    <div class="cta-banner d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <h3 class="mb-2">{{ $ctaGelombangAktif ? "Kuota {$ctaGelombangAktif->nama} Terbatas!" : 'Pendaftaran Segera Dibuka' }}</h3>
        <p class="mb-0">Amankan tempatmu sekarang sebelum kuota jurusan favorit penuh.</p>
      </div>
      <a href="{{ route('formulir-pendaftaran.create') }}" class="btn btn-cta-dark">Daftar Sekarang <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
  </div>
</section>