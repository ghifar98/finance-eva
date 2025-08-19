<?php

namespace App\Livewire;

use App\Models\Vendor;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class VendorTable extends PowerGridComponent
{
    public string $tableName = 'vendor-table-clean';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),

            // Footer tanpa showPerPage supaya pagination hilang
            PowerGrid::footer()
                ->showRecordCount(),

            PowerGrid::responsive()
                ->fixedColumns('name', 'detail'),
        ];
    }

    public function datasource(): Builder
    {
        // Ambil semua data vendor tanpa limit
        return Vendor::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('address')
            ->add('email')
            ->add('phone')
            ->add('created_at_formatted', fn (Vendor $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i'))
            ->add('detail', fn (Vendor $model) => '
                <div class="flex gap-2 items-center justify-start">
                    <a href="' . route('vendor.show', $model->id) . '" 
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white 
                              bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 
                              rounded-md transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5
                                     c4.478 0 8.268 2.943 9.542 7
                                     -1.274 4.057-5.064 7-9.542 7
                                     -4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Detail
                    </a>
                    
                    <a href="' . route('vendor.edit', $model->id) . '" 
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white 
                              bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 
                              rounded-md transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 
                                     002 2h11a2 2 0 002-2v-5
                                     m-1.414-9.414a2 2 0 112.828 2.828
                                     L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    
                    <button onclick="confirmDelete(' . $model->id . ', \'' . addslashes($model->name) . '\')" 
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white 
                                   bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 
                                   rounded-md transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 
                                     0116.138 21H7.862a2 2 0 
                                     01-1.995-1.858L5 7m5 4v6m4-6v6
                                     m1-10V4a1 1 0 00-1-1h-4a1 1 
                                     0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                </div>
            ');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Nama Vendor', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Alamat', 'address')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Telepon', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('Tanggal Dibuat', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Aksi', 'detail')
                ->visibleInExport(false),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datePicker('created_at'),
        ];
    }
}
