<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500">Pemberitahuan</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900">Notifikasi terbaru untuk akun Anda</h3>
                    </div>
                    <a href="{{ route('monitoring.budgets') }}" class="inline-flex items-center rounded-3xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600 transition">Lihat Budget</a>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="divide-y divide-slate-200">
                    @forelse ($notifications as $notification)
                        <div class="px-6 py-5 {{ $notification->is_read ? 'bg-white' : 'bg-slate-50' }}">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">{{ $notification->title }}</h3>
                                    <p class="mt-2 text-sm text-slate-600">{{ $notification->message }}</p>
                                </div>
                                <div class="text-right text-xs text-slate-400">{{ $notification->created_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-16 text-center text-sm text-slate-500">Belum ada notifikasi.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
