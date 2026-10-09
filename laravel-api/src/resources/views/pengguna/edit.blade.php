@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('breadcrumb')
    <a href="{{ route('pengguna.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors text-sm">Pengguna</a>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-gray-300"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
    <span class="text-gray-700 text-sm font-medium">Edit Pengguna</span>
@endsection

@section('content')

<div class="max-w-xl">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Edit Pengguna</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $pengguna->name }} · {{ '@' . $pengguna->username }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
            <p class="text-sm font-semibold text-gray-700">Informasi Akun</p>
        </div>
        <div class="p-6">
        <form method="POST" action="{{ route('pengguna.update', $pengguna) }}" class="space-y-5">
            @csrf @method('PUT')

            @include('pengguna._form')

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('pengguna.index') }}"
                   class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
