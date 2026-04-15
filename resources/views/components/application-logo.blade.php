@php
    $tenant = $tenant ?? (request()->get('tenant') ?? null);
    // Determine if we are in a "compact" mode (like a navbar) or "hero" mode (like login)
    // We can check if specific height classes are passed in
    $isCompact = str_contains($attributes->get('class', ''), 'h-9') || str_contains($attributes->get('class', ''), 'h-8') || str_contains($attributes->get('class', ''), 'h-10');
@endphp

@if($tenant)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
        @if($tenant->logo)
            <img src="{{ $tenant->logo }}" class="{{ $isCompact ? 'h-9' : 'h-20' }} w-auto object-contain" alt="{{ $tenant->name }}">
        @else
            <div class="{{ $isCompact ? 'h-10 w-10 text-xl' : 'h-24 w-24 text-5xl' }} shrink-0 rounded-xl shadow-lg flex items-center justify-center text-white font-black border-2 border-white/10" style="background-color: var(--color-primary);">
                {{ strtoupper(substr($tenant->name, 0, 1)) }}
            </div>
        @endif
        
        @if(!$isCompact)
            <div class="flex flex-col items-center">
                <div class="font-black text-3xl tracking-tighter text-slate-100 drop-shadow-md">
                    {{ $tenant->name }}
                </div>
                <div class="h-1.5 w-12 bg-white/20 rounded-full mt-2 overflow-hidden">
                    <div class="h-full bg-white/40 w-1/3"></div>
                </div>
            </div>
        @else
            <span class="font-bold text-lg tracking-tight text-slate-800">{{ $tenant->name }}</span>
        @endif
    </div>
@else
    {{-- Global Platform Logo --}}
    <div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
        <div class="relative group shrink-0">
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
            <img src="{{ asset('favicon.png') }}" class="relative {{ $isCompact ? 'h-10 w-10' : 'h-24 w-24' }} object-contain rounded-xl shadow-xl transform transition hover:scale-105 duration-300" alt="Mi Carta Dig">
        </div>
        
        <div class="flex flex-col {{ $isCompact ? 'items-start' : 'items-center' }}">
            <div class="font-black {{ $isCompact ? 'text-xl' : 'text-3xl' }} tracking-tighter {{ $isCompact ? 'text-slate-800' : 'text-slate-100' }} drop-shadow-sm">
                <span class="text-indigo-600 italic">Mi</span>CartaDig
            </div>
            @if(!$isCompact)
                <div class="h-1 w-8 bg-indigo-500 rounded-full mt-1 opacity-50"></div>
            @endif
        </div>
    </div>
@endif
