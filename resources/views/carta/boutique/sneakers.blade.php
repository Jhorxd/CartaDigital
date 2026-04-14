<!DOCTYPE html>
<html lang="es" x-data="themeManager" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { 
                        brand: '{{ $tenant->brand_color ?? "#000000" }}'
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        :root { --brand: {{ $tenant->brand_color ?? '#000000' }}; }
        
        .bg-brand-primary { background-color: var(--brand); }
        .text-brand-primary { color: var(--brand); }
        .border-brand-primary { border-color: var(--brand); }

        /* Estilo Simple Card */
        .simple-card {
            background: #fff;
            border: 1px solid #f1f1f1;
            transition: all 0.2s ease;
        }
        .dark .simple-card {
            background: #111;
            border: 1px solid #222;
        }
        .simple-card:hover { border-color: var(--brand); }

        .image-container { aspect-ratio: 1/1; background: #f9f9f9; }
        .dark .image-container { background: #0a0a0a; }

        /* Grid adaptativo compacto */
        .shop-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        @media (min-width: 768px) {
            .shop-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 20px;
            }
        }
    </style>
</head>
<body class="antialiased font-sans bg-white text-slate-900 dark:bg-black dark:text-slate-100 transition-colors duration-300" x-data="cartManager">

    <!-- Navbar Simple -->
    <nav class="sticky top-0 z-50 bg-white/90 dark:bg-black/90 backdrop-blur-md border-b border-gray-100 dark:border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="#" class="font-bold text-xl tracking-tight uppercase border-b-2 border-brand-primary">
                {{ $tenant->name }}
            </a>

            <div class="flex items-center gap-3 md:gap-6">
                <!-- Theme Toggle Destacado -->
                <button @click="toggleTheme" 
                        class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 hover:border-brand-primary transition-all group">
                    <template x-if="!darkMode">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </template>
                    <template x-if="darkMode">
                        <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.071 16.071l.707.707M7.757 7.757l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
                    </template>
                </button>

                <button @click="openDrawer = true" class="relative group p-2">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span x-cloak x-show="totalItems > 0" class="absolute top-1 right-1 bg-brand-primary text-white text-[9px] font-black w-4.5 h-4.5 flex items-center justify-center rounded-full" x-text="totalItems"></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Header Urban Refinado -->
    <header class="relative w-full h-[400px] md:h-[600px] overflow-hidden flex items-center justify-center text-center">
        <!-- Imagen de Fondo con posición ajustada para ver las tabas -->
        <img src="{{ asset('img/hero.png') }}" 
             class="absolute inset-0 w-full h-full object-cover object-center md:object-[center_20%] transition-all duration-700 brightness-[0.9] dark:brightness-[1] dark:contrast-[1.2]" 
             alt="Urban Sneakers">
        
        <!-- Overlay Dinámico: En oscuro inyecta color de marca, en claro es limpio -->
        <div class="absolute inset-0 bg-white/10 dark:bg-brand-primary/20 mix-blend-multiply dark:mix-blend-color"></div>
        
        <!-- Degradado inferior para suavizar -->
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-white dark:to-black opacity-90"></div>

        <div class="relative z-10 px-6">
            <span class="text-[10px] font-black uppercase tracking-[0.7em] text-brand-primary mb-5 block drop-shadow-md">The Urban Revolution</span>
            <h1 class="text-5xl md:text-8xl font-black uppercase tracking-tighter italic leading-none mb-6 text-white drop-shadow-[0_10px_10px_rgba(0,0,0,0.5)]">
                Urban <span class="text-brand-primary">Footwear</span>
            </h1>
            <div class="h-2 w-32 bg-brand-primary mx-auto mb-10 shadow-[0_0_20px_rgba(var(--brand-rgb),0.5)]"></div>
            <p class="text-xs md:text-base text-white uppercase tracking-[0.3em] font-black max-w-xl mx-auto leading-relaxed drop-shadow-lg">
                Domina las calles. <br class="hidden md:block"> Estilo sin reglas, envíos a todo el país.
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-12" x-data="{ activeCat: {{ $categories->first()->id ?? 'null' }} }">
        
        <!-- Tabs Centradas -->
        <div class="flex justify-center flex-wrap gap-2 md:gap-4 mb-12">
            @foreach($categories as $category)
                <button @click="activeCat = {{ $category->id }}"
                        class="text-[10px] md:text-xs font-black uppercase tracking-widest whitespace-nowrap px-6 py-3 border-2 transition-all"
                        :class="activeCat === {{ $category->id }} ? 'bg-brand-primary text-white border-brand-primary' : 'bg-transparent text-slate-400 border-gray-100 dark:border-white/5 hover:border-slate-300'">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <!-- Grid Compacto Centrado -->
        <div class="shop-grid justify-items-center">
            @foreach($categories as $category)
                <template x-if="activeCat === {{ $category->id }}">
                    <div class="contents">
                        @foreach($category->products as $product)
                            <div class="simple-card rounded-lg overflow-hidden flex flex-col p-2">
                                <div class="image-container rounded-md overflow-hidden flex items-center justify-center">
                                    @if($product->image)
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                                    @else
                                        <svg class="w-12 h-12 text-slate-200" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"></path></svg>
                                    @endif
                                </div>
                                <div class="p-2 pt-4 flex-1 flex flex-col">
                                    <h3 class="text-xs font-bold uppercase truncate mb-1">{{ $product->name }}</h3>
                                    <p class="text-[10px] text-slate-400 uppercase font-medium mb-3">Model Drops</p>
                                    
                                    <div class="mt-auto flex items-center justify-between">
                                        <span class="text-sm font-bold text-brand-primary">S/ {{ number_format($product->price, 2) }}</span>
                                        <button @click="addToCart({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ $product->image }}' })"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-900 text-white dark:bg-white dark:text-black hover:bg-brand-primary transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </template>
            @endforeach
        </div>
    </main>

    <!-- Drawer Carrito Minimal -->
    <div x-cloak x-show="openDrawer" class="fixed inset-0 z-[100] overflow-hidden">
        <div x-show="openDrawer" x-transition.opacity class="absolute inset-0 bg-slate-900/60" @click="openDrawer = false"></div>
        <div x-show="openDrawer" 
             x-transition:enter="transform transition ease-out duration-300" x-transition:enter-start="translate-x-full" 
             class="absolute inset-y-0 right-0 w-full max-w-xs bg-white dark:bg-black flex flex-col border-l border-gray-100 dark:border-white/10">
            <div class="p-6 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                <h2 class="font-bold uppercase text-sm">Resumen</h2>
                <button @click="openDrawer = false"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="flex-1 overflow-y-auto p-6 space-y-4">
                <template x-for="item in cart" :key="item.id">
                    <div class="flex gap-3 border-b border-gray-50 dark:border-white/5 pb-3">
                        <div class="w-12 h-12 bg-gray-50 dark:bg-zinc-900 rounded-md overflow-hidden flex items-center justify-center flex-shrink-0">
                            <img :src="item.image" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-bold uppercase truncate" x-text="item.name"></p>
                            <p class="text-[10px] font-bold text-brand-primary mt-1">S/ <span x-text="item.price.toFixed(0)"></span> x <span x-text="item.quantity"></span></p>
                        </div>
                        <button @click="removeFromCart(item.id)" class="text-slate-300 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                    </div>
                </template>
            </div>
            <div class="p-6 bg-gray-50 dark:bg-zinc-900 space-y-4">
                <div class="flex justify-between font-bold text-sm uppercase"><span>Total</span><span>S/ <span x-text="totalPrice.toFixed(0)"></span></span></div>
                <input type="text" x-model="clientDetails" placeholder="NOMBRE / DIRECCIÓN" class="w-full border-gray-200 dark:border-white/10 p-3 text-[10px] uppercase focus:border-brand-primary outline-none bg-white dark:bg-black">
                <button @click="sendToWhatsApp" class="w-full bg-brand-primary text-white py-3 font-bold text-xs uppercase tracking-widest hover:opacity-90">Ordenar vía WhatsApp</button>
            </div>
        </div>
    </div>

    <footer class="py-12 px-4 border-t border-gray-100 dark:border-white/5 text-center">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">© {{ date('Y') }} {{ $tenant->name }} • Simple E-commerce</p>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('themeManager', () => ({
                darkMode: localStorage.getItem('darkMode') === 'true',
                toggleTheme() { this.darkMode = !this.darkMode; localStorage.setItem('darkMode', this.darkMode); }
            }));
            Alpine.data('cartManager', () => ({
                cart: [], clientDetails: '', openDrawer: false,
                addToCart(product) { const e = this.cart.find(i => i.id === product.id); if (e) e.quantity++; else this.cart.push({...product, quantity: 1}); },
                removeFromCart(id) { const i = this.cart.findIndex(x => x.id === id); if (i !== -1) { if (this.cart[i].quantity > 1) this.cart[i].quantity--; else this.cart.splice(i, 1); } },
                get totalItems() { return this.cart.reduce((s, i) => s + i.quantity, 0); },
                get totalPrice() { return this.cart.reduce((s, i) => s + (i.price * i.quantity), 0); },
                sendToWhatsApp() {
                    const m = `📦 *PEDIDO: {{ $tenant->name }}*\n\n` + this.cart.map(i => `• ${i.quantity}x ${i.name.toUpperCase()} (S/ ${i.price})`).join('\n') + `\n\n*TOTAL: S/ ${this.totalPrice}*`;
                    window.open(`https://wa.me/51{{ $tenant->whatsapp }}?text=${encodeURIComponent(m)}`, '_blank');
                }
            }));
        });
    </script>
</body>
</html>
