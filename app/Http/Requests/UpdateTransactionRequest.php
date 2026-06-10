<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->transaction->user_id === auth()->id();
    }


    public function rules(): array
    {
        return [
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'budgeting_id' => 'nullable|exists:budgetings,id',
            'savings_goal_id' => 'nullable|exists:savings_goals,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            'account_id.required' => 'Akun harus dipilih.',
            'account_id.exists' => 'Akun yang dipilih tidak valid.',
            'category_id.required' => 'Kategori harus dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'type.required' => 'Tipe transaksi harus dipilih.',
            'type.in' => 'Tipe transaksi harus pemasukan atau pengeluaran.',
            'amount.required' => 'Jumlah uang harus diisi.',
            'amount.numeric' => 'Jumlah uang harus berupa angka.',
            'amount.min' => 'Jumlah uang harus lebih dari 0.',
            'title.required' => 'Judul transaksi harus diisi.',
            'title.max' => 'Judul transaksi maksimal 150 karakter.',
            'transaction_date.required' => 'Tanggal transaksi harus diisi.',
            'transaction_date.date' => 'Format tanggal tidak valid.',
        ];
    }
}
