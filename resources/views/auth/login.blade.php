<x-guest-layout>
    @php
        $tenant = request()->get('tenant');
    @endphp

    <div class="mb-10 text-center sm:text-left">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight leading-none">
            {{ $tenant ? 'Inicia Sesión' : 'Canal Administrativo' }}
        </h2>
        <p class="text-gray-500 text-sm mt-4 leading-relaxed max-w-sm">
            {{ $tenant ? 'Accede al panel de ' . $tenant->name : 'Ingresa tus credenciales seguras para la administración global del sistema.' }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ request()->routeIs('login.admin') ? route('login.admin') : route('login') }}" class="space-y-7">
        @csrf

        <!-- Email Address -->
        <div class="group">
            <x-input-label for="email" :value="__('Correo Electrónico')" class="text-xs uppercase tracking-[0.1em] font-extrabold text-gray-400 mb-2 ml-1 group-focus-within:text-[--brand-color] transition-colors" />
            <x-text-input id="email" class="block w-full !py-3.5 !px-5 !rounded-2xl !border-gray-100 !bg-white/60 focus:!bg-white focus:!ring-2 focus:!ring-[--brand-color] focus:!border-transparent transition-all placeholder:text-gray-300 shadow-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="ejemplo@correo.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-semibold text-red-500 shadow-sm" />
        </div>

        <!-- Password -->
        <div class="group">
            <x-input-label for="password" :value="__('Contraseña')" class="text-xs uppercase tracking-[0.1em] font-extrabold text-gray-400 mb-2 ml-1 group-focus-within:text-[--brand-color] transition-colors" />

            <x-text-input id="password" class="block w-full !py-3.5 !px-5 !rounded-2xl !border-gray-100 !bg-white/60 focus:!bg-white focus:!ring-2 focus:!ring-[--brand-color] focus:!border-transparent transition-all placeholder:text-gray-300 shadow-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-semibold text-red-500 shadow-sm" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-1">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer w-full sm:w-auto">
                <div class="relative flex items-center">
                    <input id="remember_me" type="checkbox" class="w-5 h-5 rounded-lg border-gray-300 text-[--brand-color] shadow-sm focus:ring-[--brand-color] focus:ring-offset-0 transition-all" name="remember">
                </div>
                <span class="ms-3 text-sm font-medium text-gray-500 group-hover:text-gray-800 transition-colors">{{ __('Recordarme') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs font-bold text-gray-400 hover:text-[--brand-color] transition-colors uppercase tracking-widest text-center sm:text-right w-full sm:w-auto" href="{{ route('password.request') }}">
                    {{ __('¿Contraseña olvidada?') }}
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" style="background-color: var(--brand-color);" class="w-full py-4.5 rounded-2xl text-white font-black text-xs uppercase tracking-[0.25em] shadow-2xl shadow-[--brand-color-soft] hover:scale-[1.03] active:scale-[0.97] transition-all flex justify-center items-center gap-3">
                <span class="py-1">{{ __('Entrar ahora') }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </button>
        </div>
    </form>
</x-guest-layout>
