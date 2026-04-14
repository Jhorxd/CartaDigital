@php
    $tenant = $tenant ?? (request()->get('tenant') ?? null);
@endphp

@if($tenant)
    <div {{ $attributes->merge(['class' => 'flex flex-col items-center gap-4']) }}>
        @if($tenant->logo)
            <div class="p-1 bg-white rounded-3xl shadow-2xl transform transition hover:scale-105 duration-300">
                <img src="{{ $tenant->logo }}" class="h-24 w-24 object-cover rounded-[1.25rem]" alt="{{ $tenant->name }}">
            </div>
        @else
            {{-- Professional Placeholder for Tenants without Logo --}}
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-[--brand-color] to-white/20 rounded-3xl blur opacity-25 transition duration-1000"></div>
                <div class="relative h-24 w-24 rounded-3xl shadow-2xl flex items-center justify-center text-white font-black text-5xl transform transition hover:scale-105 duration-300 border-2 border-white/10" style="background-color: var(--brand-color); text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                </div>
            </div>
        @endif
        
        <div class="flex flex-col items-center">
            <div class="font-black text-3xl tracking-tighter text-slate-100 drop-shadow-md">
                {{ $tenant->name }}
            </div>
            <div class="h-1.5 w-12 bg-white/20 rounded-full mt-2 overflow-hidden">
                <div class="h-full bg-white/40 w-1/3"></div>
            </div>
        </div>
    </div>
@else
    {{-- Global Platform Logo (Admin Login) --}}
    <div {{ $attributes->merge(['class' => 'flex flex-col items-center gap-4']) }}>
        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
            <img src="{{ asset('logo-sq.png') }}" class="relative h-24 w-24 object-contain rounded-3xl shadow-2xl transform transition hover:scale-105 duration-300" alt="Mi Carta Dig">
        </div>
        <div class="flex flex-col items-center">
            <div class="font-black text-3xl tracking-tighter text-slate-100 drop-shadow-md">
                <span class="text-indigo-400 italic">Mi</span>CartaDig
            </div>
            <div class="h-1 w-8 bg-indigo-500 rounded-full mt-1 opacity-50"></div>
        </div>
    </div>
@endif
