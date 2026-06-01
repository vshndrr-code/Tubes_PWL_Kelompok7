<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Budget</h2>
                <p class="text-sm text-gray-500">Kelola batas pengeluaran per kategori untuk bulan ini.</p>
            </div>
            <a href="{{ route('budgets.create') }}" class="inline-flex items-center rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition">Tambah Budget</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Kategori</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Bulan</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Limit</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($budgets as $budget)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $budget->category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 text-right">{{ sprintf('%02d/%04d', $budget->month, $budget->year) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 text-right">Rp {{ number_format($budget->limit_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('budgets.edit', $budget) }}" class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Ubah</a>
                                        <form action="{{ route('budgets.destroy', $budget) }}" method="POST" onsubmit="return confirm('Hapus budget ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center rounded-2xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-sm text-slate-500">Belum ada budget. Buat budget sekarang untuk memantau kategori pengeluaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
