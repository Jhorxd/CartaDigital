<!DOCTYPE html>
<html lang="es" x-data="themeManager" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tenant->name }} | Colección Exclusiva</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;800&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Montserrat', 'sans-serif'], serif: ['Cinzel', 'serif'] },
                    colors: { brand: 'var(--brand-color)' }
                }
            }
        }
    </script>

    <style>
        :root { --brand-color: {{ $tenant->brand_color ?? '#cda25e' }}; }
        .bg-brand { background-color: var(--brand-color); }
        .text-brand { color: var(--brand-color); }
        .border-brand { border-color: var(--brand-color); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Glass effect for toggle */
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.4); }
        .dark .glass { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); }

        .luxury-card {
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }
        .luxury-card:hover { transform: translateY(-8px); }
        .dark .luxury-card:hover { box-shadow: 0 20px 40px rgba(0,0,0,0.8), 0 0 20px rgba(205, 162, 94, 0.1); }
        .light .luxury-card { box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .light .luxury-card:hover { box-shadow: 0 20px 40px rgba(0,0,0,0.1), 0 0 20px rgba(205, 162, 94, 0.2); }
    </style>
</head>
<body class="antialiased min-h-screen relative font-sans transition-colors duration-500 selection:bg-brand selection:text-white bg-gray-50 text-gray-900 dark:bg-[#0a0a0a] dark:text-white pb-24" x-data="cartManager">

    <!-- Theme Switcher -->
    <div class="fixed top-6 right-6 z-50">
        <button @click="toggleTheme" class="p-3.5 rounded-full glass shadow-xl hover:scale-110 active:scale-95 transition-all text-gray-800 dark:text-white">
            <template x-if="!darkMode">
                <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </template>
            <template x-if="darkMode">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.071 16.071l.707.707M7.757 7.757l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
            </template>
        </button>
    </div>

    <!-- Atmospheric Background Blur -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-[-1]">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full opacity-10 dark:opacity-20 filter blur-[120px]" style="background-color: var(--brand-color);"></div>
        <div class="absolute top-[40%] -right-[10%] w-[40%] h-[50%] rounded-full opacity-[0.05] dark:opacity-10 filter blur-[150px]" style="background-color: var(--brand-color);"></div>
    </div>

    <!-- Navigation -->
    <nav class="sticky top-0 z-40 bg-white/70 dark:bg-black/60 backdrop-blur-xl border-b border-gray-200 dark:border-white/5 transition-all duration-300 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <div class="flex items-center gap-6">
                    @if($tenant->logo)
                        <img src="{{ Storage::url($tenant->logo) }}" alt="{{ $tenant->name }}" class="h-14 w-auto object-contain">
                    @else
                        <div class="h-12 w-12 rounded-sm border md:border-2 border-brand/40 dark:border-brand/50 flex items-center justify-center text-brand font-serif font-bold text-2xl shadow-[0_0_10px_rgba(0,0,0,0.05)] dark:shadow-[0_0_15px_rgba(205,162,94,0.2)]">
                            {{ substr($tenant->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl md:text-3xl font-serif font-bold tracking-widest text-gray-900 dark:text-white uppercase">{{ $tenant->name }}</h1>
                        <p class="text-[9px] md:text-xs text-brand uppercase tracking-[0.3em] font-medium mt-1">Colección Exclusiva</p>
                    </div>
                </div>
                
                @if($tenant->address || $tenant->schedule)
                <div class="hidden lg:flex flex-col items-end opacity-70 text-xs font-light tracking-wide space-y-1">
                    @if($tenant->address) <span>{{ $tenant->address }}</span> @endif
                    @if($tenant->schedule) <span class="text-brand">{{ $tenant->schedule }}</span> @endif
                </div>
                @endif
            </div>
        </div>
        <div class="h-[1px] w-full bg-gradient-to-r from-transparent via-brand to-transparent opacity-30 dark:opacity-50 absolute bottom-0"></div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16" x-data="{ activeTab: {{ $categories->first()->id ?? 'null' }} }">
        
        @if($categories->count() > 0)
            <!-- Elegant Tabs -->
            <div class="mb-16 flex flex-col items-center">
                <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 dark:text-white/40 mb-8 font-medium">Nuestras Galerías</h2>
                <div class="w-full overflow-x-auto hide-scrollbar">
                    <div class="flex gap-4 md:gap-8 justify-start md:justify-center whitespace-nowrap min-w-max px-2 border-b border-gray-200 dark:border-white/10">
                        @foreach($categories as $category)
                            <button 
                                @click="activeTab = {{ $category->id }}"
                                class="relative pb-4 text-sm md:text-base tracking-[0.1em] uppercase font-bold transition-all duration-300 focus:outline-none"
                                :class="activeTab === {{ $category->id }} ? 'text-brand' : 'text-gray-400 dark:text-white/40 hover:text-gray-900 dark:hover:text-white/80'"
                            >
                                {{ $category->name }}
                                <div class="absolute bottom-[-1px] left-0 w-full h-[2px] bg-brand transform origin-left transition-transform duration-300"
                                     :class="activeTab === {{ $category->id }} ? 'scale-x-100' : 'scale-x-0'"></div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Perfume Showcase Grid -->
            <div class="min-h-[500px]">
                @foreach($categories as $category)
                    <div x-show="activeTab === {{ $category->id }}"
                         x-transition:enter="transition ease-out duration-700 delay-100"
                         x-transition:enter-start="opacity-0 transform translate-y-8"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-16"
                         style="display: none;">
                        
                        @foreach($category->products as $product)
                            @if($product->is_active)
                                <div class="luxury-card light bg-white dark:bg-gradient-to-br dark:from-[#161616] dark:to-[#0e0e0e] rounded-sm overflow-hidden flex flex-col group border border-gray-100 dark:border-white/5 relative">
                                    <div class="absolute top-0 left-0 w-4 h-4 border-t border-l border-brand opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10 m-2"></div>
                                    <div class="absolute top-0 right-0 w-4 h-4 border-t border-r border-brand opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10 m-2"></div>
                                    
                                    <div class="relative aspect-[3/4] overflow-hidden bg-gray-50 dark:bg-black flex items-center justify-center p-8">
                                        <div class="absolute inset-0 bg-gradient-to-t from-gray-100 dark:from-[#121212] to-transparent opacity-80 z-0"></div>
                                        
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover relative z-10 group-hover:scale-110 transition-transform duration-1000 ease-in-out drop-shadow-2xl">
                                        @else
                                            <div class="relative z-10 w-full h-full flex flex-col items-center justify-center text-gray-300 dark:text-brand/20 group-hover:text-brand/40 transition-colors duration-500">
                                                <svg class="w-24 h-24 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 22.5A3 3 0 0 0 22.5 19.5v-3.375c0-.986-.4-1.92-1.11-2.61l-5.64-5.55a1.5 1.5 0 0 0-1.5-.42L12 8A4.5 4.5 0 0 0 7.5 19.5v3H19.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="m15.5 8-.5-1.5a1.5 1.5 0 0 0-.42-1.5L9 2.25A3 3 0 0 0 6 5.25v3.375c0 .986.4 1.92 1.11 2.61l5.64 5.55a1.5 1.5 0 0 0 1.5.42L16.5 16A4.5 4.5 0 0 0 21 4.5v-3H9" /></svg>
                                            </div>
                                        @endif

                                        <div class="absolute top-4 right-4 z-20">
                                            <span class="text-[10px] uppercase tracking-widest font-bold px-3 py-1 border border-brand/50 text-brand bg-white/80 dark:bg-transparent backdrop-blur-md rounded-sm">Disponible</span>
                                        </div>
                                    </div>
                                    
                                    <div class="p-6 md:p-8 flex-1 flex flex-col items-center text-center bg-white dark:bg-[#121212] z-10">
                                        <h3 class="font-serif text-lg md:text-xl text-gray-900 dark:text-white mb-3 tracking-wide group-hover:text-brand transition-colors duration-300 leading-snug">{{ $product->name }}</h3>
                                        
                                        @if($product->description)
                                            <p class="text-[11px] text-gray-500 dark:text-white/50 mb-6 font-medium dark:font-light leading-relaxed flex-1 tracking-wide">{{ $product->description }}</p>
                                        @endif
                                        
                                        <div class="mt-auto w-full pt-6 border-t border-gray-100 dark:border-white/5 flex flex-col items-center gap-4">
                                            <span class="text-xl font-bold tracking-widest text-gray-900 dark:text-white">S/ {{ number_format($product->price, 2) }}</span>
                                            
                                            <button @click.prevent="addToCart({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ Storage::url($product->image) }}' })"
                                               class="w-full py-3 px-6 bg-transparent border border-brand text-brand hover:bg-brand hover:text-white dark:hover:text-black transition-all duration-300 text-[10px] uppercase tracking-[0.2em] font-bold text-center rounded-sm">
                                                Añadir al Carrito
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-32 opacity-30">
                <svg class="w-16 h-16 text-gray-900 dark:text-white mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <p class="text-lg font-serif tracking-widest">Próximamente</p>
            </div>
        @endif
    </main>

    <!-- Floating Cart Icon -->
    <div x-cloak x-show="totalItems > 0" 
         x-transition:enter="transition ease-out duration-500 transform"
         x-transition:enter-start="translate-y-24 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         class="fixed bottom-8 right-6 z-[60]">
        
        <button @click="openDrawer = true" class="relative w-16 h-16 rounded-full bg-brand text-black shadow-[0_0_20px_rgba(205,162,94,0.4)] flex items-center justify-center hover:scale-105 active:scale-95 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <div class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-white text-black text-[10px] font-bold flex items-center justify-center border border-black/10" x-text="totalItems"></div>
        </button>
    </div>

    <!-- Elegant Cart Drawer Overlay -->
    <div x-cloak x-show="openDrawer" class="fixed inset-0 z-[70] flex justify-end" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div x-show="openDrawer" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="openDrawer = false"></div>

        <div x-show="openDrawer" 
             x-transition:enter="transform transition ease-in-out duration-500"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-500"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="relative w-full max-w-md h-full bg-white dark:bg-[#0a0a0a] shadow-2xl flex flex-col border-l border-gray-200 dark:border-white/5">
            
            <div class="px-6 h-24 flex items-center justify-between border-b border-gray-100 dark:border-white/5 shrink-0 bg-gray-50 dark:bg-black/40">
                <div>
                    <h2 class="font-serif text-xl tracking-widest text-gray-900 dark:text-white">Cesta de Compra</h2>
                </div>
                <button @click="openDrawer = false" class="w-10 h-10 rounded-full flex items-center justify-center bg-white dark:bg-[#121212] border border-gray-200 dark:border-white/10 text-gray-400 hover:text-brand transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-8 space-y-6 hide-scrollbar">
                <template x-for="item in cart" :key="item.id">
                    <div class="flex items-center gap-4 bg-gray-50 dark:bg-[#121212] p-3 rounded-sm border border-gray-100 dark:border-white/5">
                        <div class="w-16 h-16 shrink-0 bg-white dark:bg-black overflow-hidden flex items-center justify-center p-1 border border-gray-100 dark:border-white/5">
                            <template x-if="item.image">
                                <img :src="item.image" class="w-full h-full object-cover">
                            </template>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-sans text-sm font-semibold tracking-wide text-gray-900 dark:text-white truncate" x-text="item.name"></h4>
                            <p class="text-brand font-medium text-xs mt-1 tracking-widest">S/ <span x-text="(item.price * item.quantity).toFixed(2)"></span></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="removeFromCart(item.id)" class="w-7 h-7 flex items-center justify-center rounded-sm bg-gray-200 dark:bg-black text-gray-600 dark:text-gray-400 hover:text-brand transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            </button>
                            <span class="text-xs font-semibold w-4 text-center text-gray-900 dark:text-white" x-text="item.quantity"></span>
                            <button @click="addToCart(item)" class="w-7 h-7 flex items-center justify-center rounded-sm bg-gray-200 dark:bg-black text-gray-600 dark:text-gray-400 hover:text-brand transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>
                    </div>
                </template>
                
                <template x-if="cart.length === 0">
                    <div class="text-center py-16 opacity-40">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <p class="text-[10px] tracking-widest uppercase">Cesta Vacia</p>
                    </div>
                </template>
            </div>

            <div class="p-6 bg-white dark:bg-[#0a0a0a] border-t border-gray-100 dark:border-white/5 space-y-6">
                <div class="flex justify-between items-end">
                    <p class="text-[10px] font-semibold uppercase text-gray-400 tracking-widest">Total Estimado</p>
                    <p class="text-xl font-serif text-gray-900 dark:text-white">S/ <span x-text="totalPrice.toFixed(2)"></span></p>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Nombre / Dirección</label>
                    <input type="text" x-model="clientDetails" placeholder="Ej: Juan Perez - Envío a Miraflores..."
                        class="w-full bg-gray-50 dark:bg-[#121212] border border-gray-200 dark:border-white/10 rounded-sm px-4 py-3 text-xs font-semibold focus:border-brand focus:ring-0 transition-all text-gray-900 dark:text-white placeholder:text-gray-400">
                </div>

                <button @click="sendToWhatsApp" class="w-full py-4 bg-brand text-black hover:bg-white transition-all duration-300 text-[11px] uppercase tracking-[0.2em] font-bold text-center rounded-sm shadow-[0_0_20px_rgba(205,162,94,0.2)]">
                    Solicitar Compra
                </button>
            </div>
        </div>
    </div>

    <!-- Exquisite Footer -->
    <footer class="border-t border-gray-200 dark:border-white/10 mt-12 bg-gray-50 dark:bg-black py-12">
        <div class="max-w-7xl mx-auto px-4 flex flex-col items-center justify-center text-center">
            <h2 class="font-serif text-2xl text-gray-900 dark:text-white mb-4 tracking-widest">{{ $tenant->name }}</h2>
            <p class="text-xs text-gray-500 dark:text-white/40 tracking-[0.2em] font-medium dark:font-light mb-8 uppercase">La esencia de la elegancia</p>
            <div class="h-[1px] w-24 bg-brand/50 mb-8"></div>
            <p class="text-[10px] text-gray-400 dark:text-white/20 tracking-widest uppercase">© {{ date('Y') }} Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('themeManager', () => ({
                darkMode: localStorage.getItem('darkMode') ? localStorage.getItem('darkMode') === 'true' : true,
                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('darkMode', this.darkMode);
                }
            }));

            Alpine.data('cartManager', () => ({
                cart: [],
                clientDetails: '',
                openDrawer: false,
                
                addToCart(product) {
                    const existing = this.cart.find(i => i.id === product.id);
                    if (existing) {
                        existing.quantity++;
                    } else {
                        this.cart.push({ ...product, quantity: 1 });
                    }
                },
                
                removeFromCart(productId) {
                    const index = this.cart.findIndex(i => i.id === productId);
                    if (index !== -1) {
                        if (this.cart[index].quantity > 1) {
                            this.cart[index].quantity--;
                        } else {
                            this.cart.splice(index, 1);
                        }
                    }
                },
                
                get totalItems() {
                    return this.cart.reduce((sum, item) => sum + item.quantity, 0);
                },
                
                get totalPrice() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },
                
                sendToWhatsApp() {
                    if (this.cart.length === 0) return;

                    let message = `*Nueva Solicitud - {{ $tenant->name }}*\n\n`;

                    if (this.clientDetails.trim()) {
                        message += `👤 *Cliente/Envío:* ${this.clientDetails.trim()}\n\n`;
                    }

                    this.cart.forEach(item => {
                        message += `• ${item.quantity}x ${item.name} - S/ ${(item.price * item.quantity).toFixed(2)}\n`;
                    });
                    
                    message += `\n*TOTAL:* S/ ${this.totalPrice.toFixed(2)}`;

                    const url = `https://wa.me/51{{ $tenant->whatsapp }}?text=${encodeURIComponent(message)}`;
                    window.open(url, '_blank');
                }
            }));
        });
    </script>
</body>
</html>
