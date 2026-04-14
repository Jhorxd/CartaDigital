<x-guest-layout>
    @php
        $tenant = request()->get('tenant');
    @endphp

    <div class="mb-12 text-center">
        <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
            {{ $tenant ? 'Bienvenido' : 'Panel de Control' }}
        </h2>
        <p class="text-slate-500 text-[15px] mt-3 leading-relaxed font-medium">
            {{ $tenant ? 'Inicia sesión para gestionar ' . $tenant->name : 'Acceso exclusivo para administradores globales de la plataforma.' }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-8" :status="session('status')" />

    <form method="POST" action="{{ request()->routeIs('login.admin') ? route('login.admin') : route('login') }}" class="space-y-8">
        @csrf

        <!-- Email Address -->
        <div class="relative group">
            <x-input-label for="email" :value="__('Correo Electrónico')" class="absolute -top-2.5 left-4 px-2 bg-white text-[11px] uppercase tracking-[0.15em] font-extrabold text-slate-400 group-focus-within:text-[--brand-color] transition-all z-10" />
            <x-text-input id="email" class="block w-full !py-4 !px-6 !rounded-2xl !border-slate-100 !bg-slate-50/50 focus:!bg-white focus:!ring-4 focus:!ring-[--brand-color-soft] focus:!border-[--brand-color] transition-all placeholder:text-slate-300 text-slate-700 font-medium" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="tu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold text-rose-500 ml-2" />
        </div>

        <!-- Password -->
        <div class="relative group">
            <x-input-label for="password" :value="__('Contraseña')" class="absolute -top-2.5 left-4 px-2 bg-white text-[11px] uppercase tracking-[0.15em] font-extrabold text-slate-400 group-focus-within:text-[--brand-color] transition-all z-10" />
            <x-text-input id="password" class="block w-full !py-4 !px-6 !rounded-2xl !border-slate-100 !bg-slate-50/50 focus:!bg-white focus:!ring-4 focus:!ring-[--brand-color-soft] focus:!border-[--brand-color] transition-all placeholder:text-slate-300 text-slate-700 font-medium"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold text-rose-500 ml-2" />
        </div>

        <div class="flex items-center justify-between px-1">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded-md border-slate-200 text-[--brand-color] shadow-sm focus:ring-[--brand-color] focus:ring-offset-0 transition-all cursor-pointer" name="remember">
                <span class="ms-3 text-[13px] font-semibold text-slate-500 group-hover:text-slate-900 transition-colors">{{ __('Recordarme') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[12px] font-bold text-slate-400 hover:text-[--brand-color] transition-colors" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu acceso?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" style="background-color: var(--brand-color);" class="w-full py-4.5 rounded-2xl text-white font-bold text-sm uppercase tracking-[0.1em] shadow-[0_15px_30px_-5px_var(--brand-color-soft)] hover:shadow-[0_20px_40px_-5px_var(--brand-color-soft)] hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex justify-center items-center gap-3">
                <span>{{ __('Ingresar al Panel') }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </form>
    </form>
</x-guest-layout>
