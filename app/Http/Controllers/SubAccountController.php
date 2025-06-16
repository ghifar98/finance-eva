<?php

namespace App\Http\Controllers;
use App\Models\SubAccount;
use App\Models\Account;
use Illuminate\Http\Request;

class SubAccountController extends Controller
{
    

    public function create($id)
    {
        $account = Account::findOrFail($id);
        if (!$account) {
            return redirect()->route('account.index')->with('error', 'Akun tidak ditemukan.');
        }
        return view('sub-account.create')->with('account', $account);
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            
            'name' => 'required|string|max:100',
        ], [
            
            'name.required' => 'Nama sub-akun harus diisi.',
        
        ]);
        $account = Account::findOrFail($id);
        if (!$account) {
            return redirect()->route('account.index')->with('error', 'Akun tidak ditemukan.');
        }
        
        $data = $request->all();
        $data['account_id'] = $id; // Set the account_id to the provided account ID
        SubAccount::create($data);

        return redirect()->route('account.index')->with('success', 'Sub-account created successfully.');
    }   
}
