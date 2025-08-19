<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Account;

class AccountController extends Controller
{
    public function index()
    {
        return view('account.index');
    }

    public function create()
    {
        return view('account.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'pos_laporan' => 'required|string|max:50',
            'pos_saldo' => 'required|string|max:50',
            'deskripsi' => 'nullable|string|max:255',
            'credit' => 'nullable|numeric',
            'debit' => 'nullable|numeric'
        ],[
            'code.required' => 'Kode akun harus diisi.',
            'name.required' => 'Nama akun harus diisi.',
            'pos_laporan.required' => 'Pos laporan harus diisi.',
            'pos_saldo.required' => 'Pos saldo harus diisi.',
            'credit.numeric' => 'Nilai kredit harus berupa angka.',
            'debit.numeric' => 'Nilai debit harus berupa angka.'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        Account::create($data);

        return redirect()->route('account.index')->with('success', 'Account created successfully.');
    }

    public function show($id)
    {
        $account = Account::with('subAccounts')->findOrFail($id); // Memuat sub-akun
        return view('account.show', compact('account'));
    }

    public function edit($id)
    {
        $account = Account::with('subAccounts')->findOrFail($id); // Memuat sub-akun
        return view('account.edit', compact('account'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'pos_laporan' => 'required|string|max:50',
            'pos_saldo' => 'required|string|max:50',
            'deskripsi' => 'nullable|string|max:255',
            'credit' => 'nullable|numeric',
            'debit' => 'nullable|numeric'
        ]);

        $account = Account::findOrFail($id);
        $account->update($request->all());

        return redirect()->route('account.index')->with('success', 'Account updated successfully.');
    }

    public function destroy($id)
   {
        $account = Account::findOrFail($id);
        $account->delete();

        return redirect()->route('account.index')->with('success', 'Account deleted successfully.');
    }
}
