@extends('layouts.app')

@section('title', 'Konfirmasi Transfer - SPMB SMK Muhammadiyah Lebaksiu')
@section('meta_description', 'Unggah bukti transfer biaya pendaftaran SPMB SMK Muhammadiyah Lebaksiu secara online untuk diverifikasi panitia.')

@section('content')

<header class="page-hero">
  <div class="hero-shape s1"></div>
  <div class="hero-shape s2"></div>
  <div class="container">
    <nav class="breadcrumb-custom mb-2">
      <a href="{{ route('home') }}">Home</a> <i class="bi bi-chevron-right mx-1" style="font-size:.7rem;"></i> Konfirmasi Transfer
    </nav>
    <h1>Konfirmasi Transfer</h1>
    <p class="lead-text mb-0">Sudah melakukan pembayaran? Unggah bukti transfer di sini agar segera diverifikasi oleh panitia.</p>
  </div>
</header>

<section>
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4">
        <div class="eyebrow mb-2">Panduan Singkat</div>
        <h2 class="section-title mb-3">4 Langkah Konfirmasi Transfer</h2>
        <p class="section-sub">Pastikan nominal transfer sesuai dan bukti transfer terlihat jelas agar proses verifikasi lebih cepat.</p>
      </div>
      <div class="col-lg-8">
        <div class="row g-4">
          <div class="col-md-6">
            <div class="step-mini">
              <div class="step-mini-num">1</div>
              <div>
                <div class="step-mini-title">Transfer Sesuai Nominal</div>
                <p class="step-mini-desc">Transfer biaya pendaftaran ke salah satu rekening resmi sekolah di bawah ini.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="step-mini">
              <div class="step-mini-num">2</div>
              <div>
                <div class="step-mini-title">Simpan Bukti Transfer</div>
                <p class="step-mini-desc">Screenshot atau foto struk/bukti transfer dengan jelas dan tidak terpotong.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="step-mini">
              <div class="step-mini-num">3</div>
              <div>
                <div class="step-mini-title">Isi Formulir di Bawah</div>
                <p class="step-mini-desc">Lengkapi data diri dan unggah bukti transfer pada form konfirmasi.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="step-mini">
              <div class="step-mini-num">4</div>
              <div>
                <div class="step-mini-title">Tunggu Verifikasi</div>
                <p class="step-mini-desc">Panitia akan memverifikasi maksimal 2x24 jam kerja setelah bukti diunggah.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="pt-0" style="background:#fff;">
  <div class="container">
    <div class="nominal-banner mb-4">
      <div>
        <div class="label">Nominal Transfer &middot; {{ $gelombangAktif->nama ?? 'Belum Ada Gelombang Aktif' }}</div>
        <div class="value">
          @if($gelombangAktif)
            Rp{{ number_format($gelombangAktif->harga_formulir, 0, ',', '.') }}
          @else
            -
          @endif
        </div>
      </div>
    </div>

    <h5 class="fw-bold mb-3" style="color:var(--blue-900);">Rekening Resmi Sekolah</h5>
    <div class="row g-3 mb-2">
      @foreach($rekeningList as $i => $b)
      <div class="col-md-4">
        <div class="bank-card">
          <div class="bank-card-logo">{{ strtoupper(substr($b->nama_bank, 0, 3)) }}</div>
          <div class="fw-semibold small text-muted mb-1">{{ $b->nama_bank }}</div>
          <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
            <span class="bank-norek" id="norek{{ $i }}">{{ $b->no_rekening }}</span>
            <button type="button" class="btn-copy" data-copy-target="norek{{ $i }}">
              <i class="bi bi-clipboard me-1"></i>Salin
            </button>
          </div>
          <div class="text-muted small">a.n. {{ $b->atas_nama }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="contact-form-card">
          <h5 class="fw-bold mb-1" style="color:var(--blue-900);">Formulir Konfirmasi Transfer</h5>
          <p class="text-muted small mb-4">Lengkapi data di bawah ini sesuai bukti transfer yang kamu miliki.</p>

          <form action="{{ route('konfirmasi-transfer.store') }}" method="POST" enctype="multipart/form-data" id="formKonfirmasi">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label-custom" for="noPendaftaran">Nomor Pendaftaran</label>
                <input type="text" class="form-control-custom @error('no_pendaftaran') is-invalid @enderror" id="noPendaftaran" name="no_pendaftaran" value="{{ old('no_pendaftaran') }}" placeholder="Contoh: SPMB-2026-001" required>
                @error('no_pendaftaran') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom" for="namaSiswa">Nama Calon Siswa</label>
                <input type="text" class="form-control-custom @error('nama_siswa') is-invalid @enderror" id="namaSiswa" name="nama_siswa" value="{{ old('nama_siswa') }}" placeholder="Nama lengkap sesuai formulir" required>
                @error('nama_siswa') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom" for="rekeningId">Dikirim ke Rekening</label>
                <select class="form-control-custom @error('rekening_id') is-invalid @enderror" id="rekeningId" name="rekening_id" required>
                  <option value="" {{ old('rekening_id') ? '' : 'selected' }} disabled>Pilih rekening tujuan</option>
                  @foreach($rekeningList as $rek)
                    <option value="{{ $rek->id }}" {{ (string) old('rekening_id') === (string) $rek->id ? 'selected' : '' }}>{{ $rek->nama_bank }} &mdash; {{ $rek->no_rekening }}</option>
                  @endforeach
                </select>
                @error('rekening_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom" for="bankPengirim">Transfer Dari Bank</label>
                <select class="form-control-custom @error('bank_pengirim') is-invalid @enderror" id="bankPengirim" name="bank_pengirim" required>
                  <option value="" {{ old('bank_pengirim') ? '' : 'selected' }} disabled>Pilih bank pengirim</option>
                  <option value="jateng" {{ old('bank_pengirim') === 'jateng' ? 'selected' : '' }}>Bank Jateng</option>
                  <option value="bri" {{ old('bank_pengirim') === 'bri' ? 'selected' : '' }}>BRI</option>
                  <option value="bsi" {{ old('bank_pengirim') === 'bsi' ? 'selected' : '' }}>BSI</option>
                  <option value="bca" {{ old('bank_pengirim') === 'bca' ? 'selected' : '' }}>BCA</option>
                  <option value="mandiri" {{ old('bank_pengirim') === 'mandiri' ? 'selected' : '' }}>Mandiri</option>
                  <option value="lainnya" {{ old('bank_pengirim') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('bank_pengirim') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom" for="tanggalTransfer">Tanggal Transfer</label>
                <input type="date" class="form-control-custom @error('tanggal_transfer') is-invalid @enderror" id="tanggalTransfer" name="tanggal_transfer" value="{{ old('tanggal_transfer', now()->format('Y-m-d')) }}" required>
                @error('tanggal_transfer') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label-custom" for="nominalTransfer">Nominal Transfer</label>
                <input type="text" class="form-control-custom @error('nominal_transfer') is-invalid @enderror" id="nominalTransfer" name="nominal_transfer" value="{{ old('nominal_transfer') }}" placeholder="Contoh: 200000" inputmode="numeric" required>
                @error('nominal_transfer') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>

              <div class="col-12">
                <label class="form-label-custom">Bukti Transfer</label>

                <div class="upload-dropzone @error('bukti_transfer') is-invalid @enderror" id="dropzone">
                  <i class="bi bi-cloud-arrow-up"></i>
                  <div class="dz-text">Klik untuk pilih file, atau seret file ke sini</div>
                  <div class="dz-sub">Format JPG atau PNG &middot; Maks. 2MB</div>
                  <input type="file" id="buktiTransfer" name="bukti_transfer" accept=".jpg,.jpeg,.png" class="d-none" required>
                </div>
                @error('bukti_transfer') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                <div class="file-preview" id="filePreview">
                  <img id="previewThumb" class="file-preview-thumb" alt="Pratinjau bukti transfer">
                  <div>
                    <div class="file-preview-name" id="previewName"></div>
                    <div class="file-preview-size" id="previewSize"></div>
                  </div>
                  <button type="button" class="file-preview-remove" id="removeFile" aria-label="Hapus file">
                    <i class="bi bi-trash3-fill"></i>
                  </button>
                </div>
              </div>

              <div class="col-12">
                <label class="form-label-custom" for="catatanKonfirmasi">Catatan (opsional)</label>
                <textarea class="form-control-custom" id="catatanKonfirmasi" name="catatan" placeholder="Contoh: transfer dilakukan oleh orang tua atas nama berbeda">{{ old('catatan') }}</textarea>
              </div>

              <div class="col-12">
                <button type="submit" class="btn btn-daftar w-100">
                  <i class="bi bi-send-check-fill me-1"></i> Kirim Konfirmasi
                </button>
                <p class="text-muted small text-center mt-3 mb-0">
                  <i class="bi bi-shield-check me-1"></i>
                  Status konfirmasi dapat dipantau melalui menu <a href="#" class="fw-semibold text-decoration-none">Cek Status</a>.
                </p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
  document.querySelectorAll('.btn-copy').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var target = document.getElementById(btn.getAttribute('data-copy-target'));
      var text = target.textContent.trim();

      navigator.clipboard.writeText(text).then(function () {
        var original = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2 me-1"></i>Tersalin';
        btn.classList.add('copied');
        setTimeout(function () {
          btn.innerHTML = original;
          btn.classList.remove('copied');
        }, 1800);
      });
    });
  });

    (function () {
        var dropzone = document.getElementById('dropzone');
        var fileInput = document.getElementById('buktiTransfer');
        var preview = document.getElementById('filePreview');
        var thumb = document.getElementById('previewThumb');
        var nameEl = document.getElementById('previewName');
        var sizeEl = document.getElementById('previewSize');
        var removeBtn = document.getElementById('removeFile');

        function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        function showPreview(file) {
        if (!file) return;

        nameEl.textContent = file.name;
        sizeEl.textContent = formatSize(file.size);

        var reader = new FileReader();
        reader.onload = function (e) {
            thumb.src = e.target.result;
        };
        reader.readAsDataURL(file);

        preview.classList.add('show');
        }

    function resetPreview() {
      fileInput.value = '';
      preview.classList.remove('show');
      thumb.src = '';
    }

    dropzone.addEventListener('click', function () { fileInput.click(); });

    fileInput.addEventListener('change', function () {
      if (fileInput.files.length) showPreview(fileInput.files[0]);
    });

    ['dragenter', 'dragover'].forEach(function (evt) {
      dropzone.addEventListener(evt, function (e) {
        e.preventDefault();
        dropzone.classList.add('dragover');
      });
    });
    ['dragleave', 'drop'].forEach(function (evt) {
      dropzone.addEventListener(evt, function (e) {
        e.preventDefault();
        dropzone.classList.remove('dragover');
      });
    });
    dropzone.addEventListener('drop', function (e) {
      var files = e.dataTransfer.files;
      if (files.length) {
        fileInput.files = files;
        showPreview(files[0]);
      }
    });

    removeBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      resetPreview();
    });
  })();
</script>
@endpush