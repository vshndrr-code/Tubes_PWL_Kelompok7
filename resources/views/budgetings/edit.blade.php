@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">Edit Budget</h1>
            <p class="text-gray-500 mt-1">Perbarui batas anggaran untuk kategori dan periode ini.</p>
        </div>
        <a href="{{ route('budgetings.index') }}" class="bg-slate-100 text-slate-700 px-5 py-3 rounded-xl hover:bg-slate-200">Kembali</a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <form action="{{ route('budgetings.update', $budgeting->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">

            <div class="grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-xl border border-gray-200 bg-slate-50 px-4 py-3 shadow-sm focus:border-emerald-500 focus:ring-emerald-500/20">
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $budgeting->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="limit_amount" class="block text-sm font-medium text-gray-700">Jumlah Limit</label>
                    <input id="limit_amount" name="limit_amount" type="number" value="{{ old('limit_amount', $budgeting->limit_amount) }}" required class="mt-1 block w-full rounded-xl border border-gray-200 bg-slate-50 px-4 py-3 shadow-sm focus:border-emerald-500 focus:ring-emerald-500/20" placeholder="0" />
                    @error('limit_amount')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700">Bulan</label>
                    <select id="month" name="month" required class="mt-1 block w-full rounded-xl border border-gray-200 bg-slate-50 px-4 py-3 shadow-sm focus:border-emerald-500 focus:ring-emerald-500/20">
                        @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}" {{ old('month', $budgeting->month) == $month ? 'selected' : '' }}>{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}</option>
                        @endforeach
                    </select>
                    @error('month')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">Tahun</label>
                    <input id="year" name="year" type="number" value="{{ old('year', $budgeting->year) }}" required class="mt-1 block w-full rounded-xl border border-gray-200 bg-slate-50 px-4 py-3 shadow-sm focus:border-emerald-500 focus:ring-emerald-500/20" placeholder="2026" />
                    @error('year')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <a href="{{ route('budgetings.index') }}" class="bg-slate-100 text-slate-700 px-5 py-3 rounded-xl hover:bg-slate-200">Batal</a>
                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-3 rounded-xl">Perbarui Budget</button>
            </div>
        </form>
    </div>
</div>
@endsection
