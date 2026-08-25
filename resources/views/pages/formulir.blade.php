@extends('layouts.app')

@section('title', 'Isi Formulir Pendaftaran - SPMB SMK Muhammadiyah Lebaksiu')
@section('meta_description', 'Isi formulir pendaftaran SPMB SMK Muhammadiyah Lebaksiu tahun ajaran 2027/2028 secara online.')

@php
$stepFieldsMap = [
    1 => ['nama_lengkap', 'jenis_kelamin', 'agama', 'tempat_lahir', 'tanggal_lahir'],
    2 => ['asal_sekolah', 'nisn', 'nik', 'anak_ke'],
    3 => ['whatsapp_siswa', 'email_siswa', 'alamat_lengkap', 'desa_kelurahan', 'kecamatan', 'kabupaten'],
    4 => ['jurusan_id'],
    5 => ['nama_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'nama_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'whatsapp_ortu'],
    6 => ['punya_kip', 'nomor_kip', 'rekomendasi_guru_id'],
    7 => ['persetujuan_data'],
];
$startStep = 1;
if ($errors->any()) {
    foreach ($stepFieldsMap as $stepNum => $fields) {
        foreach ($fields as $f) {
            if ($errors->has($f)) {
                $startStep = $stepNum;
                break 2;
            }
        }
    }
}
$pendidikanOptions = [
    'tidak-sekolah' => 'Tidak Sekolah',
    'sd' => 'SD',
    'smp' => 'SMP',
    'sma-smk' => 'SMA/SMK',
    'd3' => 'D3',
    's1' => 'S1',
    's2' => 'S2',
    's3' => 'S3',
];
@endphp

@section('content')

<header class="page-hero">
  <div class="hero-shape s1"></div>
  <div class="hero-shape s2"></div>
  <div class="container">
    <nav class="breadcrumb-custom mb-2">
      <a href="{{ route('home') }}">Home</a> <i class="bi bi-chevron-right mx-1" style="font-size:.7rem;"></i> Isi Formulir
    </nav>
    <h1>Formulir Pendaftaran SPMB Tahun 2027/2028</h1>
    <p class="lead-text mb-0">SMK Muhammadiyah Lebaksiu &mdash; lengkapi data berikut secara bertahap.</p>
  </div>
</header>

<section>
  <div class="container">

    @if(session('error'))
      <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    @if(!$gelombangAktif)
      <div class="alert alert-warning">
        Mohon maaf, saat ini tidak ada gelombang pendaftaran yang sedang dibuka. Silakan pantau jadwal gelombang berikutnya di halaman utama.
      </div>
    @else

    <div class="wizard-shell">

      <div class="wizard-progress" id="wizardProgress">
        <div class="wizard-progress-fill" id="wizardProgressFill"></div>
        <div class="wizard-step-dot" data-dot="1">
          <div class="wizard-step-circle">1</div>
          <div class="wizard-step-label">Data Pribadi</div>
        </div>
        <div class="wizard-step-dot" data-dot="2">
          <div class="wizard-step-circle">2</div>
          <div class="wizard-step-label">Sekolah Asal</div>
        </div>
        <div class="wizard-step-dot" data-dot="3">
          <div class="wizard-step-circle">3</div>
          <div class="wizard-step-label">Kontak &amp; Alamat</div>
        </div>
        <div class="wizard-step-dot" data-dot="4">
          <div class="wizard-step-circle">4</div>
          <div class="wizard-step-label">Jurusan</div>
        </div>
        <div class="wizard-step-dot" data-dot="5">
          <div class="wizard-step-circle">5</div>
          <div class="wizard-step-label">Orang Tua</div>
        </div>
        <div class="wizard-step-dot" data-dot="6">
          <div class="wizard-step-circle">6</div>
          <div class="wizard-step-label">KIP &amp; Guru</div>
        </div>
        <div class="wizard-step-dot" data-dot="7">
          <div class="wizard-step-circle">7</div>
          <div class="wizard-step-label">Ringkasan</div>
        </div>
      </div>

      <form action="{{ route('formulir-pendaftaran.store') }}" method="POST" id="formulirSpmb" novalidate>
        @csrf
        <div class="wizard-panel">

          <div class="wizard-step" data-step="1">
            <div class="wizard-panel-title">Data Pribadi Calon Peserta Didik</div>
            <p class="wizard-panel-sub">Isi sesuai dokumen resmi (Akta Kelahiran / Kartu Keluarga).</p>

            <div class="row g-3">
              <div class="col-12">
                <label class="form-label-custom" for="namaLengkap">Nama Lengkap</label>
                <input type="text" class="form-control-custom @error('nama_lengkap') is-invalid @enderror" id="namaLengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Sesuai akta kelahiran" required>
                @error('nama_lengkap') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom">Jenis Kelamin</label>
                <div class="pill-toggle">
                  <input type="radio" id="jkLaki" name="jenis_kelamin" value="laki-laki" {{ old('jenis_kelamin') === 'laki-laki' ? 'checked' : '' }} required>
                  <label for="jkLaki"><i class="bi bi-gender-male me-1"></i>Laki-laki</label>
                  <input type="radio" id="jkPerempuan" name="jenis_kelamin" value="perempuan" {{ old('jenis_kelamin') === 'perempuan' ? 'checked' : '' }}>
                  <label for="jkPerempuan"><i class="bi bi-gender-female me-1"></i>Perempuan</label>
                </div>
                @error('jenis_kelamin') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom" for="agama">Agama</label>
                <select class="form-control-custom @error('agama') is-invalid @enderror" id="agama" name="agama" required>
                  <option value="" {{ old('agama') ? '' : 'selected' }} disabled>Pilih agama</option>
                  <option value="islam" {{ old('agama') === 'islam' ? 'selected' : '' }}>Islam</option>
                  <option value="kristen" {{ old('agama') === 'kristen' ? 'selected' : '' }}>Kristen</option>
                  <option value="katolik" {{ old('agama') === 'katolik' ? 'selected' : '' }}>Katolik</option>
                  <option value="hindu" {{ old('agama') === 'hindu' ? 'selected' : '' }}>Hindu</option>
                  <option value="buddha" {{ old('agama') === 'buddha' ? 'selected' : '' }}>Buddha</option>
                  <option value="konghucu" {{ old('agama') === 'konghucu' ? 'selected' : '' }}>Konghucu</option>
                  <option value="lainnya" {{ old('agama') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('agama') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom" for="tempatLahir">Tempat Lahir</label>
                <input type="text" class="form-control-custom @error('tempat_lahir') is-invalid @enderror" id="tempatLahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Contoh: Tegal" required>
                @error('tempat_lahir') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom" for="tanggalLahir">Tanggal Lahir</label>
                <input type="date" class="form-control-custom @error('tanggal_lahir') is-invalid @enderror" id="tanggalLahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                @error('tanggal_lahir') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>

          <div class="wizard-step" data-step="2">
            <div class="wizard-panel-title">Sekolah Asal &amp; Identitas</div>
            <p class="wizard-panel-sub">Data ini digunakan untuk verifikasi kelulusan dan data pokok pendidikan.</p>

            <div class="row g-3">
              <div class="col-12">
                <label class="form-label-custom" for="asalSekolah">Asal Sekolah</label>
                <input type="text" class="form-control-custom @error('asal_sekolah') is-invalid @enderror" id="asalSekolah" name="asal_sekolah" value="{{ old('asal_sekolah') }}" placeholder="Contoh: SMP Negeri 1 Lebaksiu" required>
                @error('asal_sekolah') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom" for="nisn">NISN</label>
                <input type="text" class="form-control-custom @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn') }}" placeholder="10 digit NISN" inputmode="numeric" maxlength="10" required>
                @error('nisn') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom" for="nik">NIK</label>
                <input type="text" class="form-control-custom @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" placeholder="16 digit NIK" inputmode="numeric" maxlength="16" required>
                @error('nik') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom" for="anakKe">Anak Ke</label>
                <input type="number" class="form-control-custom @error('anak_ke') is-invalid @enderror" id="anakKe" name="anak_ke" min="1" value="{{ old('anak_ke') }}" placeholder="Contoh: 1" required>
                @error('anak_ke') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>

          <div class="wizard-step" data-step="3">
            <div class="wizard-panel-title">Kontak &amp; Alamat</div>
            <p class="wizard-panel-sub">Informasi status pendaftaran akan dikirim ke WhatsApp dan email yang aktif.</p>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label-custom" for="whatsappSiswa">Nomor WhatsApp Aktif</label>
                <input type="tel" class="form-control-custom @error('whatsapp_siswa') is-invalid @enderror" id="whatsappSiswa" name="whatsapp_siswa" value="{{ old('whatsapp_siswa') }}" placeholder="08xxxxxxxxxx" required>
                @error('whatsapp_siswa') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom" for="emailSiswa">Email Aktif</label>
                <input type="email" class="form-control-custom @error('email_siswa') is-invalid @enderror" id="emailSiswa" name="email_siswa" value="{{ old('email_siswa') }}" placeholder="nama@email.com" required>
                @error('email_siswa') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-12">
                <label class="form-label-custom" for="alamatLengkap">Alamat Lengkap</label>
                <textarea class="form-control-custom @error('alamat_lengkap') is-invalid @enderror" id="alamatLengkap" name="alamat_lengkap" placeholder="Nama jalan, RT/RW, nomor rumah" required>{{ old('alamat_lengkap') }}</textarea>
                @error('alamat_lengkap') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-4">
                <label class="form-label-custom" for="desaKelurahan">Desa / Kelurahan</label>
                <input type="text" class="form-control-custom @error('desa_kelurahan') is-invalid @enderror" id="desaKelurahan" name="desa_kelurahan" value="{{ old('desa_kelurahan') }}" required>
                @error('desa_kelurahan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-4">
                <label class="form-label-custom" for="kecamatan">Kecamatan</label>
                <input type="text" class="form-control-custom @error('kecamatan') is-invalid @enderror" id="kecamatan" name="kecamatan" value="{{ old('kecamatan') }}" required>
                @error('kecamatan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-4">
                <label class="form-label-custom" for="kabupaten">Kabupaten</label>
                <input type="text" class="form-control-custom @error('kabupaten') is-invalid @enderror" id="kabupaten" name="kabupaten" value="{{ old('kabupaten') }}" required>
                @error('kabupaten') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>

          <div class="wizard-step" data-step="4">
            <div class="wizard-panel-title">Pilihan Kompetensi Keahlian</div>
            <p class="wizard-panel-sub">Pilih salah satu program keahlian yang paling diminati.</p>

            @error('jurusan_id') <div class="invalid-feedback d-block mb-2">{{ $message }}</div> @enderror

            <div id="jurusanSelectGroup">
              @foreach($jurusanList as $i => $jurusan)
              <div class="jurusan-select-card {{ (int) old('jurusan_id') === $jurusan->id ? 'selected' : '' }}" data-value="{{ $jurusan->id }}">
                <input type="radio" name="jurusan_id" value="{{ $jurusan->id }}" id="kompetensi{{ $i }}" class="d-none" {{ (int) old('jurusan_id') === $jurusan->id ? 'checked' : '' }} {{ $i === 0 ? 'required' : '' }}>
                <div class="jurusan-select-radio"></div>
                <div class="jurusan-select-icon"><i class="bi {{ $jurusan->icon }}"></i></div>
                <div class="jurusan-select-name">{{ $jurusan->nama }}</div>
              </div>
              @endforeach
            </div>
          </div>

          <div class="wizard-step" data-step="5">
            <div class="wizard-panel-title">Data Orang Tua / Wali</div>
            <p class="wizard-panel-sub">Lengkapi data ibu dan ayah kandung. Jika salah satu tidak ada, isi dengan data wali.</p>

            <div class="wizard-panel-group-title"><i class="bi bi-person-heart me-1"></i>Data Ibu</div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label-custom" for="namaIbu">Nama Ibu</label>
                <input type="text" class="form-control-custom @error('nama_ibu') is-invalid @enderror" id="namaIbu" name="nama_ibu" value="{{ old('nama_ibu') }}" required>
                @error('nama_ibu') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-3">
                <label class="form-label-custom" for="pendidikanIbu">Pendidikan Terakhir</label>
                <select class="form-control-custom @error('pendidikan_ibu') is-invalid @enderror" id="pendidikanIbu" name="pendidikan_ibu" required>
                  <option value="" {{ old('pendidikan_ibu') ? '' : 'selected' }} disabled>Pilih</option>
                  @foreach($pendidikanOptions as $val => $label)
                    <option value="{{ $val }}" {{ old('pendidikan_ibu') === $val ? 'selected' : '' }}>{{ $label }}</option>
                  @endforeach
                </select>
                @error('pendidikan_ibu') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-3">
                <label class="form-label-custom" for="pekerjaanIbu">Pekerjaan</label>
                <input type="text" class="form-control-custom @error('pekerjaan_ibu') is-invalid @enderror" id="pekerjaanIbu" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}" required>
                @error('pekerjaan_ibu') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="wizard-panel-group-title"><i class="bi bi-person-badge me-1"></i>Data Ayah</div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label-custom" for="namaAyah">Nama Ayah</label>
                <input type="text" class="form-control-custom @error('nama_ayah') is-invalid @enderror" id="namaAyah" name="nama_ayah" value="{{ old('nama_ayah') }}" required>
                @error('nama_ayah') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-3">
                <label class="form-label-custom" for="pendidikanAyah">Pendidikan Terakhir</label>
                <select class="form-control-custom @error('pendidikan_ayah') is-invalid @enderror" id="pendidikanAyah" name="pendidikan_ayah" required>
                  <option value="" {{ old('pendidikan_ayah') ? '' : 'selected' }} disabled>Pilih</option>
                  @foreach($pendidikanOptions as $val => $label)
                    <option value="{{ $val }}" {{ old('pendidikan_ayah') === $val ? 'selected' : '' }}>{{ $label }}</option>
                  @endforeach
                </select>
                @error('pendidikan_ayah') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-3">
                <label class="form-label-custom" for="pekerjaanAyah">Pekerjaan</label>
                <input type="text" class="form-control-custom @error('pekerjaan_ayah') is-invalid @enderror" id="pekerjaanAyah" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}" required>
                @error('pekerjaan_ayah') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="wizard-panel-group-title"><i class="bi bi-whatsapp me-1"></i>Kontak Orang Tua</div>
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label-custom" for="whatsappOrtu">Nomor WhatsApp Orang Tua (salah satu)</label>
                <input type="tel" class="form-control-custom @error('whatsapp_ortu') is-invalid @enderror" id="whatsappOrtu" name="whatsapp_ortu" value="{{ old('whatsapp_ortu') }}" placeholder="08xxxxxxxxxx" required>
                @error('whatsapp_ortu') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>

          <div class="wizard-step" data-step="6">
            <div class="wizard-panel-title">Data KIP &amp; Rekomendasi Guru</div>
            <p class="wizard-panel-sub">Dua bagian ini bersifat opsional dan tidak wajib diisi.</p>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label-custom">Memiliki KIP / KIP Kuliah / Bantuan?</label>
                <div class="pill-toggle">
                  <input type="radio" id="kipYa" name="punya_kip" value="ya" {{ old('punya_kip') === 'ya' ? 'checked' : '' }}>
                  <label for="kipYa">Ya</label>
                  <input type="radio" id="kipTidak" name="punya_kip" value="tidak" {{ old('punya_kip', 'tidak') === 'tidak' ? 'checked' : '' }}>
                  <label for="kipTidak">Tidak</label>
                </div>
              </div>
              <div class="col-md-6 {{ old('punya_kip') === 'ya' ? '' : 'd-none' }}" id="wrapNomorKip">
                <label class="form-label-custom" for="nomorKip">Nomor KIP</label>
                <input type="text" class="form-control-custom @error('nomor_kip') is-invalid @enderror" id="nomorKip" name="nomor_kip" value="{{ old('nomor_kip') }}" placeholder="Isi jika memiliki KIP" {{ old('punya_kip') === 'ya' ? 'required' : '' }}>
                @error('nomor_kip') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>

              <div class="col-12">
                <label class="form-label-custom" for="rekomendasiGuru">Rekomendasi Guru <span class="text-muted fw-normal">(opsional)</span></label>
                <select class="form-control-custom" id="rekomendasiGuru" name="rekomendasi_guru_id">
                  <option value="">Tidak ada rekomendasi</option>
                  @foreach($guruList as $guru)
                    <option value="{{ $guru->id }}" {{ (string) old('rekomendasi_guru_id') === (string) $guru->id ? 'selected' : '' }}>{{ $guru->nama }} &mdash; {{ $guru->mapel }}</option>
                  @endforeach
                </select>
                <p class="form-help-custom">Jika ada guru SD/SMP yang merekomendasikan sekolah ini, silakan pilih namanya di sini.</p>
              </div>
            </div>
          </div>

          <div class="wizard-step" data-step="7">
            <div class="wizard-panel-title">Ringkasan Pendaftaran</div>
            <p class="wizard-panel-sub">Periksa kembali data kamu sebelum mengirim pendaftaran.</p>

            <div id="summaryContent"></div>

            <div class="form-check mt-3">
              <input class="form-check-input @error('persetujuan_data') is-invalid @enderror" type="checkbox" id="persetujuanData" name="persetujuan_data" value="1" {{ old('persetujuan_data') ? 'checked' : '' }} required>
              <label class="form-check-label small text-muted" for="persetujuanData">
                Saya menyatakan bahwa seluruh data yang diisi sudah benar dan dapat dipertanggungjawabkan.
              </label>
              @error('persetujuan_data') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
          </div>

        </div>

        <div class="wizard-nav">
          <button type="button" class="btn btn-login" id="btnPrev" style="visibility:hidden;">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </button>
          <button type="button" class="btn btn-daftar" id="btnNext">
            Lanjut <i class="bi bi-arrow-right ms-1"></i>
          </button>
          <button type="submit" class="btn btn-daftar d-none" id="btnSubmit">
            <i class="bi bi-send-check-fill me-1"></i> Kirim Pendaftaran
          </button>
        </div>
      </form>
    </div>

    @endif
  </div>
</section>

@endsection

@push('scripts')
<script>
  (function () {
    var totalSteps = 7;
    var currentStep = {{ $startStep }};

    var steps = document.querySelectorAll('.wizard-step');
    if (steps.length === 0) return;

    var dots = document.querySelectorAll('.wizard-step-dot');
    var progressFill = document.getElementById('wizardProgressFill');
    var btnPrev = document.getElementById('btnPrev');
    var btnNext = document.getElementById('btnNext');
    var btnSubmit = document.getElementById('btnSubmit');

    function renderStep() {
      steps.forEach(function (step) {
        step.style.display = (parseInt(step.getAttribute('data-step')) === currentStep) ? 'block' : 'none';
      });

      dots.forEach(function (dot) {
        var n = parseInt(dot.getAttribute('data-dot'));
        dot.classList.remove('active', 'done');
        if (n === currentStep) dot.classList.add('active');
        if (n < currentStep) dot.classList.add('done');
      });

      progressFill.style.width = (((currentStep - 1) / (totalSteps - 1)) * 100) + '%';
      btnPrev.style.visibility = currentStep === 1 ? 'hidden' : 'visible';

      if (currentStep === totalSteps) {
        btnNext.classList.add('d-none');
        btnSubmit.classList.remove('d-none');
        buildSummary();
      } else {
        btnNext.classList.remove('d-none');
        btnSubmit.classList.add('d-none');
      }

      window.scrollTo({ top: document.querySelector('.wizard-shell').offsetTop - 100, behavior: 'smooth' });
    }

    function currentStepEl() {
      return document.querySelector('.wizard-step[data-step="' + currentStep + '"]');
    }

    function validateCurrentStep() {
      var inputs = currentStepEl().querySelectorAll('input, select, textarea');
      for (var i = 0; i < inputs.length; i++) {
        if (!inputs[i].checkValidity()) {
          inputs[i].reportValidity();
          return false;
        }
      }
      return true;
    }

    btnNext.addEventListener('click', function () {
      if (!validateCurrentStep()) return;
      if (currentStep < totalSteps) {
        currentStep++;
        renderStep();
      }
    });

    btnPrev.addEventListener('click', function () {
      if (currentStep > 1) {
        currentStep--;
        renderStep();
      }
    });

    dots.forEach(function (dot) {
      dot.addEventListener('click', function () {
        var n = parseInt(dot.getAttribute('data-dot'));
        if (n < currentStep) {
          currentStep = n;
          renderStep();
        }
      });
      dot.style.cursor = 'pointer';
    });

    document.querySelectorAll('.jurusan-select-card').forEach(function (card) {
      card.addEventListener('click', function () {
        document.querySelectorAll('.jurusan-select-card').forEach(function (c) { c.classList.remove('selected'); });
        card.classList.add('selected');
        card.querySelector('input[type=radio]').checked = true;
      });
    });

    var wrapNomorKip = document.getElementById('wrapNomorKip');
    var nomorKipInput = document.getElementById('nomorKip');
    document.querySelectorAll('input[name="punya_kip"]').forEach(function (radio) {
      radio.addEventListener('change', function () {
        var tampil = document.getElementById('kipYa').checked;
        wrapNomorKip.classList.toggle('d-none', !tampil);
        nomorKipInput.required = tampil;
        if (!tampil) nomorKipInput.value = '';
      });
    });

    function buildSummary() {
      var pendidikanLabel = {
        'tidak-sekolah': 'Tidak Sekolah', 'sd': 'SD', 'smp': 'SMP', 'sma-smk': 'SMA/SMK',
        'd3': 'D3', 's1': 'S1', 's2': 'S2', 's3': 'S3'
      };

      function val(id) {
        var el = document.getElementById(id);
        return el && el.value ? el.value : '-';
      }
      function row(label, value) {
        return '<div class="summary-row"><span class="label">' + label + '</span><span class="value">' + (value || '-') + '</span></div>';
      }

      var jenisKelamin = document.getElementById('jkLaki').checked ? 'Laki-laki' : (document.getElementById('jkPerempuan').checked ? 'Perempuan' : '-');
      var jurusanSelected = document.querySelector('.jurusan-select-card.selected .jurusan-select-name');
      var punyaKip = document.getElementById('kipYa').checked ? 'Ya' : 'Tidak';

      var html = '';

      html += '<div class="summary-group"><div class="summary-group-title">Data Pribadi</div>';
      html += row('Nama Lengkap', val('namaLengkap'));
      html += row('Jenis Kelamin', jenisKelamin);
      html += row('Tempat, Tanggal Lahir', val('tempatLahir') + ', ' + val('tanggalLahir'));
      html += row('Agama', val('agama'));
      html += '</div>';

      html += '<div class="summary-group"><div class="summary-group-title">Sekolah Asal &amp; Identitas</div>';
      html += row('Asal Sekolah', val('asalSekolah'));
      html += row('NISN', val('nisn'));
      html += row('NIK', val('nik'));
      html += row('Anak Ke', val('anakKe'));
      html += '</div>';

      html += '<div class="summary-group"><div class="summary-group-title">Kontak &amp; Alamat</div>';
      html += row('WhatsApp', val('whatsappSiswa'));
      html += row('Email', val('emailSiswa'));
      html += row('Alamat', val('alamatLengkap'));
      html += row('Desa/Kecamatan/Kabupaten', val('desaKelurahan') + ' / ' + val('kecamatan') + ' / ' + val('kabupaten'));
      html += '</div>';

      html += '<div class="summary-group"><div class="summary-group-title">Kompetensi Keahlian</div>';
      html += row('Pilihan Jurusan', jurusanSelected ? jurusanSelected.textContent.trim() : '-');
      html += '</div>';

      html += '<div class="summary-group"><div class="summary-group-title">Data Orang Tua / Wali</div>';
      html += row('Nama Ibu', val('namaIbu') + ' (' + (pendidikanLabel[val('pendidikanIbu')] || '-') + ', ' + val('pekerjaanIbu') + ')');
      html += row('Nama Ayah', val('namaAyah') + ' (' + (pendidikanLabel[val('pendidikanAyah')] || '-') + ', ' + val('pekerjaanAyah') + ')');
      html += row('WhatsApp Orang Tua', val('whatsappOrtu'));
      html += '</div>';

      html += '<div class="summary-group"><div class="summary-group-title">KIP &amp; Rekomendasi Guru</div>';
      html += row('Memiliki KIP', punyaKip);
      if (punyaKip === 'Ya') html += row('Nomor KIP', val('nomorKip'));
      var guruSelect = document.getElementById('rekomendasiGuru');
      html += row('Rekomendasi Guru', guruSelect.options[guruSelect.selectedIndex].text || 'Tidak ada rekomendasi');
      html += '</div>';

      document.getElementById('summaryContent').innerHTML = html;
    }

    renderStep();
  })();
</script>
@endpush