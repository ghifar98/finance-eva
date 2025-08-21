<x-layouts.app :title="__('Create Account')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        {{-- Card Container --}}
        <div class="flex h-full w-full flex-col gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- Form Header --}}
            <div class="mb-2 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Account Details</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Fill in the form below to create new accounts.</p>
                </div>
                <button type="button" id="addAccountBtn" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Account
                </button>
            </div>

            {{-- The form now points to a named route 'account.store' and uses the POST method --}}
            <form method="POST" action="{{ route('account.store') }}" class="flex flex-col gap-6">
                @csrf {{-- CSRF token for security --}}

                {{-- Container for multiple accounts --}}
                <div id="accountsContainer">
                    {{-- First Account (Template) --}}
                    <div class="account-item border border-gray-300 rounded-lg p-6 mb-4 relative">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 account-title">Account #1</h3>
                            <button type="button" class="remove-account text-red-600 hover:text-red-800 hidden" onclick="removeAccount(this)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Grid Layout for Inputs --}}
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                            {{-- Input for 'code' --}}
                            <x-input
                                name="accounts[0][code]"
                                label="Account Code"
                                placeholder="e.g., 1101"
                                corner-hint="Required"
                                value="{{ old('accounts.0.code') }}"
                            />

                            {{-- Input for 'name' --}}
                            <x-input
                                name="accounts[0][name]"
                                label="Account Name"
                                placeholder="e.g., Kas Besar"
                                corner-hint="Required"
                                value="{{ old('accounts.0.name') }}"
                            />

                            {{-- Select for 'pos_laporan' (Report Position) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Report Position <span class="text-red-500">*</span>
                                </label>
                                <select name="accounts[0][pos_laporan]" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                                    <option value="">Select a report position</option>
                                    <option value="Neraca" {{ old('accounts.0.pos_laporan') == 'Neraca' ? 'selected' : '' }}>Neraca</option>
                                    <option value="Laba Rugi" {{ old('accounts.0.pos_laporan') == 'Laba Rugi' ? 'selected' : '' }}>Laba Rugi</option>
                                </select>
                            </div>

                            {{-- Select for 'pos_saldo' (Balance Position) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Normal Balance Position <span class="text-red-500">*</span>
                                </label>
                                <select name="accounts[0][pos_saldo]" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                                    <option value="">Select a balance position</option>
                                    <option value="Debit" {{ old('accounts.0.pos_saldo') == 'Debit' ? 'selected' : '' }}>Debit</option>
                                    <option value="Kredit" {{ old('accounts.0.pos_saldo') == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                                </select>
                            </div>

                            {{-- Currency Input for 'debit' --}}
                            <div class="col-span-1">
                                <x-currency
                                    label="Opening Debit"
                                    placeholder="0.00"
                                    name="accounts[0][debit]"
                                    thousands="."
                                    decimal=","
                                    precision="2"
                                    prefix="Rp "
                                    value="{{ old('accounts.0.debit') }}"
                                />
                            </div>

                            {{-- Currency Input for 'credit' --}}
                            <div class="col-span-1">
                                <x-currency
                                    label="Opening Credit"
                                    placeholder="0.00"
                                    name="accounts[0][credit]"
                                    thousands="."
                                    decimal=","
                                    precision="2"
                                    prefix="Rp "
                                    value="{{ old('accounts.0.credit') }}"
                                />
                            </div>

                            {{-- Textarea for 'deskripsi' (Full Width) --}}
                            <div class="col-span-1 md:col-span-2">
                                <x-textarea
                                    name="accounts[0][deskripsi]"
                                    label="Description"
                                    placeholder="Enter a brief description for the account (optional)"
                                >{{ old('accounts.0.deskripsi') }}</x-textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions/Footer --}}
                <div class="mt-4 flex justify-end gap-x-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <x-button
                        label="Cancel"
                        flat
                        href="{{ route('account.index') }}"
                    />
                    <x-button
                        label="Save All Accounts"
                        type="submit"
                        primary
                    />
                </div>
            </form>
        </div>
    </div>

    <script>
        let accountIndex = 1;

        document.getElementById('addAccountBtn').addEventListener('click', function() {
            addNewAccount();
        });

        function addNewAccount() {
            const container = document.getElementById('accountsContainer');
            const newAccount = createAccountHTML(accountIndex);
            container.insertAdjacentHTML('beforeend', newAccount);
            
            // Show remove buttons for all accounts when there's more than 1
            updateRemoveButtons();
            accountIndex++;
        }

        function createAccountHTML(index) {
            return `
                <div class="account-item border border-gray-300 rounded-lg p-6 mb-4 relative">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 account-title">Account #${index + 1}</h3>
                        <button type="button" class="remove-account text-red-600 hover:text-red-800" onclick="removeAccount(this)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account Code <span class="text-red-500">*</span></label>
                            <input type="text" name="accounts[${index}][code]" placeholder="e.g., 1101" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account Name <span class="text-red-500">*</span></label>
                            <input type="text" name="accounts[${index}][name]" placeholder="e.g., Kas Besar" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Report Position <span class="text-red-500">*</span></label>
                            <select name="accounts[${index}][pos_laporan]" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                                <option value="">Select a report position</option>
                                <option value="Neraca">Neraca</option>
                                <option value="Laba Rugi">Laba Rugi</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Normal Balance Position <span class="text-red-500">*</span></label>
                            <select name="accounts[${index}][pos_saldo]" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                                <option value="">Select a balance position</option>
                                <option value="Debit">Debit</option>
                                <option value="Kredit">Kredit</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Opening Debit</label>
                            <input type="text" name="accounts[${index}][debit]" placeholder="0.00" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Opening Credit</label>
                            <input type="text" name="accounts[${index}][credit]" placeholder="0.00" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                            <textarea name="accounts[${index}][deskripsi]" placeholder="Enter a brief description for the account (optional)" 
                                      rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>
                </div>
            `;
        }

        function removeAccount(button) {
            const accountItem = button.closest('.account-item');
            accountItem.remove();
            
            // Update account titles
            updateAccountTitles();
            
            // Update remove buttons visibility
            updateRemoveButtons();
        }

        function updateAccountTitles() {
            const accounts = document.querySelectorAll('.account-item');
            accounts.forEach((account, index) => {
                const title = account.querySelector('.account-title');
                title.textContent = `Account #${index + 1}`;
            });
        }

        function updateRemoveButtons() {
            const accounts = document.querySelectorAll('.account-item');
            const removeButtons = document.querySelectorAll('.remove-account');
            
            if (accounts.length <= 1) {
                removeButtons.forEach(btn => btn.classList.add('hidden'));
            } else {
                removeButtons.forEach(btn => btn.classList.remove('hidden'));
            }
        }
    </script>
</x-layouts.app>