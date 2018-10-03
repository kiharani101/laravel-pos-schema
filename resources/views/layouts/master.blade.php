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
    <!-- CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/fa4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
    @stack('css')
  </head>
  <body class="app sidebar-mini tks">
    @auth()
      @include('partials._topnav', ['some' => 'data'])
      @include('partials._aside', ['some' => 'data'])
    @endauth

    @yield('content', '')
    <!-- Essential javascripts for application to work-->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
     <script src="{{ asset('js/parsley.min.js') }}"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="{{ asset('js/plugins/pace.min.js') }}"></script>
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
