<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="favicon.svg">
    <title>@yield('title', 'Akrez Youtube Downloader')</title>

    <link href="plugins/sahel/css/sahel.css" rel="stylesheet" type="text/css" />
    <link href="plugins/bootstrap/css/bootstrap.rtl.min.css" rel="stylesheet" type="text/css" />

    <style>
        html,
        body {
            font-family: "sahel" !important;
            -moz-osx-font-smoothing: grayscale;
            -webkit-font-smoothing: antialiased;
        }
    </style>
</head>

<body>
    <div class="container" dir="rtl">
        @yield('content')
    </div>
    <!-- Javascript -->
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
</body>


</html>
