<!DOCTYPE html>
<html lang="es" x-data="themeManager" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->name }} | {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ $tenant->logo ?? asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Color de marca dinámico del tenant --}}
    <style>
        :root {
            --color-primary: {{ $tenant->brand_color ?? '#f97316' }};
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        primary: 'var(--color-primary)',
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.4); }
        .dark .glass { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .product-card { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .product-card:hover { transform: translateY(-4px); }
        .pulse-soft { animation: pulse-soft 2s infinite; }
        @keyframes pulse-soft { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.8; transform: scale(1.05); } }
        .gradient-bg { background: linear-gradient(135deg, var(--color-primary) 0%, color-mix(in srgb, var(--color-primary) 70%, black) 100%); }
        .shadow-primary { shadow: 0 10px 25px -5px color-mix(in srgb, var(--color-primary) 30%, transparent); }
    </style>
</head>
<body class="bg-gray-50 dark:bg-black text-slate-900 dark:text-slate-100 min-h-screen font-sans selection:bg-primary selection:text-white" x-data="cartManager">

    <!-- Theme Switcher -->
    <div class="fixed top-6 right-6 z-50">
        <button @click="toggleTheme" class="p-3.5 rounded-2xl glass shadow-2xl hover:scale-110 active:scale-95 transition-all">
            <template x-if="!darkMode">
                <svg class="w-6 h-6 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </template>
            <template x-if="darkMode">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.071 16.071l.707.707M7.757 7.757l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
            </template>
        </button>
    </div>

    <!-- Hero / Header -->
    <header class="relative h-96 w-full overflow-hidden">
        <div class="absolute inset-0 scale-110 bg-cover bg-center transition-all duration-1000" 
             style="background-image: url('https://images.unsplash.com/photo-1514361892635-6b07e31e75f9?auto=format&fit=crop&w=1200&q=80')">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-gray-50 dark:to-black"></div>
        
        <div class="absolute inset-x-0 bottom-0 p-8 pb-12 flex flex-col items-center text-center">
            <div class="relative mb-6">
                @if($tenant->logo)
                    <div class="p-1 round-3xl bg-white/20 backdrop-blur-md">
                        <img src="{{ $tenant->logo }}" class="w-28 h-28 rounded-2xl border-2 border-white/50 shadow-2xl object-cover bg-white">
                    </div>
                @else
                    <div class="w-28 h-28 rounded-3xl border-4 border-white shadow-2xl gradient-bg flex items-center justify-center text-white text-4xl font-extrabold tracking-tighter shadow-orange-500/40">
                        {{ substr($tenant->name, 0, 1) }}
                    </div>
                @endif
                <div class="absolute -bottom-2 -right-2 bg-green-500 w-6 h-6 rounded-full border-4 border-white dark:border-black animate-pulse shadow-lg"></div>
            </div>
            
            <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight drop-shadow-xl mb-2">{{ $tenant->name }}</h1>
            <div class="flex items-center gap-2 text-sm font-bold opacity-70 bg-black/5 dark:bg-white/5 px-4 py-1.5 rounded-full backdrop-blur-sm">
                <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                {{ $tenant->address ?? 'Experiencia Gastronómica Unica' }}
            </div>
        </div>
    </header>

    <!-- Interactive Info & Specials -->
    <div class="mt-[-20px] relative z-10 px-6 overflow-x-auto hide-scrollbar">
        <div class="flex gap-4 pb-6 pr-12">
            <div class="flex items-center gap-3 whitespace-nowrap bg-white dark:bg-slate-900 px-6 py-3.5 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 transition-all hover:scale-105 active:scale-95">
                <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-black opacity-40 leading-none mb-1">Horario</p>
                    <p class="text-xs font-bold">{{ $tenant->schedule ?? '12pm - 10pm' }}</p>
                </div>
            </div>

            <a href="https://wa.me/{{ $tenant->whatsapp }}" class="flex items-center gap-3 whitespace-nowrap bg-white dark:bg-slate-900 px-6 py-3.5 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 transition-all hover:scale-105 active:scale-95 group">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                    <img src="{{ asset('wsp.png') }}" class="w-7 h-7 object-contain" alt="WhatsApp">
                </div>
                <div>
                    <p class="text-[10px] uppercase font-black opacity-40 leading-none mb-1">WhatsApp</p>
                    <p class="text-xs font-black text-green-600 dark:text-green-400">¡Pide ahora!</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Global Search Bar -->
    <div class="px-6 mb-8">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input 
                type="text" 
                x-model="search"
                placeholder="Busca tu plato favorito..." 
                class="w-full bg-white dark:bg-slate-900 border-2 border-transparent focus:border-primary/20 rounded-2xl py-4 pl-12 pr-4 text-sm font-bold shadow-xl shadow-slate-200/50 dark:shadow-none focus:outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600"
            >
            <template x-if="search.length > 0">
                <button @click="search = ''" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </template>
        </div>
    </div>

    <!-- Recommendations / Featured (Dynamic) -->
    @php
        $featured = $categories->flatMap->products->where('is_popular', true)->take(6);
    @endphp
    @if($featured->isNotEmpty())
    <div class="mb-10 animate-[fade-in-up_0.8s_ease-out]" x-show="search === ''">
        <div class="px-8 flex justify-between items-end mb-6">
            <div>
                <span class="text-primary text-[10px] font-black uppercase tracking-[0.2em]">Más vendidos</span>
                <h2 class="text-2xl font-black tracking-tight leading-none mt-1">Recomendados 🔥</h2>
            </div>
        </div>
        
        <div class="flex gap-5 overflow-x-auto hide-scrollbar px-8 pb-4 pr-12">
            @foreach($featured as $product)
            <div @click="addToCart({ 
                                    id: {{ $product->id }}, 
                                    name: '{{ $product->name }}', 
                                    price: {{ $product->price }}, 
                                    image: '{{ $product->image ?? '' }}' 
                                })"
                 class="w-64 flex-shrink-0 bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 group active:scale-95 transition-all">
                <div class="h-40 relative">
                    <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=400&q=80' }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/60 to-transparent">
                        <span class="text-white font-black text-lg">S/ {{ number_format($product->price, 2) }}</span>
                    </div>
                </div>
                <div class="p-4 flex justify-between items-center">
                    <div>
                        <h4 class="font-bold text-slate-800 dark:text-white line-clamp-1 mb-1">{{ $product->name }}</h4>
                        <p class="text-[10px] text-slate-400 font-medium line-clamp-1">¡Añadir al pedido!</p>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:!text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Search Results Helper / Category Overrides -->
    <div x-show="search.length > 0" class="px-8 mb-4">
        <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest">Resultados de búsqueda</h3>
    </div>

    <!-- Sticky Tabs Navigation -->
    <nav class="sticky top-0 z-40 glass shadow-2xl py-4 overflow-x-auto hide-scrollbar mb-4" x-show="search === ''">
        <div class="flex gap-8 px-8 pr-16 items-center">
            @foreach($categories as $category)
            <button @click="activeCategory = '{{ $category->id }}'" 
               class="whitespace-nowrap py-2 text-sm font-black transition-all border-b-4 border-transparent hover:text-primary active:scale-90"
               :class="activeCategory == '{{ $category->id }}' ? 'border-primary text-primary scale-110' : 'text-slate-400 dark:text-slate-500 opacity-60'">
                {{ $category->name }}
            </button>
            @endforeach
        </div>
    </nav>

    <!-- Menu Content -->
    <main class="p-6 sm:p-8 space-y-12 pb-32">
        @foreach($categories as $category)
        <section x-cloak x-show="search === '' ? (activeCategory == '{{ $category->id }}') : categoryHasResults('{{ $category->id }}')" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-10 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 id="cat-{{ $category->id }}"
                 class="space-y-8">
            
            <div class="flex items-center gap-4" x-show="search === '' || categoryHasResults('{{ $category->id }}')">
                <div class="h-10 w-2.5 rounded-full gradient-bg shadow-lg shadow-primary"></div>
                <div>
                    <h2 class="text-3xl font-black tracking-tight leading-none">{{ $category->name }}</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Delicia seleccionada</p>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($category->products as $product)
                <div x-show="search === '' || '{{ strtolower($product->name) }}'.includes(search.toLowerCase())"
                     @if($product->is_active)
                     @click="addToCart({ 
                                    id: {{ $product->id }}, 
                                    name: '{{ $product->name }}', 
                                    price: {{ $product->price }}, 
                                    image: '{{ $product->image ?? '' }}' 
                                })"
                     @endif
                     class="product-card flex bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-xl shadow-slate-200/50 dark:shadow-none border border-white dark:border-slate-800 p-3 group transition-all"
                     :class="search === '' || '{{ strtolower($product->name) }}'.includes(search.toLowerCase()) ? '{{ $product->is_active ? 'active:scale-95' : 'opacity-70 grayscale-[0.5]' }}' : 'hidden'">
                    
                    <!-- Product Image -->
                    <div class="w-28 sm:w-36 h-28 sm:h-36 shrink-0 relative overflow-hidden rounded-[2rem] shadow-lg">
                        @if($product->image)
                            <img src="{{ $product->image }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy">
                        @else
                            <div class="w-full h-full gradient-bg opacity-20 flex items-center justify-center">
                                <svg class="w-12 h-12 text-primary opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif

                        @if(!$product->is_active)
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px] flex items-center justify-center p-2 text-center">
                                <span class="text-white font-black text-[10px] uppercase tracking-widest leading-tight">No disponible</span>
                            </div>
                        @elseif($product->is_popular)
                            <div class="absolute top-3 left-3 bg-yellow-400 text-black text-[9px] font-black px-2.5 py-1 rounded-full shadow-2xl flex items-center gap-1">
                                <span class="pulse-soft">⭐</span> 
                                POPULAR
                            </div>
                        @endif
                    </div>
                    
                    <!-- Product Info -->
                    <div class="flex-1 p-4 sm:p-5 flex flex-col justify-between">
                        <div class="mb-2">
                            <h3 class="font-black text-xl leading-[1.1] text-slate-800 dark:text-white mb-2">{{ $product->name }}</h3>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed opacity-80">
                                {{ $product->description ?? 'Elaborado con los mejores ingredientes frescos.' }}
                            </p>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="text-2xl font-black text-primary tracking-tighter">
                                <span class="text-sm font-bold opacity-60">S/</span>{{ number_format($product->price, 2) }}
                            </div>
                            
                            @if($product->is_active)
                                <div class="w-10 h-10 rounded-2xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-primary group-hover:bg-primary group-hover:!text-white transition-all shadow-sm group-hover:scale-110 active:scale-90">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-300 dark:text-slate-600 cursor-not-allowed">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                </div>
                            @endif
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

    <!-- Floating Cart (Dynamic Island Style) -->
    <div x-cloak x-show="totalItems > 0" 
         x-transition:enter="transition cubic-bezier(0.175, 0.885, 0.32, 1.275) duration-500 transform"
         x-transition:enter-start="translate-y-24 scale-90 opacity-0"
         x-transition:enter-end="translate-y-0 scale-100 opacity-100"
         class="fixed bottom-8 left-0 right-0 z-50 px-6 flex justify-center pointer-events-none">
        
        <button @click="openDrawer = true" 
                class="pointer-events-auto bg-slate-900 dark:bg-slate-800/90 backdrop-blur-2xl px-6 py-4 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] flex items-center gap-6 text-white hover:scale-105 active:scale-95 transition-all w-full max-w-md border border-white/10">
            <div class="relative">
                <div class="w-12 h-12 rounded-2xl bg-primary/20 flex items-center justify-center text-primary border border-primary/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <span class="absolute -top-2 -right-2 bg-primary text-white text-[10px] font-black rounded-full w-6 h-6 flex items-center justify-center shadow-lg border-2 border-slate-900" x-text="totalItems"></span>
            </div>
            
            <div class="text-left flex-1 min-w-0">
                <p class="text-[10px] uppercase font-black tracking-[0.2em] opacity-50 mb-0.5">Tu Selección</p>
                <p class="text-xl font-black leading-none truncate tracking-tighter">S/ <span x-text="totalPrice.toFixed(2)"></span></p>
            </div>

            <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-2xl font-black text-xs uppercase tracking-widest whitespace-nowrap">
                Revisar 
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7"></path></svg>
            </div>
        </button>
    </div>

    <!-- Side Drawer (Cart) - High End Refinement -->
    <div x-cloak x-init="$watch('openDrawer', value => { if (value) document.body.style.overflow = 'hidden'; else document.body.style.overflow = 'auto'; })"
         x-show="openDrawer" class="fixed inset-0 z-[60] overflow-hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md transition-opacity duration-500" @click="openDrawer = false"
             x-transition:enter="ease-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>
             
        <div class="absolute inset-y-0 right-0 max-w-full flex" 
             x-transition:enter="transform transition cubic-bezier(0.19, 1, 0.22, 1) duration-700"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition cubic-bezier(0.19, 1, 0.22, 1) duration-500"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">
            
            <div class="w-screen max-w-md bg-white dark:bg-slate-950 shadow-[-20px_0_50px_rgba(0,0,0,0.2)]">
                <div class="h-full flex flex-col pt-8">
                    <!-- Drawer Header -->
                    <div class="px-8 pb-8 flex justify-between items-center border-b border-gray-50 dark:border-slate-900">
                        <div>
                            <h2 class="text-3xl font-black tracking-tight">Tu Pedido</h2>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Favoritos listos</p>
                        </div>
                        <button @click="openDrawer = false" class="p-3 rounded-2xl bg-gray-50 dark:bg-slate-900 hover:bg-gray-100 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Items List -->
                    <div class="flex-1 overflow-y-auto px-8 py-6 space-y-6">
                        <template x-for="item in cart" :key="item.id">
                            <div class="flex items-center gap-5 group animate-[fade-in-up_0.4s_ease-out]">
                                <div class="w-20 h-20 rounded-[1.5rem] overflow-hidden shrink-0 shadow-lg border border-gray-100 dark:border-slate-800">
                                    <img :src="item.image || 'https://via.placeholder.com/200'" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-black text-slate-800 dark:text-white truncate" x-text="item.name"></h4>
                                    <div class="flex items-center gap-3 mt-1">
                                        <p class="text-primary font-black text-lg tracking-tighter">S/ <span x-text="(item.price * item.quantity).toFixed(2)"></span></p>
                                        <span class="text-[10px] font-bold text-slate-400" x-text="'(S/ ' + item.price.toFixed(2) + ' c/u)'"></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 bg-gray-50 dark:bg-slate-900 rounded-2xl p-1.5 border border-gray-100 dark:border-slate-800">
                                    <button @click="removeFromCart(item.id)" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-white dark:hover:bg-slate-800 transition active:scale-90 text-slate-400 hover:text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                                    </button>
                                    <span class="text-sm font-black w-4 text-center" x-text="item.quantity"></span>
                                    <button @click="addToCart(item)" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-white dark:hover:bg-slate-800 transition active:scale-90 text-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        
                        <template x-if="cart.length === 0">
                            <div class="text-center py-20">
                                <div class="w-24 h-24 bg-gray-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200 dark:text-slate-800">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                                <p class="font-black text-slate-300 dark:text-slate-700 uppercase tracking-[0.2em] text-sm">Tu carrito está vacío</p>
                            </div>
                        </template>
                    </div>

                    <!-- Footer -->
                    <div class="p-8 bg-gray-50/50 dark:bg-slate-900/50 backdrop-blur-xl border-t border-gray-100 dark:border-slate-900 space-y-6">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Subtotal Final</p>
                                <p class="text-4xl font-black tracking-tighter text-slate-900 dark:text-white">S/ <span x-text="totalPrice.toFixed(2)"></span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Items</p>
                                <p class="text-lg font-black" x-text="totalItems"></p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">🪑 Mesa / Nombre / Comentario</label>
                            <input
                                type="text"
                                x-model="tableNumber"
                                placeholder="Ej: Mesa 5, Para llevar, Sin cebolla..."
                                class="w-full bg-white dark:bg-slate-950 border-2 border-transparent focus:border-primary/20 rounded-2xl px-6 py-4 text-base font-bold shadow-sm focus:outline-none transition-all placeholder:text-gray-300 dark:placeholder:text-slate-800"
                            >
                        </div>

                        <button @click="sendToWhatsApp" class="w-full h-20 gradient-bg rounded-3xl text-white font-black text-lg shadow-2xl shadow-primary/30 active:scale-95 transition-all flex items-center justify-center gap-4 group">
                            <svg class="w-7 h-7 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-4.821 4.73c-2.197 0-4.342-.58-6.225-1.685L4 18.22l1.83-5.35c-1.21-2.083-1.847-4.467-1.847-6.902 0-7.407 6.04-13.447 13.448-13.447 3.608 0 7.001 1.405 9.554 3.958 2.553 2.553 3.957 5.946 3.957 9.554 0 7.408-6.04 13.45-13.448 13.45m0-25.26C5.435.352.004 5.783.004 12.51c0 2.15.56 4.247 1.626 6.115L0 24l5.545-1.455a12.1 12.1 0 005.811 1.493c6.726 0 12.158-5.43 12.158-12.157 0-3.26-1.27-6.324-3.575-8.63C17.636 1.147 14.57.352 11.306.352z"/></svg>
                            Confirmar Pedido
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
                tableNumber: '',
                activeCategory: '{{ $categories->first()->id ?? 0 }}',
                openDrawer: false,
                search: '',
                
                // All products to help with search without full re-render
                allProducts: @json($categories->map(fn($cat) => ['id' => $cat->id, 'products' => $cat->products->map(fn($p) => strtolower($p->name))])),

                categoryHasResults(catId) {
                    if (this.search === '') return true;
                    const cat = this.allProducts.find(c => c.id == catId);
                    return cat ? cat.products.some(p => p.includes(this.search.toLowerCase())) : false;
                },
                
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

                    let message = '';

                    // Incluir mesa si fue proporcionada
                    if (this.tableNumber.trim()) {
                        message += `🪑 *${this.tableNumber.trim()}* | {{ $tenant->name }}\n\n`;
                    } else {
                        message += `*Nuevo Pedido - {{ $tenant->name }}*\n\n`;
                    }

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
