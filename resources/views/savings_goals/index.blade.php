<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Savings Goals</h2>
                <p class="text-sm text-gray-500">Kelola target tabungan untuk tujuan khusus Anda.</p>
            </div>
            <a href="{{ route('savings-goals.create') }}" class="inline-flex items-center rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition">Tambah Goal</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Kategori</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Target</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Terkumpul</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Deadline</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Aksi</th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('savings-goals.edit', $goal) }}" class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Ubah</a>
                                        <form action="{{ route('savings-goals.destroy', $goal) }}" method="POST" onsubmit="return confirm('Hapus goal tabungan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center rounded-2xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">Belum ada goal tabungan. Buat goal baru untuk mulai menabung.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
