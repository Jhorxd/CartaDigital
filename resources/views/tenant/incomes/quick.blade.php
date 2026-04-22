<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-800 tracking-tight flex items-center gap-2">
                    <span class="p-2 bg-emerald-100 text-emerald-600 rounded-xl">⚡</span> Entrada Rápida de Ingresos
                </h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Carga Masiva de Capital (Modo Eficiencia)</p>
            </div>
            <a href="{{ route('tenant.admin.incomes.index') }}" class="text-slate-400 hover:text-slate-800 text-xs font-black uppercase tracking-widest transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen" x-data="quickIncomes('{{ $lastDate }}', {{ json_encode($categories) }})">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('error'))
                <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-600 p-4 rounded-2xl font-bold text-sm shadow-sm">{{ session('error') }}</div>
            @endif

            {{-- Barra de Herramientas Premium --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 mb-6 p-4 flex flex-wrap gap-4 items-center justify-between">
                <div class="flex flex-wrap gap-2 items-center">
                    <button type="button" @click="addRow()" class="flex items-center gap-2 text-xs font-black bg-emerald-600 text-white px-6 py-3 rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-100 active:scale-95">
                        ➕ Nueva fila <span class="hidden lg:inline-flex opacity-50 ml-1 font-mono text-[10px]">↵ Enter</span>
                    </button>
                    <button type="button" @click="removeSelected()" x-show="rows.some(r => r.selected)" class="flex items-center gap-2 text-xs font-black bg-rose-50 text-rose-600 px-6 py-3 rounded-xl hover:bg-rose-100 transition active:scale-95">
                        🗑️ Borrar seleccionadas
                    </button>
                    <button type="button" @click="fillDateDown()" class="flex items-center gap-2 text-xs font-black bg-slate-100 text-slate-600 px-6 py-3 rounded-xl hover:bg-slate-200 transition active:scale-95">
                        📅 Rellenar fechas
                    </button>
                </div>
                
                <div class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-4 sm:pt-0">
                    <div class="text-right">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Estimado</div>
                        <div class="text-3xl font-black text-slate-900 leading-none mt-1">S/ <span x-text="total"></span></div>
                    </div>
                    <button type="button" @click="submitAll()"
                        class="bg-slate-900 hover:bg-black text-white font-black px-10 py-4 rounded-2xl text-sm transition shadow-xl shadow-slate-200 transform active:scale-95">
                        💾 Guardar Lote
                    </button>
                </div>
            </div>

            {{-- Tabla Principal --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <form id="bulk-form" action="{{ route('tenant.admin.incomes.bulk') }}" method="POST">
                    @csrf
                    <template x-for="(row, index) in rows" :key="row.id">
                        <div style="display:none">
                            <input type="hidden" :name="'rows['+index+'][title]'"       :value="row.title">
                            <input type="hidden" :name="'rows['+index+'][amount]'"      :value="row.amount">
                            <input type="hidden" :name="'rows['+index+'][date]'"        :value="row.date">
                            <input type="hidden" :name="'rows['+index+'][category]'"    :value="row.category">
                            <input type="hidden" :name="'rows['+index+'][description]'" :value="row.description">
                        </div>
                    </template>
                </form>

                {{-- Cabecera Desktop --}}
                <div class="hidden md:grid gap-0 border-b border-slate-100 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]"
                     style="grid-template-columns: 60px 1fr 140px 160px 180px 1fr 60px;">
                    <div class="px-2 py-4 flex items-center justify-center">
                        <input type="checkbox" @change="toggleAll($event)" class="rounded-lg border-slate-200 text-emerald-600 focus:ring-emerald-500 w-5 h-5">
                    </div>
                    <div class="px-4 py-4">Concepto Ingreso <span class="text-rose-400">*</span></div>
                    <div class="px-4 py-4 text-right">Monto <span class="text-rose-400">*</span></div>
                    <div class="px-4 py-4 text-center">Fecha <span class="text-rose-400">*</span></div>
                    <div class="px-4 py-4">Categoría</div>
                    <div class="px-4 py-4">Descripción</div>
                    <div class="px-4 py-4"></div>
                </div>

                {{-- Filas --}}
                <div class="divide-y divide-slate-50">
                    <template x-for="(row, index) in rows" :key="row.id">
                        <div
                            class="transition-all duration-300"
                            :class="{ 'bg-emerald-50/30': row.focused, 'bg-rose-50/50': row.selected, 'bg-white': !row.focused && !row.selected }"
                        >
                            {{-- Vista Desktop --}}
                            <div class="hidden md:grid gap-0 items-center group" style="grid-template-columns: 60px 1fr 140px 160px 180px 1fr 60px;">
                                <div class="flex items-center justify-center px-2 py-2">
                                    <input type="checkbox" x-model="row.selected" class="rounded-lg border-slate-200 text-rose-500 focus:ring-rose-500 w-5 h-5">
                                </div>

                                <div class="border-r border-slate-50">
                                    <input type="text" x-model="row.title" placeholder="¿De dónde vino el dinero?"
                                        @focus="row.focused = true" @blur="row.focused = false"
                                        @keydown.enter.prevent="handleEnter(index, 'title')"
                                        @keydown.tab.prevent="moveFocus(index, 'title', 'next')"
                                        @keydown.delete.prevent="if(!row.title) removeRow(index)"
                                        :data-row="index" data-col="title"
                                        class="w-full px-4 py-5 text-sm border-0 focus:ring-0 bg-transparent outline-none font-black text-slate-800 placeholder:text-slate-300">
                                </div>

                                <div class="border-r border-slate-50">
                                    <input type="number" x-model="row.amount" placeholder="0.00" step="0.01" min="0"
                                        @focus="row.focused = true" @blur="row.focused = false"
                                        @keydown.enter.prevent="handleEnter(index, 'amount')"
                                        @keydown.tab.prevent="moveFocus(index, 'amount', 'next')"
                                        @keydown.shift.tab.prevent="moveFocus(index, 'amount', 'prev')"
                                        :data-row="index" data-col="amount"
                                        class="w-full px-4 py-5 text-sm border-0 focus:ring-0 bg-transparent outline-none text-right font-black text-emerald-600 placeholder:text-emerald-200">
                                </div>

                                <div class="border-r border-slate-50 text-center">
                                    <input type="date" x-model="row.date"
                                        @focus="row.focused = true" @blur="row.focused = false"
                                        @keydown.enter.prevent="handleEnter(index, 'date')"
                                        @keydown.tab.prevent="moveFocus(index, 'date', 'next')"
                                        @keydown.shift.tab.prevent="moveFocus(index, 'date', 'prev')"
                                        :data-row="index" data-col="date"
                                        class="w-full px-4 py-5 text-xs border-0 focus:ring-0 bg-transparent outline-none text-slate-500 font-bold text-center">
                                </div>

                                <div class="border-r border-slate-50">
                                    <select x-model="row.category"
                                        @focus="row.focused = true" @blur="row.focused = false"
                                        @keydown.enter.prevent="handleEnter(index, 'category')"
                                        @keydown.tab.prevent="moveFocus(index, 'category', 'next')"
                                        @keydown.shift.tab.prevent="moveFocus(index, 'category', 'prev')"
                                        :data-row="index" data-col="category"
                                        class="w-full px-4 py-5 text-xs border-0 focus:ring-0 bg-transparent outline-none text-slate-600 font-black uppercase tracking-widest">
                                        <option value="">Sin categoría</option>
                                        <template x-for="cat in categories" :key="cat">
                                            <option :value="cat" x-text="cat"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="border-r border-slate-50">
                                    <input type="text" x-model="row.description" placeholder="Opcional..."
                                        @focus="row.focused = true" @blur="row.focused = false"
                                        @keydown.enter.prevent="handleEnter(index, 'description')"
                                        @keydown.tab.prevent="moveFocus(index, 'description', 'next')"
                                        @keydown.shift.tab.prevent="moveFocus(index, 'description', 'prev')"
                                        :data-row="index" data-col="description"
                                        class="w-full px-4 py-5 text-sm border-0 focus:ring-0 bg-transparent outline-none text-slate-400 font-medium italic">
                                </div>

                                <div class="flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" @click="removeRow(index)" x-show="rows.length > 1"
                                        class="text-slate-300 hover:text-rose-500 transition p-2 rounded-xl hover:bg-rose-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Vista Móvil --}}
                            <div class="md:hidden p-6 space-y-4">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" x-model="row.selected" class="rounded-lg border-slate-200 text-rose-500 w-6 h-6">
                                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]" x-text="'Ingreso #'+(index+1)"></span>
                                    </div>
                                    <button type="button" @click="removeRow(index)" x-show="rows.length > 1" class="text-rose-200 p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                
                                <div class="space-y-4 bg-slate-50/50 p-4 rounded-3xl border border-slate-100">
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12">
                                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 block">Concepto</label>
                                            <input type="text" x-model="row.title" placeholder="Ej: Cobro Proyecto A" class="w-full bg-white border-slate-200 rounded-xl text-sm px-4 py-3 font-black text-slate-800 focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 block text-right">Monto (S/)</label>
                                            <input type="number" x-model="row.amount" placeholder="0.00" class="w-full bg-white border-slate-200 rounded-xl text-sm px-4 py-3 text-right font-black text-emerald-600 focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 block">Fecha</label>
                                            <input type="date" x-model="row.date" class="w-full bg-white border-slate-200 rounded-xl text-xs px-4 py-3 font-bold text-slate-600 focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Añadir al final --}}
                <div class="p-6 bg-slate-50/30 border-t border-slate-100">
                    <button type="button" @click="addRow()" class="w-full md:w-auto flex items-center justify-center gap-3 px-10 py-5 bg-white border-2 border-dashed border-slate-200 text-slate-400 rounded-3xl hover:border-emerald-400 hover:text-emerald-600 transition group">
                        <span class="text-xl group-hover:scale-125 transition-transform">➕</span>
                        <span class="font-black text-xs uppercase tracking-widest">Agregar nueva fila de ingreso</span>
                    </button>
                </div>
            </div>

            {{-- Footer info --}}
            <div class="mt-10 flex flex-col sm:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="flex -space-x-2">
                         <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center border-2 border-white text-emerald-600 font-black text-xs" x-text="rows.length"></div>
                         <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center border-2 border-white text-slate-600 font-black text-xs" x-text="rows.filter(r => r.title && r.amount).length"></div>
                    </div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        filas totales · listas para procesar
                    </div>
                </div>
                <div class="flex gap-4 w-full sm:w-auto">
                    <a href="{{ route('tenant.admin.incomes.index') }}" class="flex-1 sm:flex-none text-center px-10 py-5 bg-white border border-slate-200 text-slate-400 rounded-3xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition active:scale-95">
                        Cancelar
                    </a>
                    <button type="button" @click="submitAll()"
                        class="flex-1 sm:flex-none px-16 py-5 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-[2rem] text-xs uppercase tracking-widest transition shadow-2xl shadow-emerald-100 transform active:scale-95">
                        💾 Guardar Lote Completo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function quickIncomes(lastDate, categories) {
        const COLS = ['title', 'amount', 'date', 'category', 'description'];
        let uid = 0;

        function makeRow(overrides = {}) {
            return {
                id: ++uid,
                title: '',
                amount: '',
                date: lastDate,
                category: '',
                description: '',
                focused: false,
                selected: false,
                ...overrides
            };
        }

        return {
            categories,
            rows: [makeRow(), makeRow(), makeRow()],

            get total() {
                const sum = this.rows.reduce((acc, r) => {
                    const v = parseFloat(r.amount);
                    return acc + (isNaN(v) ? 0 : v);
                }, 0);
                return sum.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            addRow(afterIndex = null) {
                const lastFilled = this.rows.filter(r => r.date).slice(-1)[0];
                const inheritDate = lastFilled ? lastFilled.date : lastDate;
                const row = makeRow({ date: inheritDate });
                if (afterIndex !== null) {
                    this.rows.splice(afterIndex + 1, 0, row);
                } else {
                    this.rows.push(row);
                }
                this.$nextTick(() => {
                    if (window.innerWidth > 768) {
                        this.focusCell(afterIndex !== null ? afterIndex + 1 : this.rows.length - 1, 'title');
                    }
                });
            },

            removeRow(index) {
                if (this.rows.length <= 1) return;
                this.rows.splice(index, 1);
                if (window.innerWidth > 768) {
                    this.$nextTick(() => {
                        const newIdx = Math.min(index, this.rows.length - 1);
                        this.focusCell(newIdx, 'title');
                    });
                }
            },

            removeSelected() {
                this.rows = this.rows.filter(r => !r.selected);
                if (this.rows.length === 0) this.rows.push(makeRow());
            },

            toggleAll(e) {
                this.rows.forEach(r => r.selected = e.target.checked);
            },

            fillDateDown() {
                let current = null;
                this.rows.forEach(r => {
                    if (r.date) current = r.date;
                    else if (current) r.date = current;
                });
            },

            focusCell(rowIndex, col) {
                this.$nextTick(() => {
                    const el = document.querySelector(`[data-row="${rowIndex}"][data-col="${col}"]`);
                    if (el) { el.focus(); el.select?.(); }
                });
            },

            moveFocus(rowIndex, col, dir) {
                const colIndex = COLS.indexOf(col);
                let nextRow = rowIndex;
                let nextCol = col;

                if (dir === 'next') {
                    if (colIndex < COLS.length - 1) {
                        nextCol = COLS[colIndex + 1];
                    } else {
                        nextRow = rowIndex + 1;
                        nextCol = COLS[0];
                        if (nextRow >= this.rows.length) {
                            this.addRow();
                            return;
                        }
                    }
                } else {
                    if (colIndex > 0) {
                        nextCol = COLS[colIndex - 1];
                    } else {
                        nextRow = rowIndex - 1;
                        nextCol = COLS[COLS.length - 1];
                        if (nextRow < 0) return;
                    }
                }

                this.focusCell(nextRow, nextCol);
            },

            handleEnter(rowIndex, col) {
                if (col === 'description' || col === 'title') {
                    this.addRow(rowIndex);
                } else {
                    this.moveFocus(rowIndex, col, 'next');
                }
            },

            submitAll() {
                document.getElementById('bulk-form').submit();
            },

            init() {
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' && e.shiftKey) {
                        e.preventDefault();
                        this.submitAll();
                    }
                });
                if (window.innerWidth > 768) {
                    this.$nextTick(() => this.focusCell(0, 'title'));
                }
            }
        };
    }
    </script>
</x-app-layout>
