@extends('layouts.app')

@section('content')
<div class="p-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold">
                Budgetings
            </h1>
            <p class="text-gray-500 mt-1">
                Manage your monthly budget limits
            </p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Tambah Budget Baru</h2>

        @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('budgetings.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category_id" id="category_id" class="mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="limit_amount" class="block text-sm font-medium text-gray-700">Jumlah Limit</label>
                    <input type="number" name="limit_amount" id="limit_amount" value="{{ old('limit_amount') }}" class="mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="0">
                    @error('limit_amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700">Bulan</label>
                    <select name="month" id="month" class="mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Pilih bulan</option>
                        @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}" {{ old('month') == $month ? 'selected' : '' }}>{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}</option>
                        @endforeach
                    </select>
                    @error('month')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">Tahun</label>
                    <input type="number" name="year" id="year" value="{{ old('year', date('Y')) }}" class="mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="2026">
                    @error('year')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-3 rounded-xl">
                        Simpan Budget
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 text-left">Category</th>
                    <th class="p-4 text-left">Limit Amount</th>
                    <th class="p-4 text-left">Month</th>
                    <th class="p-4 text-left">Year</th>
                    <th class="p-4 text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($budgetings as $budget)
                <tr class="border-t">
                    <td class="p-4">
                        {{ $budget->category->name ?? '-' }}
                    </td>
                    <td class="p-4">
                        Rp {{ number_format($budget->limit_amount,0,',','.') }}
                    </td>
                    <td class="p-4">
                        {{ $budget->month }}
                    </td>
                    <td class="p-4">
                        {{ $budget->year }}
                    </td>
                    <td class="p-4 text-center">
                        <a href="{{ route('budgetings.edit',$budget->id) }}" class="text-blue-500">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-10 text-gray-400">No budget available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection