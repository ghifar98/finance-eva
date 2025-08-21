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
    // Validate multiple accounts
    $request->validate([
        'accounts' => 'required|array|min:1',
        'accounts.*.code' => 'required|string|max:255|unique:accounts,code',
        'accounts.*.name' => 'required|string|max:255',
        'accounts.*.pos_laporan' => 'required|in:Neraca,Laba Rugi',
        'accounts.*.pos_saldo' => 'required|in:Debit,Kredit',
        'accounts.*.debit' => 'nullable|numeric|min:0',
        'accounts.*.credit' => 'nullable|numeric|min:0',
        'accounts.*.deskripsi' => 'nullable|string|max:1000',
    ]);

    $createdAccounts = [];
    
    // Loop through each account and create
    foreach ($request->accounts as $accountData) {
        // Clean currency values
        if (isset($accountData['debit'])) {
            $accountData['debit'] = preg_replace('/[^\\d,.]/', '', $accountData['debit']);
            $accountData['debit'] = str_replace(',', '.', $accountData['debit']);
        }
        
        if (isset($accountData['credit'])) {
            $accountData['credit'] = preg_replace('/[^\\d,.]/', '', $accountData['credit']);
            $accountData['credit'] = str_replace(',', '.', $accountData['credit']);
        }
        
        // Tambahkan user_id dari user yang sedang login
        $accountData['user_id'] = Auth::id();
        
        $account = Account::create($accountData);
        $createdAccounts[] = $account;
    }

    $message = count($createdAccounts) == 1 
        ? 'Account created successfully!' 
        : count($createdAccounts) . ' accounts created successfully!';

    return redirect()->route('account.index')->with('success', $message);
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
