<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta Digital | {{ $tenant->name }}</title>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <!-- Alpine.js para interactividad (carrito) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="antialiased selection:bg-indigo-500 selection:text-white" x-data="cart()">

    <div class="min-h-screen pb-24 relative overflow-hidden">
        <!-- Background gradients -->
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-50"></div>
        <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-96 h-96 bg-emerald-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-30"></div>

        <!-- Encabezado del Local -->
        <header class="relative pt-12 pb-8 px-6 text-center z-10">
            @if($tenant->logo)
                <img src="{{ $tenant->logo }}" alt="Logo" class="mx-auto h-24 w-24 object-cover rounded-full shadow-lg border-2 border-indigo-500 mb-4 transition-transform hover:scale-105">
            @else
                <div class="mx-auto h-24 w-24 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-4xl font-black mb-4 shadow-2xl shadow-indigo-500/20 transition-transform hover:scale-105">
                    {{ substr($tenant->name, 0, 1) }}
                </div>
            @endif
            <h1 class="text-4xl font-extrabold tracking-tight mb-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">{{ $tenant->name }}</h1>
            
            <div class="flex flex-col sm:flex-row justify-center sm:space-x-6 text-sm text-slate-300 mt-4 space-y-2 sm:space-y-0">
                <p class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $tenant->address ?? 'Dirección no disponible' }}
                </p>
                <p class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $tenant->schedule ?? 'Horario no disponible' }}
                </p>
            </div>
        </header>

        <!-- Categorías y Productos -->
        <main class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6">
            @foreach($categories as $category)
                @if($category->products->count() > 0)
                    <div class="mb-10" x-data="{ expanded: true }">
                        <button class="w-full text-left focus:outline-none group" @click="expanded = !expanded">
                            <h2 class="text-2xl font-bold border-b border-indigo-500/30 pb-3 mb-5 flex justify-between items-center text-white group-hover:text-indigo-400 transition-colors">
                                {{ $category->name }}
                                <svg class="w-6 h-6 transform transition-transform duration-300 text-indigo-400" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </h2>
                        </button>
                        
                        <div x-show="expanded" x-collapse class="space-y-4">
                            @foreach($category->products as $product)
                                <div class="glass rounded-2xl p-4 flex gap-4 transition-all duration-300 hover:scale-[1.02] hover:bg-white/5 hover:shadow-xl hover:border-indigo-500/30 group">
                                    <div class="flex-1 flex flex-col">
                                        <h3 class="text-lg font-bold text-slate-100 group-hover:text-white">{{ $product->name }}</h3>
                                        <p class="text-slate-400 text-sm mt-1 mb-3 line-clamp-2">{{ $product->description ?? '' }}</p>
                                        <div class="flex justify-between items-center mt-auto">
                                            <span class="text-xl font-black text-emerald-400">S/ {{ number_format($product->price, 2) }}</span>
                                            <button @click="addToCart('{{ addslashes($product->name) }}', {{ $product->price }})" class="bg-indigo-600/90 hover:bg-indigo-500 text-white px-5 py-2 rounded-xl text-sm font-semibold transition-all shadow-lg active:scale-95 shadow-indigo-500/20">
                                                + Añadir
                                            </button>
                                        </div>
                                    </div>
                                    @if($product->image)
                                        <div class="w-24 h-24 sm:w-32 sm:h-32 shrink-0">
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-xl shadow-md border border-white/5">
                                        </div>
                                    @else
                                        <div class="w-24 h-24 sm:w-32 sm:h-32 shrink-0 rounded-xl bg-slate-800/50 flex items-center justify-center border border-white/5">
                                            <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </main>

        <!-- Fab Carrito Flotante -->
        <div class="fixed bottom-6 inset-x-0 flex justify-center z-50 px-4 pointer-events-none" x-show="totalItems > 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10" style="display: none;">
            <button @click="sendWhatsApp('{{ $tenant->whatsapp ?? '' }}')" class="pointer-events-auto bg-gradient-to-r from-emerald-500 to-emerald-600 shadow-2xl shadow-emerald-500/30 text-white px-8 py-4 rounded-full font-bold flex items-center gap-3 hover:scale-[1.03] active:scale-95 transition-all border border-emerald-400/20">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.57-.187-.981-.365-1.739-.751-2.874-2.502-2.961-2.614-.087-.112-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.173.087.289.129.332.202.043.073.043.423-.101.827z"/></svg>
                Hacer Pedido • <span class="bg-white/20 px-2 py-0.5 rounded-md" x-text="totalItems"></span> <span class="hidden sm:inline text-emerald-100 ml-1">S/ <span x-text="totalAmount.toFixed(2)"></span></span>
            </button>
        </div>

    </div>

    <!-- Alpine Plugin para Collapse -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cart', () => ({
                items: {},
                addToCart(name, price) {
                    if(this.items[name]) {
                        this.items[name].qty++;
                    } else {
                        this.items[name] = { price: price, qty: 1 };
                    }
                },
                get totalItems() {
                    return Object.values(this.items).reduce((acc, item) => acc + item.qty, 0);
                },
                get totalAmount() {
                    return Object.values(this.items).reduce((acc, item) => acc + (item.price * item.qty), 0);
                },
                sendWhatsApp(phone) {
                    if (!phone) {
                        alert('Este local no tiene un número de WhatsApp configurado.');
                        return;
                    }
                    let text = `*¡Hola! Quiero hacer un pedido:*%0A%0A`;
                    Object.entries(this.items).forEach(([name, data]) => {
                        text += `🔘 ${data.qty}x ${name} (S/ ${data.price.toFixed(2)})%0A`;
                    });
                    text += `%0A*Total: S/ ${this.totalAmount.toFixed(2)}*`;
                    window.open(`https://wa.me/${phone}?text=${text}`, '_blank');
                }
            }))
        })
    </script>
</body>
</html>
