<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Savings Goals') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500">Target tabungan</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900">Kelola tujuan finansial Anda</h3>
                    </div>
                    <a href="{{ route('monitoring.budgets') }}" class="inline-flex items-center rounded-3xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600 transition">Lihat Budget</a>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Kategori</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Target</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Terkumpul</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Deadline</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($goals as $goal)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $goal->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 text-right">Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 text-right">Rp {{ number_format($goal->current_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 text-right">{{ $goal->deadline->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $goal->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($goal->status === 'paused' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">
                                        {{ ucfirst($goal->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">Belum ada target tabungan yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
