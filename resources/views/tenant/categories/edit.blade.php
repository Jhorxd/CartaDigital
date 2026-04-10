<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Categoría') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 font-sans">
                    <form method="POST" action="{{ route('tenant.admin.categories.update', $category) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <x-input-label for="name" :value="__('Nombre de la Categoría')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $category->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="order_position" :value="__('Orden')" />
                            <x-text-input id="order_position" name="order_position" type="number" class="mt-1 block w-full" :value="old('order_position', $category->order_position)" />
                            <x-input-error class="mt-2" :messages="$errors->get('order_position')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <a href="{{ route('tenant.admin.categories.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                            <x-primary-button>{{ __('Actualizar Categoría') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
