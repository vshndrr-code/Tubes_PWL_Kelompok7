<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecurringTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => 'required|exists:accounts,id,user_id,' . auth()->id(),
            'title' => 'required|string|max:150',
            'amount' => 'required|numeric|min:0.01',
            'recurring_frequency' => 'required|in:daily,weekly,monthly,yearly',
            'start_date' => 'required|date|after_or_equal:today',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'account_id.required' => 'Akun harus dipilih.',
            'account_id.exists' => 'Akun yang dipilih tidak valid.',
            'amount.required' => 'Jumlah yang harus dibayar harus diisi.',
            'amount.numeric' => 'Jumlah harus berupa angka.',
            'amount.min' => 'Jumlah harus lebih dari 0.',
            'title.required' => 'Judul transaksi harus diisi.',
            'title.max' => 'Judul transaksi maksimal 150 karakter.',
            'recurring_frequency.required' => 'Pilih jenis recurring.',
            'recurring_frequency.in' => 'Pilih harian, mingguan, bulanan, atau tahunan.',
            'start_date.required' => 'Tanggal mulai harus diisi.',
            'start_date.date' => 'Format tanggal tidak valid.',
            'start_date.after_or_equal' => 'Tanggal mulai harus hari ini atau lebih baru.',
        ];
    }
}
