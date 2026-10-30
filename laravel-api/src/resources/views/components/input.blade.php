@props(['type' => 'text'])
<input type="{{ $type }}" {{ $attributes->merge(['class' => 'block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20']) }}>
