<!DOCTYPE html>
<html lang="es" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tenant->name }} | Boutique de Arte Floral</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], serif: ['Playfair Display', 'serif'] },
                    colors: { brand: '{{ $tenant->brand_color ?? "#e11d48" }}' }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .body-bg { 
            background: linear-gradient(rgba(255, 255, 255, 0.96), rgba(255, 255, 255, 0.96)), url('/luxury_floral_pattern_bg_1776282721770.png'); 
            background-attachment: fixed; background-size: cover; 
        }
        .dark .body-bg { 
            background: linear-gradient(rgba(10, 10, 10, 0.93), rgba(10, 10, 10, 0.93)), url('/luxury_floral_pattern_bg_1776282721770.png'); 
            background-attachment: fixed; background-size: cover; 
        }

        .luxury-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.4); transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
        .dark .luxury-card { background: rgba(30,30,30,0.4); border: 1px solid rgba(255,255,255,0.05); }
        .luxury-card:hover { transform: translateY(-8px); border-color: var(--brand-color); box-shadow: 0 25px 50px -12px rgba(225, 29, 72, 0.15); }

        .floating-cart { 
            position: fixed; bottom: 30px; right: 30px; z-index: 100;
            width: 70px; height: 70px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 20px 50px rgba(225, 29, 72, 0.35);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .floating-cart:hover { transform: scale(1.1) rotate(5deg); }

        .hero-bg { background-image: url('/boutique_flowers_header_1776283190160.png'); background-size: cover; background-position: center; }

        .price-badge { background: white; border-radius: 1rem; padding: 6px 16px; box-shadow: 0 10px 25px -5px rgba(225, 29, 72, 0.1); border: 1px solid rgba(225, 29, 72, 0.05); }
        .dark .price-badge { background: #161616; border-color: rgba(255,255,255,0.1); }
        .price-currency { font-weight: 500; font-size: 0.65em; margin-right: 2px; vertical-align: baseline; opacity: 0.7; }
        .price-amount { font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.02em; }
    </style>
</head>
<body class="antialiased transition-colors duration-500 body-bg text-gray-900 dark:text-gray-100" 
      x-data="cartStore" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">

    @php
        $allProductsData = $categories->flatMap(fn($cat) => $cat->products->where('is_active', true))->unique('id')->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => (float)$p->price, 'image' => $p->image, 'description' => $p->description])->values();
    @endphp

    <nav class="sticky top-0 z-50 bg-white/60 dark:bg-black/40 backdrop-blur-3xl border-b border-brand/5">
        <div class="max-w-7xl mx-auto px-4 md:px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-4">
                @if($tenant->logo) <img src="{{ $tenant->logo }}" class="h-10 md:h-14 w-auto scale-105"> @else <div class="w-10 h-10 bg-brand rounded-lg flex items-center justify-center text-white font-black text-xl"> {{ substr($tenant->name, 0, 1) }} </div> @endif
                <div class="text-left"> <h1 class="text-xl md:text-2xl font-serif font-black tracking-tighter leading-none">{{ $tenant->name }}</h1> <p class="text-[8px] font-bold text-brand uppercase tracking-[0.4em]">Boutique de Arte Floral</p> </div>
            </div>
            <button @click="darkMode = !darkMode" class="p-2 text-brand">
                <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.071 16.071l.707.707M7.757 7.757l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
            </button>
        </div>
    </nav>

    <header class="relative h-[45vh] md:h-[55vh] flex items-center justify-center overflow-hidden hero-bg">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative z-10 text-center px-4 max-w-4xl space-y-4">
            <h2 class="text-5xl md:text-8xl font-serif font-black text-white leading-[0.8] tracking-tighter drop-shadow-2xl"> Arte Floral <br> <span class="text-brand italic italic">Boutique</span> </h2>
            <div class="flex justify-center"> <div class="h-1 w-20 bg-brand rounded-full my-4 shadow-lg shadow-brand/20"></div> </div>
            <p class="text-white text-[10px] md:text-sm font-bold tracking-[0.5em] uppercase opacity-90">Donde cada pétalo cuenta una historia</p>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-12 pb-32" x-data="{ searchQuery: '', activeTab: {{ $categories->first()->id ?? 0 }} }">
        <div class="mb-16 space-y-8">
            <div class="max-w-xl mx-auto relative group">
                <input type="text" x-model="searchQuery" placeholder="Buscar mi arreglo favorito..." class="w-full bg-white/80 dark:bg-zinc-800 rounded-full px-10 py-4 text-lg shadow-xl border-none focus:ring-2 focus:ring-brand dark:text-white transition-all">
                <div class="absolute right-8 top-1/2 -translate-y-1/2 text-brand"> <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> </div>
            </div>
            <div class="flex gap-2 overflow-x-auto hide-scrollbar pb-1 justify-start md:justify-center" x-show="searchQuery === ''">
                @foreach($categories as $category)
                    <button @click="activeTab = {{ $category->id }}" class="whitespace-nowrap px-8 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="activeTab === {{ $category->id }} ? 'bg-brand text-white shadow-xl scale-105' : 'bg-white/50 dark:bg-zinc-800/50 text-gray-500 border border-brand/5'"> {{ $category->name }} </button>
                @endforeach
            </div>
        </div>

        <div class="min-h-[400px]">
            @foreach($categories as $category)
                <div x-show="activeTab === {{ $category->id }} && searchQuery === ''" x-transition class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-10">
                    @foreach($category->products as $product)
                        @if($product->is_active)
                            <div class="luxury-card rounded-[2.5rem] overflow-hidden flex flex-col group relative">
                                <div class="relative aspect-[3/4] overflow-hidden bg-gray-50 dark:bg-zinc-800 flex items-center justify-center">
                                    @if($product->image) <img src="{{ $product->image }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"> @else <div class="flex flex-col items-center gap-3 opacity-20"> <svg class="w-16 h-16 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1" d="M12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2Z"></path></svg> <span class="text-[8px] font-black uppercase tracking-widest">Arte Floral</span> </div> @endif
                                    <div class="absolute top-4 left-4 z-10">
                                        <div class="price-badge transform -rotate-1 group-hover:rotate-3 transition-transform"> <span class="price-currency text-brand">S/</span> <span class="price-amount text-gray-900 dark:text-white text-base md:text-xl">{{ number_format($product->price, 2) }}</span> </div>
                                    </div>
                                </div>
                                <div class="p-5 md:p-8 flex-1 flex flex-col gap-3">
                                    <h4 class="text-sm md:text-2xl font-serif font-bold leading-tight line-clamp-2 md:group-hover:text-brand transition-colors">{{ $product->name }}</h4>
                                    @if($product->description) <p class="text-[9px] md:text-xs text-gray-400 dark:text-gray-500 leading-relaxed line-clamp-2 italic">{{ $product->description }}</p> @endif
                                    <button @click="addToCart({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ $product->image }}' })" class="mt-auto w-full py-3 bg-brand text-white font-black text-[9px] uppercase tracking-widest rounded-xl shadow-lg hover:bg-black transition-all"> Elegir </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach

            <div x-cloak x-show="searchQuery !== ''" x-transition>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-10">
                    <template x-for="product in getFilteredProducts(searchQuery)" :key="product.id">
                        <div class="luxury-card rounded-[2.5rem] overflow-hidden flex flex-col">
                            <div class="relative aspect-[3/4] overflow-hidden bg-gray-50 flex items-center justify-center">
                                <template x-if="product.image"><img :src="product.image" class="absolute inset-0 w-full h-full object-cover"></template>
                            </div>
                            <div class="p-8 space-y-4 text-center"> 
                                <h4 class="text-xl font-serif font-black" x-text="product.name"></h4> 
                                <div class="price-badge inline-block mx-auto mb-4"> <span class="price-currency text-brand">S/</span> <span class="price-amount text-gray-900 dark:text-white text-xl" x-text="parseFloat(product.price).toFixed(2)"></span> </div>
                                <button @click="addToCart(product)" class="w-full py-4 bg-brand text-white font-black text-[10px] uppercase tracking-widest rounded-2xl"> Seleccionar </button> 
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </main>

    <!-- Floating Carterita (ALWAYS VISIBLE) -->
    <button @click="openDrawer = true" class="floating-cart bg-brand text-white group">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        <!-- Dynamic Badge (Only visible if > 0) -->
        <div x-show="totalItems > 0" x-transition class="absolute -top-1 -right-1 bg-white text-brand text-[10px] w-6 h-6 rounded-full flex items-center justify-center font-black shadow-lg ring-2 ring-brand/10 animate-bounce" x-text="totalItems"></div>
    </button>

    <!-- Cart Drawer -->
    <div x-cloak x-show="openDrawer" class="fixed inset-0 z-[101] flex justify-end">
        <div x-show="openDrawer" @click="openDrawer = false" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-md"></div>
        <div x-show="openDrawer" x-transition:enter="transform transition duration-500" x-transition:enter-start="translate-x-full" 
             class="relative w-full max-w-md h-full bg-white dark:bg-[#0d0d0d] shadow-2xl flex flex-col">
            <div class="px-6 h-20 flex items-center justify-between shrink-0 border-b border-gray-100 dark:border-white/5">
                <h2 class="font-serif text-xl tracking-widest uppercase dark:text-white">CESTA DE COMPRA</h2>
                <button @click="openDrawer = false" class="text-gray-400 p-2"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            
            <!-- Empty state check -->
            <div class="flex-1 overflow-y-auto px-6 py-6 space-y-4 hide-scrollbar flex flex-col">
                <template x-if="cart.length === 0">
                    <div class="flex-1 flex flex-col items-center justify-center text-center space-y-4 opacity-40">
                        <div class="w-20 h-20 bg-gray-100 dark:bg-zinc-800 rounded-full flex items-center justify-center"> <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg></div>
                        <p class="font-serif italic text-lg dark:text-white">Tu cesta está vacía,<br>¡elige algo hermoso!</p>
                    </div>
                </template>

                <template x-for="item in cart" :key="item.id">
                    <div class="flex items-center gap-4 bg-gray-50/50 dark:bg-zinc-900/50 p-3 rounded-sm border border-gray-100 dark:border-white/5 shadow-sm">
                        <div class="w-16 h-16 shrink-0 bg-white overflow-hidden border border-gray-100 dark:border-white/5"> <template x-if="item.image"><img :src="item.image" class="w-full h-full object-cover"></template> </div>
                        <div class="flex-1 min-w-0"> <h4 class="font-bold text-xs text-gray-900 dark:text-white truncate" x-text="item.name"></h4> <p class="text-brand font-black text-xs mt-1">S/ <span x-text="item.price.toFixed(2)"></span></p> </div>
                        <div class="flex items-center gap-1.5"> <button @click="removeFromCart(item.id)" class="w-7 h-7 flex items-center justify-center bg-gray-200 dark:bg-black text-gray-500 rounded-sm text-xs">-</button> <span class="text-xs font-bold w-4 text-center dark:text-white" x-text="item.quantity"></span> <button @click="addToCart(item)" class="w-7 h-7 flex items-center justify-center bg-gray-200 dark:bg-black text-gray-500 rounded-sm text-xs">+</button> </div>
                    </div>
                </template>
            </div>

            <div x-show="cart.length > 0" class="p-6 bg-white dark:bg-[#0a0a0a] border-t border-gray-100 dark:border-white/5 space-y-4 shadow-inner">
                <div class="flex justify-between items-center px-1"> <p class="text-[9px] font-bold uppercase text-gray-400 tracking-widest">TOTAL ESTIMADO</p> <p class="text-2xl font-serif dark:text-white">S/ <span x-text="totalPrice.toFixed(2)"></span></p> </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-gray-100 dark:border-white/5 pb-2 cursor-default"> <span class="text-sm">📦</span> <span class="text-[9px] font-bold uppercase text-gray-400 tracking-widest">DATOS DE ENTREGA</span> </div>
                    <div> <p class="text-[9px] font-bold text-gray-400 mb-1 uppercase tracking-widest">👤 NOMBRE Y APELLIDO (QUIEN RECIBE)</p> <input type="text" x-model="clientName" placeholder="Ej: Ana María Rojas" class="w-full bg-gray-50 dark:bg-zinc-900 border border-gray-100 dark:border-white/5 rounded-xl px-4 py-3 text-xs dark:text-white"> </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div> <p class="text-[9px] font-bold text-gray-400 mb-1 uppercase tracking-widest">🌍 DEPARTAMENTO</p> <select x-model="clientDept" class="w-full bg-gray-50 dark:bg-zinc-900 border border-gray-100 dark:border-white/5 rounded-xl px-4 py-3 text-xs dark:text-white"> <option value="">Seleccionar...</option><option>Lima</option><option>Callao</option> </select> </div>
                        <div> <p class="text-[9px] font-bold text-gray-400 mb-1 uppercase tracking-widest">📍 DISTRITO</p> <input type="text" x-model="clientDistrict" placeholder="Ej: Miraflores" class="w-full bg-gray-50 dark:bg-zinc-900 border border-gray-100 dark:border-white/5 rounded-xl px-4 py-3 text-xs dark:text-white"> </div>
                    </div>
                    <div> <p class="text-[9px] font-bold text-gray-400 mb-1 uppercase tracking-widest">📅 FECHA DE ENTREGA</p> <input type="date" x-model="deliveryDate" class="w-full bg-gray-50 dark:bg-zinc-900 border border-gray-100 dark:border-white/5 rounded-xl px-4 py-3 text-xs dark:text-white"> </div>
                    <div> <p class="text-[9px] font-bold text-gray-400 mb-1 uppercase tracking-widest">💌 DEDICATORIA (OPCIONAL)</p> <textarea x-model="cardMessage" placeholder="Escribe un mensaje..." class="w-full h-16 bg-gray-50 dark:bg-zinc-900 border border-gray-100 dark:border-white/5 rounded-xl px-4 py-3 text-xs resize-none dark:text-white"></textarea> </div>
                </div>
                <button @click="sendToWhatsApp" class="w-full py-4 bg-brand text-white font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-black transition-all"> SOLICITAR COMPRA </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cartStore', () => ({
                cart: [], openDrawer: false, clientName: '', clientDistrict: '', clientDept: '', deliveryDate: '', cardMessage: '',
                addToCart(p) { const e = this.cart.find(i => i.id === p.id); if (e) { e.quantity++; } else { this.cart.push({ ...p, quantity: 1 }); } },
                removeFromCart(id) { const i = this.cart.findIndex(p => p.id === id); if (i !== -1) { if (this.cart[i].quantity > 1) { this.cart[i].quantity--; } else { this.cart.splice(i, 1); } } },
                get totalItems() { return this.cart.reduce((s, i) => s + i.quantity, 0); },
                get totalPrice() { return this.cart.reduce((s, i) => s + (i.price * i.quantity), 0); },
                getFilteredProducts(q) {
                    const query = q.toLowerCase().trim().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                    const allProducts = @json($allProductsData);
                    return allProducts.filter(p => p.name.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').includes(query));
                },
                sendToWhatsApp() {
                    if (this.cart.length === 0) return;
                    let m = `*🏵️ PEDIDO - {{ strtoupper($tenant->name) }}*\n\n`;
                    if (this.clientName) m += `*Cliente:* ${this.clientName}\n`;
                    if (this.clientDistrict) m += `*Distrito:* ${this.clientDistrict}\n`;
                    if (this.deliveryDate) m += `*Fecha:* ${this.deliveryDate}\n`;
                    if (this.cardMessage) m += `*Dedicatoria:* "${this.cardMessage}"\n\n`;
                    m += `*Productos:*\n`;
                    this.cart.forEach(i => { m += `✅ ${i.quantity}x ${i.name} (S/ ${i.price.toFixed(2)})\n`; });
                    m += `\n*TOTAL: S/ ${this.totalPrice.toFixed(2)}*`;
                    window.open(`https://wa.me/51{{ $tenant->whatsapp }}?text=${encodeURIComponent(m)}`, '_blank');
                }
            }));
        });
    </script>
</body>
</html>
