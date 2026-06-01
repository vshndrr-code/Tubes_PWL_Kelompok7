<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ubah Savings Goal</h2>
                <p class="text-sm text-gray-500">Perbarui target tabungan dan status goal Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                    <h3 class="text-xl font-semibold text-white">Ubah Savings Goal</h3>
                    <p class="text-emerald-100">Perbarui data goal tabungan Anda sesuai kebutuhan.</p>
                </div>

                <form action="{{ route('savings-goals.update', $goal) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Goal</label>
                            <input id="name" name="name" value="{{ old('name', $goal->name) }}" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Beli Sepatu, Beli Laptop" />
                            @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="target_amount" class="block text-sm font-semibold text-slate-700 mb-2">Target Amount</label>
                            <input id="target_amount" name="target_amount" type="number" step="0.01" min="0" value="{{ old('target_amount', $goal->target_amount) }}" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="5500000" />
                            @error('target_amount')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="current_amount" class="block text-sm font-semibold text-slate-700 mb-2">Current Amount</label>
                            <input id="current_amount" name="current_amount" type="number" step="0.01" min="0" value="{{ old('current_amount', $goal->current_amount) }}" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="0" />
                            @error('current_amount')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="deadline" class="block text-sm font-semibold text-slate-700 mb-2">Deadline</label>
                            <input id="deadline" name="deadline" type="date" value="{{ old('deadline', $goal->deadline->format('Y-m-d')) }}" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" />
                            @error('deadline')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                            <select id="status" name="status" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                                <option value="active" {{ old('status', $goal->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ old('status', $goal->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="paused" {{ old('status', $goal->status) === 'paused' ? 'selected' : '' }}>Paused</option>
                            </select>
                            @error('status')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end pt-4 border-t border-slate-200">
                        <a href="{{ route('savings-goals.index') }}" class="inline-flex justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                        <button type="submit" class="inline-flex justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">Perbarui Goal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
