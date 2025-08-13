<?php

namespace App\Livewire;

use App\Models\Account;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
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
                ->showPerPage()
                ->showRecordCount(),
            PowerGrid::responsive()
                ->fixedColumns('code', 'detail'),
        ];
    }

    public function datasource(): Builder
    {
        return Account::query();
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
            ->add('detail', fn (Account $model) => Blade::render('<button class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">Detail</button>'));
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

    // Custom CSS untuk menghilangkan garis tabel
    public function template(): ?string
    {
        return <<<'HTML'
        <div>
            <style>
                .pg-table {
                    border-collapse: separate !important;
                    border-spacing: 0 8px !important;
                }
                
                .pg-table thead tr th {
                    background-color: #1e293b !important;
                    color: white !important;
                    border: none !important;
                    padding: 16px !important;
                    font-weight: 600 !important;
                }
                
                .pg-table thead tr th:first-child {
                    border-radius: 8px 0 0 8px !important;
                }
                
                .pg-table thead tr th:last-child {
                    border-radius: 0 8px 8px 0 !important;
                }
                
                .pg-table tbody tr td {
                    background-color: white !important;
                    border: none !important;
                    padding: 16px !important;
                    border-top: 1px solid #f1f5f9 !important;
                    border-bottom: 1px solid #f1f5f9 !important;
                }
                
                .pg-table tbody tr td:first-child {
                    border-left: 1px solid #f1f5f9 !important;
                    border-radius: 8px 0 0 8px !important;
                }
                
                .pg-table tbody tr td:last-child {
                    border-right: 1px solid #f1f5f9 !important;
                    border-radius: 0 8px 8px 0 !important;
                }
                
                .pg-table tbody tr:hover td {
                    background-color: #f8fafc !important;
                }
                
                .pg-table tbody tr {
                    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
                }
                
                .power-grid {
                    background-color: #f8fafc !important;
                    padding: 24px !important;
                    border-radius: 12px !important;
                }
                
                .pg-search {
                    background-color: white !important;
                    border: 1px solid #e2e8f0 !important;
                    border-radius: 8px !important;
                    padding: 12px 16px !important;
                }
                
                .pg-search:focus {
                    border-color: #f97316 !important;
                    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1) !important;
                }
            </style>
            
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-semibold text-slate-800">Daftar Akun</h1>
                        <p class="text-slate-600 mt-1">Chart of Accounts</p>
                    </div>
                    <a href="{{ route('account.create') }}" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Akun
                    </a>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-800 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Data Akun</h3>
                </div>
                
                <div class="p-6">
                    @php
                        $powerGridComponent = $this;
                    @endphp
                    
                    {!! $powerGridComponent->render() !!}
                </div>
            </div>
        </div>
        HTML;
    }
}