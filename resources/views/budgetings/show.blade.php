@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">Detail Budget</h1>
            <p class="text-gray-500 mt-1">Lihat informasi lengkap untuk budget ini.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('budgetings.index') }}" class="bg-slate-100 text-slate-700 px-5 py-3 rounded-xl hover:bg-slate-200">Kembali</a>
            <a href="{{ route('budgetings.edit', $budgeting->id) }}" class="bg-emerald-500 text-white px-5 py-3 rounded-xl hover:bg-emerald-600">Edit</a>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Kategori</p>
            <p class="mt-3 text-xl font-semibold text-slate-900">{{ $budgeting->category->name ?? '-' }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Limit Amount</p>
            <p class="mt-3 text-xl font-semibold text-slate-900">Rp {{ number_format($budgeting->limit_amount, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Bulan</p>
            <p class="mt-3 text-xl font-semibold text-slate-900">{{ str_pad($budgeting->month, 2, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Tahun</p>
            <p class="mt-3 text-xl font-semibold text-slate-900">{{ $budgeting->year }}</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Dibuat Pada</p>
            <p class="mt-3 text-slate-900">{{ optional($budgeting->created_at)->format('d M Y H:i') ?? '-' }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Terakhir Diperbarui</p>
            <p class="mt-3 text-slate-900">{{ optional($budgeting->updated_at)->format('d M Y H:i') ?? '-' }}</p>
        </div>
    </div>
</div>
@endsection
