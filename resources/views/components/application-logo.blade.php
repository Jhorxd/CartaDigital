@php
    $tenant = $tenant ?? request()->get('tenant');
@endphp

@if($tenant && $tenant->logo)
    <img src="{{ $tenant->logo }}" {{ $attributes->merge(['class' => 'h-9 w-auto object-contain']) }} alt="{{ $tenant->name }}">
@else
    <div {{ $attributes->merge(['class' => 'flex items-center gap-1 font-black text-2xl tracking-tighter text-slate-800']) }}>
        <span class="text-primary italic">Mi</span>CartaDig
    </div>
@endif
