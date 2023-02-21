<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
        <!-- font awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- app custom css -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

        @stack('assetCss')
    </head>
    <body>
        <div>
          <section>
              @yield('content')
          </section>
        </div>

        @stack('assetJs')
        <script src="{{ mix('js/app.js') }}"></script>
        <noscript>
          This website relies on JavaScript, which you appear to have disabled. You will not be
          able to use many of this website's features without JavaScript enabled.
        </noscript>
    </body>
</html>
