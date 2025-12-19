@include('partials.header')
@include('partials.navbar')
@include('partials.sidebar')

      <main class="app-main">
        @include('partials.alerts')
        @yield('content')
      </main>

@include('partials.footer')
