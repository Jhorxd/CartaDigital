<!DOCTYPE html>
<html lang="es" x-data="themeManager" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->name }} | Carta Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        primary: '#f97316', // Color naranja para botones y detalles
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .dark .glass { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.05); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .product-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .product-card:active { transform: scale(0.96); }
        .gradient-bg { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen font-sans selection:bg-primary selection:text-white" x-data="cartManager">

    <!-- Theme Switcher & Header Floating -->
    <div class="fixed top-4 right-4 z-50 flex gap-2">
        <button @click="toggleTheme" class="p-3 rounded-full glass shadow-xl hover:scale-110 transition active:scale-90">
            <template x-if="!darkMode">
                <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </template>
            <template x-if="darkMode">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.071 16.071l.707.707M7.757 7.757l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
            </template>
        </button>
    </div>

    <!-- Hero Section -->
    <header class="relative h-72 w-full overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 hover:scale-105" 
             style="background-image: url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80')">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-50 dark:from-slate-950 via-transparent to-black/20"></div>
        
        <div class="absolute bottom-6 left-6 right-6 flex items-end gap-5">
            <div class="relative">
                @if($tenant->logo)
                    <img src="{{ $tenant->logo }}" class="w-24 h-24 rounded-2xl border-4 border-white dark:border-slate-900 shadow-2xl object-cover bg-white">
                @else
                    <div class="w-24 h-24 rounded-2xl border-4 border-white dark:border-slate-900 shadow-2xl gradient-bg flex items-center justify-center text-white text-3xl font-bold">
                        {{ substr($tenant->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="flex-1 pb-2">
                <h1 class="text-3xl font-bold drop-shadow-md text-slate-900 dark:text-white">{{ $tenant->name }}</h1>
                <p class="text-sm opacity-80 flex items-center gap-1 mt-1">
                    <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                    {{ $tenant->address ?? 'Restaurante Local' }}
                </p>
            </div>
        </div>
    </header>

    <!-- Info Bars -->
    <div class="px-6 py-4 flex gap-4 overflow-x-auto hide-scrollbar">
        <div class="flex items-center gap-2 whitespace-nowrap bg-white dark:bg-slate-900 px-4 py-2 rounded-full shadow-sm text-xs font-semibold border border-slate-100 dark:border-slate-800">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            Abierto ahora
        </div>
        <div class="flex items-center gap-2 whitespace-nowrap bg-white dark:bg-slate-900 px-4 py-2 rounded-full shadow-sm text-xs font-semibold border border-slate-100 dark:border-slate-800">
            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ $tenant->schedule ?? '12pm - 10pm' }}
        </div>
        <a href="https://wa.me/{{ $tenant->whatsapp }}" target="_blank" class="flex items-center gap-2 whitespace-nowrap bg-white dark:bg-slate-900 px-4 py-2 rounded-full shadow-sm text-xs font-semibold border border-slate-100 dark:border-slate-800 text-green-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-4.821 4.73c-2.197 0-4.342-.58-6.225-1.685L4 18.22l1.83-5.35c-1.21-2.083-1.847-4.467-1.847-6.902 0-7.407 6.04-13.447 13.448-13.447 3.608 0 7.001 1.405 9.554 3.958 2.553 2.553 3.957 5.946 3.957 9.554 0 7.408-6.04 13.45-13.448 13.45m0-25.26C5.435.352.004 5.783.004 12.51c0 2.15.56 4.247 1.626 6.115L0 24l5.545-1.455a12.1 12.1 0 005.811 1.493c6.726 0 12.158-5.43 12.158-12.157 0-3.26-1.27-6.324-3.575-8.63C17.636 1.147 14.57.352 11.306.352z"/></svg>
            Chat
        </a>
    </div>

    <!-- Sticky Tabs Navigation -->
    <nav class="sticky top-0 z-40 glass shadow-sm px-6 py-2 overflow-x-auto hide-scrollbar">
        <div class="flex gap-6 items-center">
            @foreach($categories as $category)
            <button @click="activeCategory = '{{ $category->id }}'" 
               class="whitespace-nowrap py-2 text-sm font-semibold transition-all border-b-2 border-transparent hover:border-primary hover:text-primary active:scale-95"
               :class="activeCategory == '{{ $category->id }}' ? 'border-primary text-primary scale-105 font-bold' : 'text-slate-500 dark:text-slate-400'">
                {{ $category->name }}
            </button>
            @endforeach
        </div>
    </nav>

    <!-- Menu Content -->
    <main class="p-6 space-y-8 pb-32">
        @foreach($categories as $category)
        <section x-cloak x-show="activeCategory == '{{ $category->id }}'" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="space-y-6">
            
            <div class="flex items-center gap-3">
                <div class="w-1 h-8 gradient-bg rounded-full"></div>
                <h2 class="text-2xl font-bold tracking-tight">{{ $category->name }}</h2>
            </div>
            
            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($category->products as $product)
                <div class="product-card flex bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl border border-slate-100 dark:border-slate-800">
                    <!-- Product Image -->
                    <div class="w-28 sm:w-36 h-auto shrink-0 relative overflow-hidden group">
                        @if($product->image)
                            <img src="{{ $product->image }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                        @else
                            <div class="w-full h-full gradient-bg opacity-10 flex items-center justify-center">
                                <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        @if($product->is_popular)
                            <span class="absolute top-2 left-2 bg-yellow-400 text-black text-[10px] font-bold px-2 py-0.5 rounded-full shadow-lg">POPULAR</span>
                        @endif
                    </div>
                    
                    <!-- Product Info -->
                    <div class="flex-1 p-4 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-lg leading-tight">{{ $product->name }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2 leading-relaxed">
                                {{ $product->description ?? 'Sin descripción disponible.' }}
                            </p>
                        </div>
                        
                        <div class="flex justify-between items-end mt-3">
                            <div class="text-xl font-bold text-primary">
                                <span class="text-sm font-normal">S/</span>{{ number_format($product->price, 2) }}
                            </div>
                            <button @click="addToCart({ 
                                id: {{ $product->id }}, 
                                name: '{{ $product->name }}', 
                                price: {{ $product->price }}, 
                                image: '{{ $product->image ?? '' }}' 
                            })" 
                            class="gradient-bg text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg shadow-orange-500/20 active:scale-90 transition transform flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Añadir
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($category->products->isEmpty())
                <div class="text-center py-20 opacity-30">
                    <p class="font-bold">No hay productos en esta categoría.</p>
                </div>
            @endif
        </section>
        @endforeach
    </main>

    <!-- Floating Cart & Drawer -->
    <div x-cloak x-show="totalItems > 0" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-y-20 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         class="fixed bottom-6 left-6 right-6 z-50 flex justify-center">
        
        <button @click="openDrawer = true" 
                class="gradient-bg px-8 py-4 rounded-full shadow-2xl flex items-center gap-4 text-white hover:scale-105 active:scale-95 transition-all w-full max-w-sm">
            <div class="relative">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span class="absolute -top-2 -right-2 bg-white text-primary text-xs font-black rounded-full w-5 h-5 flex items-center justify-center animate-bounce shadow-md" x-text="totalItems"></span>
            </div>
            <div class="text-left flex-1">
                <p class="text-[10px] uppercase font-bold opacity-80 leading-none">Ver mipedido</p>
                <p class="text-lg font-black leading-none">S/ <span x-text="totalPrice.toFixed(2)"></span></p>
            </div>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>

    <!-- Side Drawer (Cart) -->
    <div x-cloak x-init="$watch('openDrawer', value => { if (value) document.body.style.overflow = 'hidden'; else document.body.style.overflow = 'auto'; })"
         x-show="openDrawer" class="fixed inset-0 z-[60] overflow-hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="openDrawer = false"></div>
        <div class="absolute inset-y-0 right-0 max-w-full flex pl-10" 
             x-transition:enter="transform transition ease-in-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">
            <div class="w-screen max-w-md">
                <div class="h-full flex flex-col bg-slate-50 dark:bg-slate-900 shadow-2xl">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
                        <h2 class="text-xl font-black">Tu Pedido</h2>
                        <button @click="openDrawer = false" class="p-2 rounded-full hover:bg-slate-200 dark:hover:bg-slate-800 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-6 space-y-4">
                        <template x-for="item in cart" :key="item.id">
                            <div class="flex items-center gap-4 bg-white dark:bg-slate-800 p-3 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                                <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0">
                                    <img :src="item.image || 'https://via.placeholder.com/100'" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-sm" x-text="item.name"></h4>
                                    <p class="text-primary font-bold text-sm">S/ <span x-text="item.price.toFixed(2)"></span></p>
                                </div>
                                <div class="flex flex-col items-center gap-1 bg-slate-100 dark:bg-slate-700 rounded-lg p-1">
                                    <button @click="addToCart(item)" class="p-1 hover:text-primary transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></button>
                                    <span class="text-xs font-black" x-text="item.quantity"></span>
                                    <button @click="removeFromCart(item.id)" class="p-1 hover:text-red-500 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg></button>
                                </div>
                            </div>
                        </template>
                        <template x-if="cart.length === 0">
                            <div class="text-center py-20 opacity-40">
                                <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <p class="mt-4 font-bold">Carrito vacío</p>
                            </div>
                        </template>
                    </div>

                    <div class="p-6 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 space-y-4">
                        <div class="flex justify-between items-center text-xl font-black">
                            <span>Total</span>
                            <span class="text-primary">S/ <span x-text="totalPrice.toFixed(2)"></span></span>
                        </div>
                        <button @click="sendToWhatsApp" class="w-full gradient-bg py-4 rounded-2xl text-white font-black shadow-xl shadow-orange-500/20 active:scale-95 transition flex items-center justify-center gap-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-4.821 4.73c-2.197 0-4.342-.58-6.225-1.685L4 18.22l1.83-5.35c-1.21-2.083-1.847-4.467-1.847-6.902 0-7.407 6.04-13.447 13.448-13.447 3.608 0 7.001 1.405 9.554 3.958 2.553 2.553 3.957 5.946 3.957 9.554 0 7.408-6.04 13.45-13.448 13.45m0-25.26C5.435.352.004 5.783.004 12.51c0 2.15.56 4.247 1.626 6.115L0 24l5.545-1.455a12.1 12.1 0 005.811 1.493c6.726 0 12.158-5.43 12.158-12.157 0-3.26-1.27-6.324-3.575-8.63C17.636 1.147 14.57.352 11.306.352z"/></svg>
                            Confirmar por WhatsApp
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('themeManager', () => ({
                darkMode: localStorage.getItem('darkMode') === 'true',
                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('darkMode', this.darkMode);
                }
            }));

            Alpine.data('cartManager', () => ({
                cart: [],
                activeCategory: '{{ $categories->first()->id ?? 0 }}',
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
                    let message = "*Nuevo Pedido - {{ $tenant->name }}*\n\n";
                    this.cart.forEach(item => {
                        message += `• ${item.quantity}x ${item.name} - S/ ${(item.price * item.quantity).toFixed(2)}\n`;
                    });
                    message += `\n*Total: S/ ${this.totalPrice.toFixed(2)}*`;
                    
                    const url = `https://wa.me/{{ $tenant->whatsapp }}?text=${encodeURIComponent(message)}`;
                    window.open(url, '_blank');
                }
            }));
        });

        // Intersection Observer for Active Category Tabs
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section');
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute('id').replace('cat-', '');
                }
            });
            if (current) {
                const alpine = document.querySelector('[x-data="cartManager"]').__x.$data;
                alpine.activeCategory = current;
            }
        });
    </script>
</body>
</html>
