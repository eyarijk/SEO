<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SEO</title>

    <!-- Styles -->
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <div  id="app">
      @include('includes.nav.main')
    <div class="columns">
      <div class="column">
        @include('includes.nav.manage')

      </div>


    <div class="column is-10">
      @yield('content')
    </div>
      </div>

  </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    @include('includes.toast')
    @yield('scripts')

</body>
</html>
