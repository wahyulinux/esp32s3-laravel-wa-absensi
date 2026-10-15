<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ $namaSekolah }}</title>
    {{-- School Identity Favicon --}}
    <link rel="icon" href="{{ asset('images/logo-sekolah.png') }}" type="image/png">
    
    {{-- FontAwesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#5C2828] min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-sm">
    <div class="flex flex-col items-center mb-7">
        <div class="w-20 h-20 rounded-2xl bg-white p-2.5 flex items-center justify-center shadow-xl shadow-black/30 mb-3.5 border-2 border-[#FF8533]/30 overflow-hidden">
            <img src="{{ asset('images/logo-sekolah.png') }}" alt="{{ $namaSekolah }}" class="w-full h-full object-contain">
        </div>
        <h1 class="text-white font-extrabold text-lg text-center tracking-tight">{{ $namaSekolah }}</h1>
        <p class="text-rose-200/70 text-xs mt-1 font-medium">Sistem Absensi ESP32-S3 &middot; RFID &amp; WhatsApp</p>
    </div>

    <div class="bg-white rounded-2xl shadow-2xl p-6 border-t-4 border-[#FF8533]">
        <h2 class="text-base font-bold text-[#1E1E1E] mb-1">Masuk ke Sistem</h2>
        <p class="text-xs text-gray-500 mb-5">Masukkan kredensial administrator Anda</p>

        <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
            @csrf

            @if($errors->has('username') || session('status'))
            <div class="px-3.5 py-2.5 rounded-xl text-xs bg-red-50 text-red-700 border border-red-200 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                <span>{{ $errors->first('username') ?: session('status') }}</span>
            </div>
            @endif

            <div>
                <label for="username" class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-user text-xs"></i>
                    </span>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus autocomplete="username"
                           class="w-full pl-9 pr-3.5 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#FF8533] focus:border-transparent transition-all">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </span>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                           class="w-full pl-9 pr-3.5 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#FF8533] focus:border-transparent transition-all
                                  @error('password') border-red-400 bg-red-50 @enderror">
                </div>
                @error('password')
                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                    <i class="fa-solid fa-circle-xmark text-[11px]"></i>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-xs text-gray-600 select-none cursor-pointer pt-1">
                <input type="checkbox" name="remember" value="1" class="rounded border-gray-300 text-[#FF8533] focus:ring-[#FF8533]">
                <span>Ingat saya di perangkat ini</span>
            </label>

            <button type="submit"
                    class="w-full px-6 py-2.5 bg-[#FF8533] text-white rounded-xl text-sm font-bold hover:bg-[#EA6D1A] transition-all shadow-md shadow-[#FF8533]/25 active:scale-95 flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                <span>Masuk Sekarang</span>
            </button>
        </form>
    </div>

    <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-1.5 text-center text-rose-200/80 hover:text-white text-xs font-medium mt-6 transition-colors">
        <i class="fa-solid fa-arrow-left text-[11px]"></i>
        <span>Kembali ke dashboard utama</span>
    </a>
</div>

</body>
</html>
