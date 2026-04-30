<!DOCTYPE html>
<html lang="en" id="admin-html-root">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Admin | @yield('title')</title>

  <!-- include CSS Style -->
  @include('admin.layouts.inc.style')

  {{-- Anti-FOUC: aplica data-theme ANTES do primeiro render --}}
  <script>
    (function () {
      var stored = localStorage.getItem('admin-theme');
      var preferLight = window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches;
      var theme = stored || (preferLight ? 'light' : 'dark');
      if (theme === 'light') {
        document.documentElement.setAttribute('data-theme', 'light');
      }
    })();
  </script>
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <!-- include sidebar -->
      @include('admin.layouts.inc.sidebar')


      <!-- Main Content -->
      <div class="main-content">
        @yield('content')
      </div>

      <!-- include footer -->
      @include('admin.layouts.inc.footer')
    </div>
  </div>

  <!-- include JS Scripts -->
  @include('admin.layouts.inc.script')

</body>

</html>
