<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <script src="/asset/js/jquery.min.js"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });
        })
    </script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div id="myToast" class="hidden fixed right-10 top-10 px-5 py-4 text-white bg-green-600 rounded-md drop-shadow-lg" style="z-index: 1000">
    <p class="text-sm">
        @if(session('message'))
            {{ session('message') }}
        @endif
    </p>
</div>
<div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')
    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif
    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
    <script>
        function showToast() {
            // Show the toast
            document.getElementById("myToast").classList.remove("hidden");
            setTimeout(function () {
                document.getElementById("myToast").classList.add("hidden");
            }, 5000);
        }
        @if(session('message'))
            showToast()
        @endif
    </script>
</div>
</body>
</html>
