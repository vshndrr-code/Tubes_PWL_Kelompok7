<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:accounts,name,NULL,id,user_id,' . auth()->id(),
            'type' => 'required|in:cash,bank,credit,other',
            'balance' => 'required|numeric|min:0',
        ], [
            'name.unique' => 'Nama akun ini sudah ada. Silakan gunakan nama yang berbeda.',
        ]);

        Account::create(array_merge($data, ['user_id' => auth()->id()]));

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function show(Account $account)
    {
        $this->authorizeOwner($account);

        session(['selected_account_id' => $account->id]);

        return view('accounts.show', compact('account'));
    }

    public function edit(Account $account)
    {
        $this->authorizeOwner($account);

        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $this->authorizeOwner($account);

        $data = $request->validate([
            'name' => 'required|string|max:100|unique:accounts,name,' . $account->id . ',id,user_id,' . auth()->id(),
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

    private function authorizeOwner(Account $account): void
    {
        if ($account->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
