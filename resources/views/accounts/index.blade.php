<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-slate-900 tracking-tight">Accounts</h2>
            </div>
        </div>
    </x-slot>

    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div
                    class="mb-6 rounded-[28px] border border-emerald-200/80 bg-emerald-50/80 px-6 py-4 text-emerald-900 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $typeLabels = [
                    'bank' => 'Bank',
                    'cash' => 'Cash',
                    'credit' => 'Kartu Kredit',
                    'other' => 'E-wallet',
                ];

                $typeIcons = [
                    'bank' =>
                        '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10l9-6 9 6"/><path d="M5 10v10h14V10"/><path d="M10 14h4"/><path d="M7 20v-6"/><path d="M17 20v-6"/></svg>',
                    'cash' =>
                        '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5"/><path d="M3 7h18"/><path d="M7 7v10"/><path d="M17 7v10"/><circle cx="12" cy="13" r="2"/></svg>',
                    'credit' =>
                        '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="3"/><path d="M2 11h20"/><path d="M6 16h2"/></svg>',
                    'other' =>
                        '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 2h10a3 3 0 0 1 3 3v14a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3Z"/><path d="M12 18h.01"/></svg>',
                ];

                $accountItems =
                    $accounts instanceof \Illuminate\Pagination\AbstractPaginator
                        ? $accounts->getCollection()
                        : collect($accounts);

                $totalBalance = $accountItems->sum('balance');
            @endphp

            <div class="mb-6 rounded-[32px] bg-slate-900/5 p-6 ring-1 ring-slate-200/80">
                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Saldo Total</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-900">Rp
                                {{ number_format($totalBalance, 0, ',', '.') }}</p>
                            <p class="mt-2 text-sm text-slate-500">Ringkasan semua dompet aktif Anda.</p>
                        </div>
                        <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Akun Aktif</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $accounts->count() }}</p>
                            <p class="mt-2 text-sm text-slate-500">Jumlah dompet yang sedang aktif.</p>
                        </div>
                    </div>

                    <div class="flex flex-col items-end justify-end gap-2">
                        <a href="{{ route('accounts.create') }}"
                            class="inline-flex h-9 min-w-[130px] items-center justify-center gap-1.5 rounded-full bg-emerald-500 px-4 text-xs font-semibold text-white shadow-md shadow-emerald-500/10 transition hover:bg-emerald-600">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                            </svg>
                            + Tambah Akun
                        </a>
                        <a href="{{ route('accounts.transfer') }}"
                            class="inline-flex h-9 min-w-[130px] items-center justify-center gap-1.5 rounded-full bg-slate-900 px-4 text-xs font-semibold text-white shadow-md shadow-slate-900/10 transition hover:bg-slate-800">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 7h16" />
                                <path d="M4 12h10" />
                                <path d="M16 17l4-4-4-4" />
                            </svg>
                            Transfer
                        </a>
                    </div>
                </div>
            </div>

            @if ($accounts->isEmpty())
                <div class="rounded-3xl border border-dashed border-gray-300 bg-white p-12 text-center shadow-sm">
                    <div class="mx-auto h-24 w-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 10.5V19a2 2 0 002 2h14a2 2 0 002-2v-8.5M5 10.5L12 4l7 6.5M5 10.5h14" />
                        </svg>
                    </div>
                    <p class="text-gray-500 mb-6">Belum ada akun. Tambahkan akun untuk mulai mengelola saldo.</p>
                    <a href="{{ route('accounts.create') }}"
                        class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Akun Pertamamu
                    </a>
                </div>
            @else
                <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-slate-200 text-sm text-slate-700">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left font-semibold tracking-[0.16em] uppercase">Wallet
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left font-semibold tracking-[0.16em] uppercase">Type
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left font-semibold tracking-[0.16em] uppercase">
                                    Balance</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left font-semibold tracking-[0.16em] uppercase">
                                    Transactions</th>
                                <th scope="col"
                                    class="w-60 px-6 py-4 text-center font-semibold tracking-[0.16em] uppercase">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach ($accounts as $account)
                                @php
                                    $bankName = strtolower($account->name);
                                    $bankKeys = ['bni', 'bca', 'mandiri', 'permata', 'cimb', 'danamon', 'anz'];
                                    $typeIcon = $typeIcons[$account->type] ?? $typeIcons['other'];
                                    if ($account->type === 'bank') {
                                        foreach ($bankKeys as $bankKey) {
                                            if (strpos($bankName, $bankKey) !== false) {
                                                $typeIcon = $typeIcons['bank'];
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
                                                {!! $typeIcon !!}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-900">{{ $account->name }}</p>
                                                <p class="text-xs text-slate-500">ID {{ $account->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-700">{{ $typeLabels[$account->type] ?? ucfirst($account->type) }}</span>
                                    </td>
                                    <td class="px-6 py-5 font-semibold text-slate-900">Rp
                                        {{ number_format($account->balance, 0, ',', '.') }}</td>
                                    <td class="px-6 py-5 text-slate-500">0 transaksi</td>
                                    <td class="w-60 px-6 py-5 align-middle">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('accounts.edit', $account) }}"
                                                class="inline-flex h-9 w-20 items-center justify-center gap-1 rounded-full border border-slate-200 bg-white text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-300">
                                                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M4 17.25V21h3.75L18.81 10.94l-3.75-3.75L4 17.25z" />
                                                    <path d="M14.06 6.94l3 3" />
                                                </svg>
                                                <span>Edit</span>
                                            </a>

                                            <form action="{{ route('accounts.destroy', $account) }}" method="POST"
                                                class="m-0 inline-flex p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Hapus akun ini?')"
                                                    class="inline-flex h-9 w-20 items-center justify-center gap-1 rounded-full border border-red-200 bg-red-50 text-xs font-semibold text-red-700 shadow-sm transition hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-300">
                                                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M10 11v6" />
                                                        <path d="M14 11v6" />
                                                        <path d="M5 7h14" />
                                                        <path d="M6 7l1 14h10l1-14" />
                                                        <path d="M9 7V4h6v3" />
                                                    </svg>
                                                    <span>Hapus</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
