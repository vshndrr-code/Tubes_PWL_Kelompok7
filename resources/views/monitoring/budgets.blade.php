<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Budget Monitoring') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500">Bulan</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900">{{ now()->monthName }} {{ $year }}</h3>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Kategori</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Limit</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Terpakai</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Sisa</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Progress</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($budgets as $budget)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $budget->category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 text-right">Rp {{ number_format($budget->limit_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 text-right">Rp {{ number_format($budget->spent, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 text-right">Rp {{ number_format(max(0, $budget->limit_amount - $budget->spent), 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                    <div class="w-full rounded-full bg-slate-100 h-2.5 overflow-hidden">
                                        <div class="h-2.5 bg-emerald-500" style="width: {{ $budget->progress }}%"></div>
                                    </div>
                                    <span class="mt-2 block text-xs text-slate-500">{{ number_format($budget->progress, 0) }}%</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">Belum ada anggaran yang diatur untuk bulan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
