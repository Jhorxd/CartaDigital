<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carta Digital | Digitaliza tu restorán o cafetería</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent: #f59e0b;
            --bg: #fafaf9;
            --text: #1c1917;
            --text-light: #57534e;
            --glass: rgba(255, 255, 255, 0.7);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #0c0a09;
                --text: #f5f5f4;
                --text-light: #a8a29e;
                --glass: rgba(28, 25, 23, 0.7);
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
            transition: background-color 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Nav */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
            background: var(--glass);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1rem 0;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            items-center: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            font-size: 0.95rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }

        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background-color: var(--primary);
            color: white;
        }

        /* Hero */
        .hero {
            padding: 10rem 0 6rem;
            text-align: center;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, rgba(250, 250, 249, 0) 70%);
            z-index: -1;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 8vw, 4.5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }

        .hero h1 span {
            background: linear-gradient(to right, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-light);
            max-width: 700px;
            margin: 0 auto 2.5rem;
        }

        /* Features */
        .features {
            padding: 6rem 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--glass);
            padding: 2.5rem;
            border-radius: 2rem;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .feature-card h3 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .feature-card p {
            color: var(--text-light);
            font-size: 1rem;
        }

        /* Footer */
        footer {
            padding: 4rem 0;
            border-top: 1px solid rgba(0,0,0,0.05);
            text-align: center;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate {
            animation: fadeIn 0.8s ease-out forwards;
        }

        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }

        @media (max-width: 768px) {
            .hero h1 { font-size: 3rem; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    <nav>
        <div class="container nav-content">
            <a href="/" class="logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path></svg>
                Carta Digital
            </a>
            <div class="nav-links">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Mi Panel</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline">Ingresar</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Empezar Gratis</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <main class="container">
        <section class="hero animate">
            <h1 class="animate delay-1">Transforma tu Menú Físico en una <span>Experiencia Digital</span></h1>
            <p class="animate delay-2">La forma más rápida, elegante y profesional para restaurantes, cafeterías y bares de digitalizar su carta y conectar con sus clientes.</p>
            <div class="animate delay-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem;">Ir al Panel de Control</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem;">Crea tu Carta Ahora</a>
                @endauth
            </div>
        </section>

        <section class="features">
            <div class="feature-card animate delay-1">
                <div class="icon-box">📱</div>
                <h3>Sin Apps, Solo QR</h3>
                <p>Tus clientes solo necesitan escanear un código QR con su cámara. Sin descargar nada, rápido y directo a su mesa.</p>
            </div>
            <div class="feature-card animate delay-2">
                <div class="icon-box">⚡</div>
                <h3>Actualización Instantánea</h3>
                <p>¿Cambió un precio o se agotó un plato? Actualiza tu stock y precios en segundos desde tu panel administrativo.</p>
            </div>
            <div class="feature-card animate delay-2">
                <div class="icon-box">🎨</div>
                <h3>Diseño Premium</h3>
                <p>Cartas elegantes y modernas que resaltan tus platos. La primera impresión entra por los ojos.</p>
            </div>
            <div class="feature-card animate delay-1">
                <div class="icon-box">🚀</div>
                <h3>Multi-negocio</h3>
                <p>Gestiona una o varias sucursales desde una sola cuenta. Ideal para franquicias o diversos emprendimientos gastronómicos.</p>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Carta Digital. Todos los derechos reservados.</p>
            <p style="margin-top: 0.5rem; font-size: 0.8rem; opacity: 0.6;">Hecho para digitalizar la gastronomía.</p>
        </div>
    </footer>

</body>
</html>
