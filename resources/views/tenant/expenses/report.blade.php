<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📊 Reporte de Gastos
            </h2>
            <a href="{{ route('tenant.admin.expenses.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                ← Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Selector de año --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-5 flex flex-wrap items-center gap-4">
                <span class="text-sm font-semibold text-gray-600 w-full sm:w-auto">Ver año:</span>
                <div class="flex flex-wrap gap-2">
                    @foreach($years as $y)
                        <a href="{{ route('tenant.admin.expenses.report', ['year' => $y]) }}"
                           class="px-4 py-1.5 rounded-full text-sm font-bold transition
                                  {{ $y == $year ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $y }}
                        </a>
                    @endforeach
                </div>
                @if($years->isEmpty())
                    <span class="text-gray-400 text-sm">No hay datos registrados aún.</span>
                @endif
            </div>

            {{-- Total del año --}}
            <div class="bg-gradient-to-r from-rose-500 to-rose-700 text-white rounded-xl p-6 shadow flex justify-between items-center">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-widest opacity-80">Total gastos {{ $year }}</div>
                    <div class="text-4xl font-bold mt-1">S/ {{ number_format($grandTotal, 2) }}</div>
                </div>
                <div class="text-6xl opacity-20">📉</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Resumen mensual --}}
                <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">📅 Por Mes</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        @php $maxMonthly = $monthlyTotals->max('total') ?: 1; @endphp
                        @foreach($monthNames as $idx => $name)
                            @php
                                $m = $idx + 1;
                                $row = $monthlyTotals->get($m);
                                $total = $row ? $row->total : 0;
                                $width = $total > 0 ? round(($total / $maxMonthly) * 100) : 0;
                            @endphp
                            <div class="flex items-center gap-3">
                                <div class="w-8 text-right text-xs font-semibold text-gray-500">{{ $name }}</div>
                                <div class="flex-1 bg-gray-100 rounded-full h-5 overflow-hidden">
                                    <div class="bg-rose-500 h-5 rounded-full transition-all duration-500"
                                         style="width: {{ $width }}%"></div>
                                </div>
                                <div class="w-24 text-right text-xs font-bold {{ $total > 0 ? 'text-rose-600' : 'text-gray-300' }}">
                                    {{ $total > 0 ? 'S/ ' . number_format($total, 2) : '—' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Resumen por categoría --}}
                <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">🏷️ Por Categoría</h3>
                    </div>
                    @if($byCategory->isEmpty())
                        <div class="p-8 text-center text-gray-400 text-sm">No hay datos para este año.</div>
                    @else
                        @php $maxCat = $byCategory->max('total') ?: 1; @endphp
                        <div class="p-4 space-y-3">
                            @foreach($byCategory as $row)
                                @php $width = round(($row->total / $maxCat) * 100); @endphp
                                <div>
                                    <div class="flex justify-between text-xs font-semibold text-gray-600 mb-1">
                                        <span>{{ $row->category }}</span>
                                        <span class="text-rose-600">S/ {{ number_format($row->total, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                                        <div class="bg-indigo-500 h-2.5 rounded-full" style="width: {{ $width }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="px-6 pb-4">
                            <table class="w-full text-sm border-t border-gray-100 pt-4">
                                <tbody>
                                    @foreach($byCategory as $row)
                                    <tr class="border-b border-gray-50">
                                        <td class="py-2 text-gray-700">{{ $row->category }}</td>
                                        <td class="py-2 text-right font-bold text-rose-600">S/ {{ number_format($row->total, 2) }}</td>
                                        <td class="py-2 text-right text-gray-400 text-xs pl-3">
                                            {{ $grandTotal > 0 ? round(($row->total / $grandTotal) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Acceso rápido --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('tenant.admin.expenses.quick') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl text-sm transition text-center shadow-md">
                    ⚡ Entrada Rápida
                </a>
                <a href="{{ route('tenant.admin.expenses.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl text-sm transition text-center shadow-md">
                    + Nuevo Gasto
                </a>
                <a href="{{ route('tenant.admin.expenses.index') }}" class="bg-white border border-gray-200 text-gray-600 font-bold py-3 px-6 rounded-xl text-sm transition text-center hover:bg-gray-50">
                    Ver Listado
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
