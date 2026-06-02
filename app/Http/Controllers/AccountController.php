<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::where('user_id', Auth::id())
            ->when(
                $request->boolean('archived'),
                fn($query) => $query->whereNotNull('archived_at'),
                fn($query) => $query->whereNull('archived_at')
            )
            ->orderByDesc('is_pinned')
            ->orderBy('name');

        if (method_exists(Account::class, 'transactions')) {
            $query->withCount('transactions');
        }

        $accounts = $query->get();

        return view('accounts.index', compact('accounts'))
            ->with('selectedAccount', null);
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:accounts,name,NULL,id,user_id,' . Auth::id(),
            'type' => 'required|in:cash,bank,credit,other',
            'balance' => 'required|numeric|min:0',
        ], [
            'name.unique' => 'Nama akun ini sudah ada. Silakan gunakan nama yang berbeda.',
        ]);

        Account::create(array_merge($data, ['user_id' => Auth::id()]));

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function show(Account $account)
    {
        $this->authorizeOwner($account);

        session(['selected_account_id' => $account->id]);

        return view('accounts.show', compact('account'))
            ->with('selectedAccount', $account);
    }

    public function edit(Account $account)
    {
        $this->authorizeOwner($account);

        return view('accounts.edit', compact('account'))
            ->with('selectedAccount', $account);
    }

    public function update(Request $request, Account $account)
    {
        $this->authorizeOwner($account);

        $data = $request->validate([
            'name' => 'required|string|max:100|unique:accounts,name,' . $account->id . ',id,user_id,' . Auth::id(),
            'type' => 'required|in:cash,bank,credit,other',
            'balance' => 'required|numeric|min:0',
        ], [
            'name.unique' => 'Nama akun ini sudah ada. Silakan gunakan nama yang berbeda.',
        ]);

        $account->update($data);

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(Account $account)
    {
        $this->authorizeOwner($account);

        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil dihapus.');
    }

    public function togglePin(Account $account)
    {
        $this->authorizeOwner($account);

        if ($account->archived_at) {
            throw ValidationException::withMessages([
                'account' => 'Akun arsip perlu dipulihkan sebelum disematkan.',
            ]);
        }

        $account->forceFill([
            'is_pinned' => ! $account->is_pinned,
        ])->save();

        return back()->with(
            'success',
            $account->is_pinned ? 'Akun disematkan sebagai akun utama.' : 'Pin akun dilepas.'
        );
    }

    public function archive(Account $account)
    {
        $this->authorizeOwner($account);

        $account->forceFill([
            'is_pinned' => false,
            'archived_at' => now(),
        ])->save();

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil diarsipkan.');
    }

    public function restore(Account $account)
    {
        $this->authorizeOwner($account);

        $account->forceFill([
            'archived_at' => null,
        ])->save();

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil dipulihkan.');
    }

    public function transferForm()
    {
        $accounts = Account::where('user_id', Auth::id())
            ->whereNull('archived_at')
            ->orderByDesc('is_pinned')
            ->orderBy('name')
            ->get();

        return view('accounts.transfer', compact('accounts'));
    }

    public function processTransfer(Request $request)
    {
        [$data, $fromAccount, $toAccount] = $this->validateTransferInput($request);

        DB::transaction(function () use ($fromAccount, $toAccount, $data) {
            $fromAccount->balance = $fromAccount->balance - $data['amount'];
            $toAccount->balance = $toAccount->balance + $data['amount'];

            $fromAccount->save();
            $toAccount->save();
        });

        return redirect()->route('accounts.index')->with('success', 'Transfer berhasil diproses.');
    }

    private function validateTransferInput(Request $request): array
    {
        $data = $request->validate([
            'from_account_id' => ['required', 'integer', 'exists:accounts,id', 'different:to_account_id'],
            'to_account_id' => ['required', 'integer', 'exists:accounts,id', 'different:from_account_id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ], [
            'from_account_id.required' => 'Pilih akun sumber terlebih dahulu.',
            'from_account_id.exists' => 'Akun sumber tidak ditemukan.',
            'from_account_id.different' => 'Akun sumber dan tujuan tidak boleh sama.',
            'to_account_id.required' => 'Pilih akun tujuan terlebih dahulu.',
            'to_account_id.exists' => 'Akun tujuan tidak ditemukan.',
            'to_account_id.different' => 'Akun sumber dan tujuan tidak boleh sama.',
            'amount.required' => 'Masukkan jumlah transfer.',
            'amount.numeric' => 'Jumlah transfer harus berupa angka.',
            'amount.min' => 'Jumlah transfer minimal Rp1.',
        ]);

        $accounts = Account::where('user_id', Auth::id())
            ->whereNull('archived_at')
            ->whereIn('id', [$data['from_account_id'], $data['to_account_id']])
            ->get();

        $fromAccount = $accounts->firstWhere('id', (int) $data['from_account_id']);
        $toAccount = $accounts->firstWhere('id', (int) $data['to_account_id']);

        if (! $fromAccount) {
            throw ValidationException::withMessages([
                'from_account_id' => 'Akun sumber tidak ditemukan atau sedang diarsipkan.',
            ]);
        }

        if (! $toAccount) {
            throw ValidationException::withMessages([
                'to_account_id' => 'Akun tujuan tidak ditemukan atau sedang diarsipkan.',
            ]);
        }

        if ((float) $data['amount'] > (float) $fromAccount->balance) {
            throw ValidationException::withMessages([
                'amount' => 'Saldo akun sumber tidak cukup untuk transfer ini.',
            ]);
        }

        return [$data, $fromAccount, $toAccount];
    }

    private function authorizeOwner(Account $account): void
    {
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
