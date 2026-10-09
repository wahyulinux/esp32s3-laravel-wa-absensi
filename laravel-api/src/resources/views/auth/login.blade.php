<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ $namaSekolah }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📋</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-sm">
    <div class="flex flex-col items-center mb-8">
        <div class="w-14 h-14 rounded-2xl bg-blue-500 flex items-center justify-center shadow-lg shadow-blue-500/30 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="w-7 h-7">
                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h1 class="text-white font-bold text-lg text-center">{{ $namaSekolah }}</h1>
        <p class="text-slate-400 text-sm mt-1">Masuk untuk mengelola sistem absensi</p>
    </div>

    <div class="bg-white rounded-2xl shadow-2xl p-6">
        <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
            @csrf

            @if($errors->has('username') || session('status'))
            <div class="px-4 py-3 rounded-xl text-sm bg-red-50 text-red-700 border border-red-100">
                {{ $errors->first('username') ?: session('status') }}
            </div>
            @endif

            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-1.5">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus autocomplete="username"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow">
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow
                              @error('password') border-red-400 bg-red-50 @enderror">
                @error('password')
                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600 select-none">
                <input type="checkbox" name="remember" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                Ingat saya
            </label>

            <button type="submit"
                    class="w-full px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                Masuk
            </button>
        </form>
    </div>

    <a href="{{ route('dashboard') }}" class="block text-center text-slate-400 hover:text-white text-sm mt-6 transition-colors">
        ← Kembali ke dashboard
    </a>
</div>

</body>
</html>
