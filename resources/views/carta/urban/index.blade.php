<!DOCTYPE html>
<html lang="es" x-data="themeManager" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tenant->name }} | Urban Style</title>
    <link rel="icon" type="image/png" href="{{ $tenant->logo ?? asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .dark .simple-card {
            background: #111;
            border: 1px solid #222;
        }
        .simple-card:hover { 
            border-color: var(--brand); 
            transform: translateY(-4px);
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.1);
        }

        .image-container { aspect-ratio: 1/1; background: #f9f9f9; position: relative; }
        .dark .image-container { background: #0a0a0a; }

        /* Badges */
        .badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 4px 8px;
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-radius: 4px;
            z-index: 10;
        }
        .badge-nuevo { background: #000; color: #fff; }
        .badge-oferta { background: #ff3e3e; color: #fff; }
        .badge-limitado { background: var(--brand); color: #fff; }

        /* Grid adaptativo compacto */
        .shop-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        @media (min-width: 768px) {
            .shop-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 20px;
            }
        }

        /* Slider Scrollbar Hide */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased font-sans bg-white text-slate-900 dark:bg-black dark:text-slate-100 transition-colors duration-300" x-data="cartManager">

    <!-- Navbar Simple -->
    <nav class="sticky top-0 z-50 bg-white/90 dark:bg-black/90 backdrop-blur-md border-b border-gray-100 dark:border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="#" class="font-bold text-xl tracking-tight flex items-center gap-3 group">
                @if($tenant->logo)
                    <div class="h-10 bg-white dark:bg-zinc-900 rounded-lg p-1 border border-gray-100 dark:border-zinc-800 shadow-sm flex items-center justify-center transition-all group-hover:border-brand-primary">
                        <img src="{{ $tenant->logo }}" alt="{{ $tenant->name }}" class="h-full w-auto object-contain drop-shadow-sm">
                    </div>
                @else
                    <div class="w-10 h-10 rounded-lg bg-brand-primary text-white flex items-center justify-center text-lg font-black shadow-md border border-white/20">
                        {{ substr($tenant->name, 0, 1) }}
                    </div>
                @endif
                <span class="uppercase border-b-2 border-transparent group-hover:border-brand-primary transition-colors hidden sm:block">{{ $tenant->name }}</span>
            </a>

            <div class="flex items-center gap-3 md:gap-6">
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
    <header class="relative w-full h-[350px] md:h-[550px] overflow-hidden flex items-center justify-center text-center">
        <img src="{{ asset('img/hero.png') }}" 
             class="absolute inset-0 w-full h-full object-cover object-center md:object-[center_20%] transition-all duration-700 brightness-[0.8] dark:brightness-[0.9]" 
             alt="Urban Sneakers">
        <div class="absolute inset-0 bg-black/20 dark:bg-brand-primary/10 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-white dark:to-black opacity-90"></div>

        <div class="relative z-10 px-6">
            <span class="text-[10px] font-black uppercase tracking-[0.7em] text-brand-primary mb-4 block drop-shadow-md">Exclusive Drops</span>
            <h1 class="text-6xl md:text-9xl font-black uppercase tracking-tighter italic leading-none mb-6 text-white drop-shadow-2xl">
                STEPS <span class="text-brand-primary">UP</span>
            </h1>
            <p class="text-[10px] md:text-xs text-white uppercase tracking-[0.3em] font-black max-w-xl mx-auto drop-shadow-lg opacity-80">
                La mejor selección de calzado urbano. <br> Envíos express a todo el país.
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8" x-data="{ activeCat: {{ $categories->first()->id ?? 'null' }} }">
        
        <!-- Tabs Compactas con Indicador de Scroll -->
        <div class="relative mb-10 group/tabs">
            <!-- Gradientes laterales para indicar scroll (Solo Móvil) -->
            <div class="absolute left-0 top-0 bottom-0 w-12 bg-gradient-to-r from-white dark:from-black to-transparent z-10 pointer-events-none opacity-0 group-hover/tabs:opacity-100 transition-opacity md:hidden"></div>
            <div class="absolute right-0 top-0 bottom-0 w-12 bg-gradient-to-l from-white dark:from-black to-transparent z-10 pointer-events-none md:hidden"></div>
            
            <!-- Flecha Indicadora Animada (Solo Móvil) -->
            <div class="absolute right-2 top-1/2 -translate-y-1/2 z-20 pointer-events-none animate-bounce-horizontal md:hidden">
                <svg class="w-4 h-4 text-brand-primary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M13 7l5 5-5 5M6 7l5 5-5 5"></path></svg>
            </div>

            <style>
                @keyframes bounce-horizontal {
                    0%, 100% { transform: translate(-25%, -50%); animation-timing-function: cubic-bezier(0.8, 0, 1, 1); }
                    50% { transform: translate(0, -50%); animation-timing-function: cubic-bezier(0, 0, 0.2, 1); }
                }
                .animate-bounce-horizontal { animation: bounce-horizontal 1s infinite; }
            </style>
            
            <div class="flex justify-start md:justify-center overflow-x-auto no-scrollbar gap-2 pb-2 px-6"
                 x-init="$el.scrollTo({left: 40, behavior: 'smooth'}); setTimeout(() => $el.scrollTo({left: 0, behavior: 'smooth'}), 600)">
                @foreach($categories as $category)
                    <button @click="activeCat = {{ $category->id }}"
                            class="text-[10px] font-black uppercase tracking-widest whitespace-nowrap px-6 py-3 border-2 transition-all flex-shrink-0"
                            :class="activeCat === {{ $category->id }} ? 'bg-brand-primary text-white border-brand-primary shadow-lg shadow-brand-primary/20' : 'bg-transparent text-slate-400 border-gray-100 dark:border-white/5 hover:border-slate-300'">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Grid -->
        <div class="shop-grid justify-items-center">
            @foreach($categories as $category)
                <template x-if="activeCat === {{ $category->id }}">
                    <div class="contents">
                        @foreach($category->products as $product)
                            <div class="simple-card rounded-xl overflow-hidden flex flex-col p-2 w-full max-w-[280px]">
                                @if($product->badge)
                                    <span class="badge {{ $product->badge == 'Oferta' ? 'badge-oferta' : ($product->badge == 'Nuevo' ? 'badge-nuevo' : 'badge-limitado') }}">
                                        {{ $product->badge }}
                                    </span>
                                @endif

                                <div class="image-container rounded-lg overflow-hidden flex items-center justify-center group">
                                    @if($product->image)
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    @endif
                                </div>
                                
                                <div class="p-3 pt-4 flex-1 flex flex-col">
                                    <div class="flex justify-between items-start mb-1">
                                        <p class="text-[9px] font-black uppercase text-brand-primary tracking-tighter">{{ $product->brand ?? 'Urban Style' }}</p>
                                        @if($product->old_price)
                                            <span class="text-[9px] text-slate-400 line-through">S/ {{ number_format($product->old_price, 0) }}</span>
                                        @endif
                                    </div>
                                    <h3 class="text-xs font-extrabold uppercase truncate mb-3 tracking-tight">{{ $product->name }}</h3>
                                    
                                    <div class="mt-auto flex items-center justify-between">
                                        <span class="text-sm font-black text-slate-900 dark:text-white italic">S/ {{ number_format($product->price, 0) }}</span>
                                        <button @click="addToCart({ 
                                                    id: {{ $product->id }}, 
                                                    name: '{{ addslashes($product->name) }}', 
                                                    price: {{ $product->price }}, 
                                                    image: '{{ $product->image }}', 
                                                    brand: '{{ $product->brand }}',
                                                    gallery: {{ json_encode($product->gallery ?? []) }},
                                                    sizes: {{ json_encode($product->sizes ?? []) }} 
                                                })"
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-900 text-white dark:bg-white dark:text-black hover:bg-brand-primary hover:text-white transition-all shadow-md active:scale-90">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
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

    <!-- Drawer Carrito -->
    <div x-cloak x-show="openDrawer" class="fixed inset-0 z-[100] overflow-hidden">
        <div x-show="openDrawer" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openDrawer = false"></div>
        <div x-show="openDrawer" 
             x-transition:enter="transform transition ease-out duration-300" x-transition:enter-start="translate-x-full" 
             class="absolute inset-y-0 right-0 w-full max-w-xs bg-white dark:bg-black flex flex-col border-l border-gray-100 dark:border-white/10 shadow-2xl">
            <div class="p-6 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                <h2 class="font-black uppercase text-xs tracking-tighter italic">Tu Carrito</h2>
                <button @click="openDrawer = false" class="text-slate-400 hover:text-black dark:hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="flex-1 overflow-y-auto p-6 space-y-5">
                <template x-for="item in cart" :key="item.id">
                    <div class="flex gap-4 border-b border-gray-50 dark:border-white/5 pb-4">
                        <div class="w-16 h-16 bg-gray-50 dark:bg-zinc-900 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100 dark:border-white/5">
                            <img :src="item.image" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[7px] font-black uppercase text-brand-primary" x-text="item.brand || 'URBAN'"></p>
                            <p class="text-[10px] font-black uppercase truncate leading-tight" x-text="item.name"></p>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-[10px] font-black italic">S/ <span x-text="item.price.toFixed(0)"></span></p>
                                <div class="flex items-center gap-3">
                                    <button @click="removeFromCart(item.id)" class="w-5 h-5 flex items-center justify-center rounded bg-gray-100 dark:bg-zinc-800 text-[10px]">-</button>
                                    <span class="text-[10px] font-bold" x-text="item.quantity"></span>
                                    <button @click="executeAddToCart(item)" class="w-5 h-5 flex items-center justify-center rounded bg-gray-100 dark:bg-zinc-800 text-[10px]">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="p-6 bg-gray-50 dark:bg-zinc-900 border-t border-gray-100 dark:border-white/5 space-y-4">
                <div class="flex justify-between font-black text-xs uppercase italic"><span>Subtotal</span><span>S/ <span x-text="totalPrice.toFixed(0)"></span></span></div>
                <div class="space-y-2">
                    <label class="text-[8px] font-black uppercase text-slate-400">Datos de Envío</label>
                    <input type="text" x-model="clientDetails" placeholder="NOMBRE / TELÉFONO / DIRECCIÓN" class="w-full border border-gray-200 dark:border-white/10 p-3 text-[10px] uppercase font-bold focus:border-brand-primary outline-none bg-white dark:bg-black rounded-lg">
                </div>
                <button @click="sendToWhatsApp" class="w-full bg-brand-primary text-white py-4 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] hover:opacity-90 transition-all shadow-lg active:scale-95">Finalizar Pedido vía WhatsApp</button>
            </div>
        </div>
    </div>

    <!-- Modal Premium de Selección (Detalle + Galería + Talla) -->
    <div x-cloak x-show="showSizeModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
        <div x-show="showSizeModal" x-transition.opacity class="absolute inset-0 bg-slate-900/90 backdrop-blur-md" @click="showSizeModal = false"></div>
        <div x-show="showSizeModal" x-transition:enter="ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-8 scale-95" 
             class="relative bg-white dark:bg-zinc-900 w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-white/10">
            
            <!-- Close Button Overlay -->
            <button @click="showSizeModal = false" class="absolute top-4 right-4 z-50 w-8 h-8 flex items-center justify-center rounded-full bg-black/20 text-white backdrop-blur-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="flex flex-col md:flex-row h-full">
                <!-- Gallery Slider Section -->
                <div class="w-full md:w-1/2 aspect-square md:aspect-auto relative bg-gray-100 dark:bg-zinc-950" x-data="{ currentImg: 0 }">
                    <!-- Images -->
                    <div class="w-full h-full relative overflow-hidden">
                        <!-- Main Image -->
                        <template x-if="currentImg === 0">
                            <img :src="selectedProduct?.image" class="w-full h-full object-cover">
                        </template>
                        <!-- Gallery Images -->
                        <template x-for="(img, index) in (selectedProduct?.gallery || [])" :key="index">
                            <template x-if="currentImg === (index + 1)">
                                <img :src="img" class="w-full h-full object-cover">
                            </template>
                        </template>
                    </div>

                    <!-- Navigation Dots -->
                    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-1.5" x-show="selectedProduct?.gallery?.length > 0">
                        <button @click="currentImg = 0" class="w-2 h-2 rounded-full transition-all" :class="currentImg === 0 ? 'w-6 bg-brand-primary' : 'bg-white/50'"></button>
                        <template x-for="(img, index) in (selectedProduct?.gallery || [])">
                            <button @click="currentImg = index + 1" class="w-2 h-2 rounded-full transition-all" :class="currentImg === index + 1 ? 'w-6 bg-brand-primary' : 'bg-white/50'"></button>
                        </template>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="w-full md:w-1/2 p-6 md:p-8 flex flex-col">
                    <div class="mb-6">
                        <p class="text-[9px] font-black uppercase text-brand-primary tracking-widest mb-1" x-text="selectedProduct?.brand || 'URBAN EXCLUSIVE'"></p>
                        <h3 class="text-2xl font-black uppercase italic tracking-tighter leading-none" x-text="selectedProduct?.name"></h3>
                        <div class="flex items-center gap-2 mt-4">
                            <span class="text-xl font-black italic">S/ <span x-text="selectedProduct?.price"></span></span>
                        </div>
                    </div>

                    <div class="mb-8 flex-1">
                        <p class="text-[8px] font-black uppercase text-slate-400 tracking-widest mb-3 italic">Seleccionar Talla</p>
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="size in (selectedProduct?.sizes || [])" :key="size">
                                <button @click="selectedSize = size"
                                        :class="selectedSize === size ? 'bg-brand-primary text-white border-brand-primary shadow-lg shadow-brand-primary/30' : 'bg-gray-50 dark:bg-white/5 text-slate-500 border-gray-100 dark:border-white/5 hover:border-slate-300 dark:hover:border-zinc-700'"
                                        class="h-10 flex items-center justify-center text-[10px] font-black rounded-xl border-2 transition-all uppercase"
                                        x-text="size">
                                </button>
                            </template>
                            @if($tenant->business_type === 'urban')
                                <template x-if="!selectedProduct?.sizes || selectedProduct?.sizes.length === 0">
                                    <p class="col-span-4 text-[9px] uppercase font-bold text-slate-400 italic">Talla única / Contactar vendedor</p>
                                </template>
                            @endif
                        </div>
                    </div>

                    <button @click="confirmAddToCart" 
                            :disabled="selectedProduct?.sizes?.length > 0 && !selectedSize"
                            :class="(selectedProduct?.sizes?.length > 0 && !selectedSize) ? 'opacity-50 grayscale cursor-not-allowed' : 'hover:scale-[1.02] active:scale-95 shadow-xl shadow-brand-primary/20 bg-brand-primary'"
                            class="w-full text-white py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] transition-all">
                        Añadir al Carrito
                    </button>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-12 px-4 border-t border-gray-100 dark:border-white/5 text-center mt-12 bg-gray-50 dark:bg-zinc-950">
        <p class="text-[9px] font-black uppercase tracking-[0.4em] text-slate-400 italic">© {{ date('Y') }} {{ $tenant->name }} • Urban Footwear Experience</p>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('themeManager', () => ({
                darkMode: localStorage.getItem('darkMode') === 'true',
                toggleTheme() { this.darkMode = !this.darkMode; localStorage.setItem('darkMode', this.darkMode); }
            }));
            Alpine.data('cartManager', () => ({
                cart: [], clientDetails: '', openDrawer: false,
                showSizeModal: false, selectedProduct: null, selectedSize: null,
                
                addToCart(product) {
                    this.selectedProduct = product;
                    this.selectedSize = null;
                    if (product.sizes && product.sizes.length > 0) {
                        this.showSizeModal = true;
                    } else {
                        // Si no tiene tallas, permitir ver el detalle igual (con la galería)
                        this.showSizeModal = true;
                    }
                },

                confirmAddToCart() {
                    const itemToAdd = { ...this.selectedProduct };
                    
                    if (this.selectedSize) {
                        itemToAdd.id = `${this.selectedProduct.id}-${this.selectedSize}`;
                        itemToAdd.name = `${this.selectedProduct.name} (T: ${this.selectedSize})`;
                        itemToAdd.size = this.selectedSize;
                    }

                    this.executeAddToCart(itemToAdd);
                    this.showSizeModal = false;
                },

                executeAddToCart(item) {
                    const e = this.cart.find(i => i.id === item.id);
                    if (e) e.quantity++;
                    else this.cart.push({...item, quantity: 1});
                    this.openDrawer = true;
                },

                removeFromCart(id) {
                    const i = this.cart.findIndex(x => x.id === id);
                    if (i !== -1) {
                        if (this.cart[i].quantity > 1) this.cart[i].quantity--;
                        else this.cart.splice(i, 1);
                    }
                },

                get totalItems() { return this.cart.reduce((s, i) => s + i.quantity, 0); },
                get totalPrice() { return this.cart.reduce((s, i) => s + (i.price * i.quantity), 0); },
                
                sendToWhatsApp() {
                    if (this.cart.length === 0) return;
                    const m = `🔥🔥 *NUEVO PEDIDO: {{ $tenant->name }}* 🔥🔥\n\n` + 
                            this.cart.map(i => `👟 *${i.quantity}x ${i.name.toUpperCase()}*\n   Marca: ${i.brand || 'Urban'}\n   Precio: S/ ${i.price}`).join('\n\n') + 
                            `\n\n💰 *TOTAL A PAGAR: S/ ${this.totalPrice}*\n\n👤 *CLIENTE:* ${this.clientDetails.toUpperCase()}\n\n_Enviado desde el catálogo digital_`;
                    window.open(`https://wa.me/51{{ $tenant->whatsapp }}?text=${encodeURIComponent(m)}`, '_blank');
                }
            }));
        });
    </script>
</body>
</html>
</body>
</html>
