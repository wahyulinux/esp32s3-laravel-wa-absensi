<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-slate-200 bg-white']) }}>
    <div class="overflow-x-auto"><table class="w-full text-sm">{{ $slot }}</table></div>
</div>
