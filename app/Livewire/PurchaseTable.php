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
    public string $tableName = 'purchase-table-k6jbcw-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Purchase::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('date_formatted', fn (Purchase $model) => Carbon::parse($model->date)->format('d/m/Y'))
            ->add('po_no')
            ->add('company')
            ->add('project_id')
            ->add('vendor_id')
            ->add('package')
            ->add('rep_name')
            ->add('phone')
            ->add('total_amount')
            ->add('qty')
            ->add('balance')
            ->add('total_ppn')
            ->add('created_at')
            ->add('detail', fn (Purchase $model) => Blade::render('<x-button href="'. route('purchase.show',['id'=>$model->id]) .'" light positive label="Detail" />'));
    
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Date', 'date_formatted', 'date')
                ->sortable(),

            Column::make('Po no', 'po_no')
                ->sortable()
                ->searchable(),

            Column::make('Company', 'company')
                ->sortable()
                ->searchable(),

            Column::make('Project id', 'project_id'),
            Column::make('Vendor id', 'vendor_id'),
            Column::make('Package', 'package')
                ->sortable()
                ->searchable(),

            Column::make('Rep name', 'rep_name')
                ->sortable()
                ->searchable(),

            Column::make('Phone', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('Total amount', 'total_amount')
                ->sortable()
                ->searchable(),

            Column::make('Qty', 'qty')
                ->sortable()
                ->searchable(),

            Column::make('Balance', 'balance')
                ->sortable()
                ->searchable(),

            Column::make('Total ppn', 'total_ppn')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

           Column::make('Detail', 'detail'),
           Column::action('Action'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('date'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Purchase $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
