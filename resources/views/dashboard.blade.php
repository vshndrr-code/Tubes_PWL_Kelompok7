<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="overflow-hidden rounded-3xl border border-yellow-200 bg-yellow-100 shadow-sm">
                <div class="px-6 py-6 sm:flex sm:items-center sm:justify-between gap-6">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-yellow-900">Upgrade once, use lifetime</p>
                        <h2 class="mt-3 text-2xl font-semibold text-slate-900">Unlock premium features for smarter money tracking</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-yellow-900/90">
                            Upgrading to PREMIUM helps you eliminate any barriers and difficulties in managing your cash flow, monthly budget, or future goals. Just one payment and it's yours.
                        </p>
                    </div>
                    <a href="#" class="inline-flex items-center justify-center rounded-3xl bg-yellow-200 px-6 py-4 text-sm font-semibold text-yellow-950 transition hover:bg-yellow-300">
                        Go PREMIUM
                    </a>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-3">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Last Month</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">Rp 0</p>
                </div>
                <div class="rounded-3xl border border-blue-100 bg-white p-6 shadow-sm ring-1 ring-blue-100">
                    <p class="text-xs uppercase tracking-[0.24em] text-blue-500">This Month</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">Rp 0</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Future</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">Rp 0</p>
                </div>
            </div>

            <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center shadow-sm">
                <div class="text-5xl leading-none text-slate-500">^ ^</div>
                <p class="mt-4 text-sm font-medium text-slate-600">No transactions</p>
            </div>
        </div>
    </div>
</x-app-layout>
