@extends('layouts.app')

@section('title', 'Kontak - SPMB SMK Muhammadiyah Lebaksiu')
@section('meta_description', 'Hubungi panitia SPMB SMK Muhammadiyah Lebaksiu melalui telepon, WhatsApp, email, atau kunjungi langsung lokasi sekolah.')

@section('content')

<header class="page-hero">
  <div class="hero-shape s1"></div>
  <div class="hero-shape s2"></div>
  <div class="container">
    <nav class="breadcrumb-custom mb-2">
      <a href="{{ route('home') }}">Home</a> <i class="bi bi-chevron-right mx-1" style="font-size:.7rem;"></i> Kontak
    </nav>
    <h1>Hubungi Kami</h1>
    <p class="lead-text mb-0">Punya pertanyaan seputar SPMB? Tim panitia kami siap membantu kamu.</p>
  </div>
</header>

<section>
  <div class="container">
    <div class="text-center mb-5">
      <div class="eyebrow mb-2">Hubungi Kami</div>
      <h2 class="section-title">Ada Pertanyaan? Kami Siap Membantu</h2>
      <p class="section-sub mx-auto">Silakan hubungi panitia SPMB melalui salah satu kanal di bawah ini, atau kunjungi langsung sekolah kami.</p>
    </div>
    <div class="row g-4">
      @foreach($kontakInfoList as $info)
      <div class="col-md-6 col-lg-3">
        <div class="contact-info-card">
          <div class="contact-info-icon"><i class="bi {{ $info['icon'] }}"></i></div>
          <div>
            <div class="contact-info-title">{{ $info['title'] }}</div>
            <p class="contact-info-value">{!! $info['value'] !!}</p>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="pt-0">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-6">
        <div class="map-wrap">
          <iframe
            src="https://www.google.com/maps?q=SMK+Muhammadiyah+Lebaksiu+Tegal&output=embed"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Lokasi SMK Muhammadiyah Lebaksiu">
          </iframe>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="contact-form-card">
          <h5 class="fw-bold mb-1" style="color:var(--blue-900);">Kirim Pesan</h5>
          <p class="text-muted small mb-4">Isi formulir berikut, tim kami akan membalas secepatnya.</p>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <form action="{{ route('kontak.store') }}" method="POST">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label-custom" for="namaKontak">Nama Lengkap</label>
                <input type="text" class="form-control-custom @error('nama') is-invalid @enderror" id="namaKontak" name="nama" value="{{ old('nama') }}" placeholder="Nama kamu" required>
                @error('nama') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom" for="hpKontak">No. WhatsApp</label>
                <input type="tel" class="form-control-custom @error('whatsapp') is-invalid @enderror" id="hpKontak" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="08xxxxxxxxxx" required>
                @error('whatsapp') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-12">
                <label class="form-label-custom" for="emailKontak">Email</label>
                <input type="email" class="form-control-custom @error('email') is-invalid @enderror" id="emailKontak" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-12">
                <label class="form-label-custom" for="subjekKontak">Subjek</label>
                <select class="form-control-custom @error('subjek') is-invalid @enderror" id="subjekKontak" name="subjek" required>
                  <option value="" {{ old('subjek') ? '' : 'selected' }} disabled>Pilih topik pertanyaan</option>
                  <option value="pendaftaran" {{ old('subjek') === 'pendaftaran' ? 'selected' : '' }}>Pendaftaran &amp; Gelombang</option>
                  <option value="biaya" {{ old('subjek') === 'biaya' ? 'selected' : '' }}>Biaya &amp; Pembayaran</option>
                  <option value="jurusan" {{ old('subjek') === 'jurusan' ? 'selected' : '' }}>Program Keahlian</option>
                  <option value="lainnya" {{ old('subjek') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('subjek') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-12">
                <label class="form-label-custom" for="pesanKontak">Pesan</label>
                <textarea class="form-control-custom @error('pesan') is-invalid @enderror" id="pesanKontak" name="pesan" placeholder="Tulis pertanyaan atau pesan kamu di sini...">{{ old('pesan') }}</textarea>
                @error('pesan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-daftar w-100">
                  <i class="bi bi-send-fill me-1"></i> Kirim Pesan
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="pt-0">
  <div class="container">
    <div class="whatsapp-cta">
      <div class="d-flex align-items-center gap-3">
        <div class="whatsapp-cta-icon"><i class="bi bi-whatsapp"></i></div>
        <div>
          <h4 class="mb-1">Lebih Suka Chat Langsung?</h4>
          <p>Tim panitia SPMB siap membantu via WhatsApp setiap hari kerja.</p>
        </div>
      </div>
      <a href="https://wa.me/{{ $whatsappCs }}" target="_blank" rel="noopener" class="btn btn-daftar">
        <i class="bi bi-whatsapp me-1"></i> Chat Sekarang
      </a>
    </div>
  </div>
</section>

@endsection