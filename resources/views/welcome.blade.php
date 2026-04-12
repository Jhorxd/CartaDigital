<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MiCartaDig | El Futuro de los Menús QR</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        primary: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        },
                        orange: {
                            500: '#f97316',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .hero-gradient {
            background: radial-gradient(circle at 50% 50%, rgba(124, 58, 237, 0.15) 0%, rgba(0, 0, 0, 0) 50%);
        }
        .text-gradient {
            background: linear-gradient(135deg, #a78bfa 0%, #f97316 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="font-sans bg-[#0a0a0a] text-white selection:bg-primary-500/30 selection:text-primary-200">

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 px-6 py-4" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <nav class="max-w-7xl mx-auto flex items-center justify-between glass px-6 py-3 rounded-2xl transition-all duration-300" 
             :class="scrolled ? 'translate-y-2' : ''">
            <a href="/" class="flex items-center gap-3 text-2xl font-extrabold tracking-tighter">
                <img src="{{ asset('favicon.png') }}" class="w-8 h-8 object-contain" alt="MiCartaDig Icon">
                <span><span class="text-orange-500 italic">Mi</span>CartaDig</span>
            </a>
            
            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-white/70">
                <a href="#beneficios" class="hover:text-white transition-colors">Beneficios</a>
                <a href="#pasos" class="hover:text-white transition-colors">Pasos para empezar</a>
                <a href="#franquicias" class="hover:text-white transition-colors">Franquicias</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="https://wa.me/51936427929" target="_blank" class="px-5 py-2 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-500 hover:shadow-[0_0_20px_rgba(124,58,237,0.3)] transition-all">Empezar Gratis</a>
            </div>
        </nav>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="relative min-h-screen flex flex-col items-center justify-center pt-24 pb-12 overflow-hidden">
            <div class="hero-gradient absolute inset-0 pointer-events-none"></div>
            
            <div class="max-w-4xl mx-auto px-6 text-center z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-400 text-xs font-bold uppercase tracking-widest mb-8 animate-bounce">
                    🚀 La revolución de los menús QR ha llegado
                </div>
                
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black tracking-tight leading-[1.1] mb-8">
                    Tu Menú Físico es el <span class="text-gradient">Pasado</span>. Esto es el <span class="underline decoration-orange-500 underline-offset-8 decoration-4">Futuro</span>.
                </h1>
                
                <p class="text-lg md:text-xl text-white/50 max-w-2xl mx-auto mb-12 leading-relaxed">
                    Digitaliza tu restaurante con la tecnología de <strong>MiCartaDig</strong>. Imágenes ultraligeras, gestión desde el móvil y una experiencia que enamora a tus clientes.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="https://wa.me/51936427929" target="_blank" class="w-full sm:w-auto px-8 py-4 bg-orange-500 text-white font-black text-lg rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-[0_0_30px_rgba(249,115,22,0.3)] group">
                        Crear mi cuenta ahora
                        <svg class="inline-block ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>

                <!-- Stats Bar -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-24 pt-12 border-t border-white/5">
                    <div>
                        <div class="text-3xl font-black text-white mb-1">QR</div>
                        <div class="text-xs text-white/40 uppercase tracking-widest">Menú Digital Pro</div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-white mb-1">100%</div>
                        <div class="text-xs text-white/40 uppercase tracking-widest">Autoadministrable</div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-white mb-1">Elegante</div>
                        <div class="text-xs text-white/40 uppercase tracking-widest">Diseño Rich Aesthetics</div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-white mb-1">Segundos</div>
                        <div class="text-xs text-white/40 uppercase tracking-widest">Actualización Inmediata</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Grid -->
        <section id="beneficios" class="py-24 px-6 relative">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-20">
                    <h2 class="text-3xl md:text-5xl font-black mb-6">Beneficios de MiCartaDig</h2>
                    <p class="text-white/40 max-w-xl mx-auto">No es solo un menú QR, es una herramienta de marketing y gestión diseñada para el éxito.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Feature 1 -->
                    <div class="glass p-8 rounded-[2.5rem] hover:bg-white/[0.05] transition-colors border-white/10 group">
                        <div class="w-14 h-14 bg-blue-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">⚡</div>
                        <h3 class="text-xl font-bold mb-4">Carga Ultra-Rápida</h3>
                        <p class="text-white/40 leading-relaxed text-sm">Convertimos todas tus fotos a <strong>WebP</strong> automáticamente. Menor peso, mayor velocidad, clientes más felices.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="glass p-8 rounded-[2.5rem] hover:bg-white/[0.05] transition-colors border-white/10 group">
                        <div class="w-14 h-14 bg-orange-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">📸</div>
                        <h3 class="text-xl font-bold mb-4">Gestión con tu Cámara</h3>
                        <p class="text-white/40 leading-relaxed text-sm">¿Nuevo plato? Tómale una foto con tu celular y súbela directo al sistema. Así de simple, sin complicaciones.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="glass p-8 rounded-[2.5rem] hover:bg-white/[0.05] transition-colors border-white/10 group">
                        <div class="w-14 h-14 bg-green-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">🔍</div>
                        <h3 class="text-xl font-bold mb-4">Buscador Inteligente</h3>
                        <p class="text-white/40 leading-relaxed text-sm">Un motor de búsqueda en tiempo real integrado para que tus clientes encuentren ese plato especial al instante.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="glass p-8 rounded-[2.5rem] hover:bg-white/[0.05] transition-colors border-white/10 group">
                        <div class="w-14 h-14 bg-red-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">🚫</div>
                        <h3 class="text-xl font-bold mb-4">Control "Sold Out"</h3>
                        <p class="text-white/40 leading-relaxed text-sm">Marca productos como agotados en un click. Se verán como no disponibles en la carta de inmediato.</p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="glass p-8 rounded-[2.5rem] hover:bg-white/[0.05] transition-colors border-white/10 group">
                        <div class="w-14 h-14 bg-purple-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">🛡️</div>
                        <h3 class="text-xl font-bold mb-4">Panel SaaS Profesional</h3>
                        <p class="text-white/40 leading-relaxed text-sm">Filtros avanzados por categorías y nombres. Gestiona miles de productos sin perder la cabeza.</p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="glass p-8 rounded-[2.5rem] hover:bg-white/[0.05] transition-colors border-white/10 group">
                        <div class="w-14 h-14 bg-yellow-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">💎</div>
                        <h3 class="text-xl font-bold mb-4">Diseño Rich Aesthetics</h3>
                        <p class="text-white/40 leading-relaxed text-sm">Interfaz móvil nativa, carrito animado (Dynamic Island) y estética premium que sube el nivel de tu marca.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pasos Section -->
        <section id="pasos" class="py-24 px-6 relative border-t border-white/5">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-5xl font-black mb-4">Empieza en 3 Pasos</h2>
                    <p class="text-white/40">Digitaliza tu negocio hoy mismo, sin demoras.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div class="text-center relative">
                        <div class="text-6xl font-black text-white/5 absolute -top-10 left-1/2 -translate-x-1/2">01</div>
                        <div class="text-2xl font-bold mb-4">Regístrate</div>
                        <p class="text-white/40 text-sm">Contáctanos por WhatsApp y activa tu cuenta profesional en minutos.</p>
                    </div>
                    <div class="text-center relative">
                        <div class="text-6xl font-black text-white/5 absolute -top-10 left-1/2 -translate-x-1/2">02</div>
                        <div class="text-2xl font-bold mb-4">Sube tu Menú</div>
                        <p class="text-white/40 text-sm">Agrega tus platos, precios y fotos optimizadas desde cualquier dispositivo.</p>
                    </div>
                    <div class="text-center relative">
                        <div class="text-6xl font-black text-white/5 absolute -top-10 left-1/2 -translate-x-1/2">03</div>
                        <div class="text-2xl font-bold mb-4">Genera tu QR</div>
                        <p class="text-white/40 text-sm">Imprime tu código QR único y ofreces una experiencia digital a tus clientes.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Franquicias Section -->
        <section id="franquicias" class="py-24 px-6 bg-white text-black rounded-[3rem] md:rounded-[5rem] mx-4 overflow-hidden relative">
            <div class="max-w-7xl mx-auto flex flex-col items-center text-center">
                <h2 class="text-4xl md:text-6xl font-black mb-8">Pensado para Franquicias</h2>
                <p class="text-black/60 text-lg md:text-xl max-w-2xl mb-12">
                    Nuestra infraestructura inteligente te permite gestionar una red de locales desde un único centro de control. Perfecto para cadenas y corporaciones gastronómicas.
                </p>
                
                <div class="flex flex-wrap justify-center gap-4">
                    <div class="px-6 py-3 bg-black/5 rounded-2xl font-bold border border-black/5">Subdominios Propios</div>
                    <div class="px-6 py-3 bg-black/5 rounded-2xl font-bold border border-black/5">Colores de Marca</div>
                    <div class="px-6 py-3 bg-black/5 rounded-2xl font-bold border border-black/5">Logos Personalizados</div>
                    <div class="px-6 py-3 bg-black/5 rounded-2xl font-bold border border-black/5">Control Total de Usuarios</div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-32 px-6 text-center">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-4xl md:text-6xl font-black mb-12 leading-tight">¿Listo para dar el salto digital?</h2>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="https://wa.me/51936427929" target="_blank" class="w-full sm:w-auto px-10 py-5 bg-primary-600 text-white font-black text-xl rounded-2xl hover:bg-primary-500 transition-all shadow-xl">
                        Contactar por WhatsApp
                    </a>
                </div>
                <p class="mt-8 text-white/30 text-sm">Únete a cientos de restaurantes que ya confían en MiCartaDig.</p>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="py-12 border-t border-white/5 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="text-2xl font-black tracking-tighter">
                <span class="text-orange-500 italic">Mi</span>CartaDig
            </div>
            
            <div class="flex gap-8 text-white/40 text-sm uppercase font-bold tracking-widest">
                <a href="#" class="hover:text-white transition-colors">Privacidad</a>
                <a href="#" class="hover:text-white transition-colors">Términos</a>
                <a href="https://wa.me/51936427929" class="hover:text-white transition-colors">Soporte</a>
            </div>

            <div class="text-white/30 text-sm">
                &copy; {{ date('Y') }} MiCartaDig. Hecho para digitalizar la gastronomía.
            </div>
        </div>
    </footer>

</body>
</html>
