<?php

namespace App\Livewire;

use App\Models\Account;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class AccountTable extends PowerGridComponent
{
    public string $tableName = 'account-table-ycnuxq-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showRecordCount(),
            PowerGrid::responsive()
                ->fixedColumns('code', 'detail'),
        ];
    }

    public function datasource(): Builder
    {
        return Account::query()->limit(1000);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('code')
            ->add('name')
            ->add('pos_laporan')
            ->add('pos_saldo')
            ->add('deskripsi')
            ->add('credit', fn (Account $model) => 'Rp' . number_format($model->credit, 0, ',', '.'))
            ->add('debit', fn (Account $model) => 'Rp' . number_format($model->debit, 0, ',', '.'))
            ->add('created_at_formatted', fn (Account $model) => Carbon::parse($model->created_at)->format('d/m/Y'))
            ->add('detail', fn (Account $model) => '
                
                    
                    <a href="' . route('account.edit', $model->id) . '" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 rounded-md transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    
                    <button onclick="confirmDelete(' . $model->id . ', \'' . addslashes($model->name) . '\')" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-md transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                </div>
            ');
    }

    public function columns(): array
    {
        return [
            Column::make('Kode', 'code')
                ->sortable()
                ->searchable(),

            Column::make('Nama Akun', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Posisi Laporan', 'pos_laporan')
                ->sortable()
                ->searchable(),

            Column::make('Posisi Saldo', 'pos_saldo')
                ->sortable()
                ->searchable(),

            Column::make('Deskripsi', 'deskripsi')
                ->sortable()
                ->searchable(),

            Column::make('Credit', 'credit')
                ->sortable(),

            Column::make('Debit', 'debit')
                ->sortable(),

            Column::make('Tanggal Dibuat', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Aksi', 'detail')
                ->visibleInExport(false),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('pos_laporan', 'pos_laporan')
                ->dataSource(Account::select('pos_laporan')->distinct()->get())
                ->optionLabel('pos_laporan')
                ->optionValue('pos_laporan'),
                
            Filter::select('pos_saldo', 'pos_saldo')
                ->dataSource(Account::select('pos_saldo')->distinct()->get())
                ->optionLabel('pos_saldo')
                ->optionValue('pos_saldo'),
        ];
    }
}