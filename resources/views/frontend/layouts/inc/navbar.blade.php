<nav class="navbar navbar-expand-lg main_menu" id="main_menu_area">
  <div class="container">
    <a class="navbar-brand" href="/">
      @if($generalSetting->logo && file_exists(public_path($generalSetting->logo)))
        <img src="{{asset($generalSetting->logo)}}" alt="logo" class="navbar-logo-img">
      @else
        <span class="navbar-logo-text">{{ config('app.name', 'Portfolio') }}</span>
      @endif
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <i class="far fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{route('home')}}">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{{route('portfolio')}}">Portfolio</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{{route('blog')}}">Blog</a>
        </li>
      </ul>

      {{-- Theme Toggle Button --}}
      <div class="theme-toggle-wrap">
        <button
          id="theme-toggle-btn"
          class="theme-toggle-btn"
          aria-label="Mudar para tema claro"
          aria-pressed="false"
          title="Ativar tema claro"
        >
          <span class="theme-icon theme-icon--sun" aria-hidden="true">☀️</span>
          <span class="theme-icon theme-icon--moon" aria-hidden="true">🌙</span>
        </button>
      </div>

    </div>
  </div>
</nav>
