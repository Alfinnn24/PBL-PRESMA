<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Presma - PBL Prestasi Mahasiswa</title>
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  
  <!-- Custom CSS tambahin sendiri-->
  <link rel="stylesheet" href="{{ asset('css/landing-page.css') }}" />
</head>
<body>
  <!-- Loading Overlay -->
  <div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
  </div>

  <!-- Header -->
  <nav class="navbar navbar-light bg-white justify-content-between px-4 sticky-top" id="navbar">
    <span class="navbar-brand font-weight-bold">Presma</span>
    <!-- link buat nanti login arahin sendiri -->
    <a class="nav-link" href="{{ url('login') }}">Login</a>
  </nav>

  <!-- Hero Section -->
  <section class="hero d-flex align-items-center justify-content-center text-white text-center">
    <div class="animate-on-scroll fade-in">
      <p class="small text-warning mb-1 pulse">TI-2A</p>
      <h1 class="display-4 font-weight-bold floating">
        PBL Pencatatan<br />Prestasi Mahasiswa
      </h1>
      <p class="scroll-down mt-4" onclick="smoothScrollTo('.about-section')">scroll down ↓</p>
    </div>
  </section>

  <!-- tentang presma -->
  <section class="py-5 bg-dark text-white about-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0 animate-on-scroll slide-left interactive-card">
          <h6 class="text-warning">GET STARTED</h6>
          <h2 class="mb-3">Apa Itu PRESMA?</h2>
          <p>
            PRESMA merupakan website yang dirancang untuk mencatat
            prestasi mahasiswa secara sistematis. Dengan fitur
            rekomendasi otomatis berbasis data, PRESMA membantu mahasiswa menemukan
            rekomendasi lomba terbaik secara cepat dan objektif sesuai dengan kemampuan.
          </p>
        </div>
        <div class="col-md-6 text-center animate-on-scroll zoom-in delay-200">
          <img
            src="{{ asset('images/gambarcatatan.jpg') }}"
            alt="catat"
            class="img-fluid interactive-card"
            style="max-height: 350px; border-radius: 15px;"
          />
        </div>
      </div>
    </div>
  </section>

  <!-- Anggota -->
  <section class="py-5 bg-dark text-white team-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 text-center mb-4 mb-md-0 animate-on-scroll rotate-in">
          <img
            src="{{ asset('images/kelas2a.jpg') }}"
            alt="Team"
            class="img-fluid interactive-card"
            style="max-height: 300px; border-radius: 15px;"
          />
        </div>
        <div class="col-md-6 animate-on-scroll slide-right delay-200">
          <h6 class="text-warning">PBL KELOMPOK 1</h6>
          <h2 class="mb-3">Anggota</h2>
          <div class="team-member animate-on-scroll slide-up delay-200">
            <i class="fas fa-user-graduate text-warning me-2"></i>
            Innama Maesa Putri - 234720235
          </div>
          <div class="team-member animate-on-scroll slide-up delay-300">
            <i class="fas fa-user-graduate text-warning me-2"></i>
            Lelyta Meyda Ayu Budiyanti - 234720214
          </div>
          <div class="team-member animate-on-scroll slide-up delay-400">
            <i class="fas fa-user-graduate text-warning me-2"></i>
            Moch. Alfin Burhanudin A. - 234720024
          </div>
          <div class="team-member animate-on-scroll slide-up delay-100">
            <i class="fas fa-user-graduate text-warning me-2"></i>
            Muhammad Nur Aziz - 234720237
          </div>
          <!-- <div class="team-member animate-on-scroll slide-up delay-500">
            <i class="fas fa-user-graduate text-warning me-2"></i>
            Satrio Ahmad Ramadhani - 234720163
          </div> -->
          <p class="text-warning mt-3 pulse">TI-2A</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer/CTA -->
  <footer class="text-center text-white py-5 bg-dark animate-on-scroll fade-in">
    <div class="mb-3">
      <h4 class="mb-2">Yuk Login</h4>
      <p class="text-muted">
        PRESMA kelola prestasi mahasiswa dengan mudah
      </p>
    </div>
    <!-- link login -->
    <a href="{{ url('login') }}" class="btn login-btn">
      <i class="fas fa-sign-in-alt me-2"></i>
      Continue to Login
    </a>
  </footer>

  <!-- JavaScript -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- buat javascript nanti custom aja -->
  <script src="{{ asset('js/landing-page.js') }}"></script>
  
</body>
</html>