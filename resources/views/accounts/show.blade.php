<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $account->name }}</h2>
                <p class="text-sm text-gray-500">Detail akun {{ $account->name }} dan saldo terkini.</p>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <a href="{{ route('accounts.edit', $account) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Edit Akun
                </a>
                <a href="{{ route('accounts.index') }}" class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/10 transition hover:bg-blue-700">
                    Semua Akun
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-6 lg:grid-cols-[360px_minmax(0,1fr)]">
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Akun Terpilih</p>
                            <h3 class="mt-3 text-2xl font-semibold text-slate-900">{{ $account->name }}</h3>
                            <p class="mt-2 text-sm text-slate-500">{{ ucfirst($account->type) }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700">Rp {{ number_format($account->balance, 0, ',', '.') }}</div>
                    </div>

                    <div class="mt-6 grid gap-4">
                        <div class="rounded-3xl bg-slate-50 p-4">
                            <p class="text-sm font-semibold text-slate-900">Saldo saat ini</p>
                            <p class="mt-2 text-lg text-slate-700">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-50 p-4">
                            <p class="text-sm font-semibold text-slate-900">Tipe akun</p>
                            <p class="mt-2 text-lg text-slate-700">{{ ucfirst($account->type) }}</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Ringkasan</p>
                            <h3 class="mt-2 text-xl font-semibold text-slate-900">Transaksi terbaru</h3>
                        </div>
                        <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat semua</a>
                    </div>

                    <div class="mt-8 rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-slate-500">
                        <div class="text-5xl">^ ^</div>
                        <p class="mt-4 text-sm">Belum ada transaksi untuk akun ini.</p>
                    </div>
                </section>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Aksi cepat</p>
                        <h3 class="mt-2 text-xl font-semibold text-slate-900">Tambah transaksi atau pindah akun</h3>
                    </div>
                    <a href="{{ route('accounts.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">Tambah Akun</a>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 p-5 text-center">
                        <p class="text-sm text-slate-500">Gunakan widget di pojok atas untuk memilih akun lain.</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5 text-center">
                        <p class="text-sm text-slate-500">Setiap akun akan membuka halaman sendiri setelah dipilih.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
