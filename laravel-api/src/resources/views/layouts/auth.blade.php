<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') — {{ $namaSekolah }}</title>
    <link rel="icon" href="{{ asset('images/logo-sekolah.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] } } } };
    </script>
    @stack('styles')
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-10 font-sans text-slate-900 antialiased">
    <main class="w-full max-w-md">@yield('content')</main>
    @stack('scripts')
</body>
</html>
