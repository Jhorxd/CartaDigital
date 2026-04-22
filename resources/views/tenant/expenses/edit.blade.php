<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ✏️ Editar Gasto
            </h2>
            <a href="{{ route('tenant.admin.expenses.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                ← Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl p-6 md:p-8">

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded">
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tenant.admin.expenses.update', $expense) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">
                            Título <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title', $expense->title) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5"
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="amount" class="block text-sm font-semibold text-gray-700 mb-1">
                                Monto (S/) <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="amount"
                                name="amount"
                                value="{{ old('amount', $expense->amount) }}"
                                step="0.01"
                                min="0.01"
                                class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5"
                            >
                        </div>
                        <div>
                            <label for="date" class="block text-sm font-semibold text-gray-700 mb-1">
                                Fecha <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="date"
                                name="date"
                                value="{{ old('date', $expense->date->format('Y-m-d')) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-1">Categoría</label>
                        <select
                            id="category"
                            name="category"
                            class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5"
                        >
                            <option value="">— Sin categoría —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" @selected(old('category', $expense->category) === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">
                            Descripción <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 resize-none"
                        >{{ old('description', $expense->description) }}</textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg text-sm transition">
                            💾 Actualizar Gasto
                        </button>
                        <a href="{{ route('tenant.admin.expenses.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-lg text-sm transition">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
