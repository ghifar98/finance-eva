<?php

namespace App\Livewire;

use App\Models\Purchase;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class PurchaseTable extends PowerGridComponent
{
    public string $tableName = 'purchase-table-luxury';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->withoutLoading(),
            PowerGrid::footer()
                ->showPerPage(0) // Menampilkan semua data sekaligus
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Purchase::query()
            ->select([
                'id', 
                'date', 
                'po_no', 
                'company', 
                'package', 
                'rep_name', 
                'total_amount',
                'qty'
            ])
            ->orderBy('date', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('date_formatted', fn (Purchase $model) => Carbon::parse($model->date)->format('d M Y'))
            ->add('po_no')
            ->add('company')
            ->add('package')
            ->add('rep_name')
            ->add('total_amount_formatted', fn (Purchase $model) => 'Rp ' . number_format($model->total_amount, 0, ',', '.'))
            ->add('qty')
            ->add('status_badge', fn (Purchase $model) => 
                Blade::render('
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-emerald-100 to-emerald-200 text-emerald-800 border border-emerald-300">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Active
                    </span>
                ')
            )
            ->add('actions', fn (Purchase $model) =>
                Blade::render('
                    <div class="flex items-center gap-2">
                        <a href="' . route('purchase.show', ['id' => $model->id]) . '" 
                           class="group relative inline-flex items-center px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Detail
                        </a>
                        <a href="' . route('purchase.item.create', ['id' => $model->id]) . '" 
                           class="group relative inline-flex items-center px-4 py-2 rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 text-white text-sm font-medium shadow-lg hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Item
                        </a>
                    </div>
                ')
            );
    }

    public function columns(): array
    {
        return [
            Column::make('PO Number', 'po_no', 'po_no')
                ->sortable()
                ->searchable(),
                
            Column::make('Date', 'date_formatted', 'date')
                ->sortable(),
                
            Column::make('Company', 'company')
                ->sortable()
                ->searchable(),
                
            Column::make('Package', 'package')
                ->sortable()
                ->searchable(),
                
            Column::make('Representative', 'rep_name')
                ->sortable()
                ->searchable(),
                
            Column::make('Amount', 'total_amount_formatted', 'total_amount')
                ->sortable(),
                
            Column::make('Quantity', 'qty')
                ->sortable(),
                
            Column::make('Status', 'status_badge'),
            
            Column::make('Actions', 'actions'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('date')
                // ->label('Filter by Date'),
        ];
    }

    public function template(): ?string
    {
        return 'livewire.purchase-table-template';
    }
}