<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Custom CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @stack('css')
</head>

<body>
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
      @guest()
        @yield('content')
      @else
        @include('partials/_topnav')
        @include('partials/_aside')
        
        <div class="page-wrapper">
          <div class="card border-0">
            <div class="card-body">
              @yield('content')
            </div>
          </div>

          <footer class="footer text-center">
              All Rights Reserved by Xtreme Admin. Designed and Developed by <a href="https://wrappixel.com">WrapPixel</a>.
          </footer>
        </div>
        @endguest
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script>
      function getProducts(){
        $.ajax({
          url: '{{ route('product.all') }}'
        });
      }

      function getCategories(){
        $.ajax({
          url: '{{ route('category.all') }}'
        });
      }

    </script>
    @stack('scripts')
</body>

</html>