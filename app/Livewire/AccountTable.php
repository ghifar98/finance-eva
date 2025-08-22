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