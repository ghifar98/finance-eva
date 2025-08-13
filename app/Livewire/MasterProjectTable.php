<?php

namespace App\Livewire;

use App\Models\MasterProject;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class MasterProjectTable extends PowerGridComponent
{
    public string $tableName = 'master-project-table-9nllku-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->withoutLoading(),
            PowerGrid::footer()
                ->showRecordCount()
                ->pageName('projectPage'), // Custom pagination name
        ];
    }

    public function datasource(): Builder
    {
        return MasterProject::query()->limit(1000); // Show more records without pagination
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('user_id')
            ->add('project_name')
            ->add('kontrak')
            ->add('vendor')
            ->add('start_project_formatted', fn (MasterProject $model) => Carbon::parse($model->start_project)->format('d/m/Y'))
            ->add('end_project_formatted', fn (MasterProject $model) => Carbon::parse($model->end_project)->format('d/m/Y'))
            ->add('project_duration', fn (MasterProject $model) => $this->calculateProjectAge($model))
            ->add('detail', fn (MasterProject $model) => Blade::render(
                '<div class="flex justify-center">
                    <x-button 
                        href="' . route('master-projects.show', ['id' => $model->id]) . '" 
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 border-0" 
                        label="Detail" 
                    />
                </div>'
            ));
    }

    private function calculateProjectAge($model)
    {
        $startDate = Carbon::parse($model->start_project);
        $now = Carbon::now();
        $daysDiff = $startDate->diffInDays($now);
        
        // Convert days to years for age calculation (approximation)
        $ageInYears = $daysDiff / 365;
        
        return [
            'days' => $daysDiff,
            'years' => $ageInYears
        ];
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->headerAttribute('class', 'bg-gradient-to-r from-slate-800 to-slate-900 text-white font-bold text-center border-0'),
            
            Column::make('User ID', 'user_id')
                ->headerAttribute('class', 'bg-gradient-to-r from-slate-800 to-slate-900 text-white font-bold text-center border-0'),
            
            Column::make('Nama Project', 'project_name')
                ->sortable()
                ->searchable()
                ->headerAttribute('class', 'bg-gradient-to-r from-slate-800 to-slate-900 text-white font-bold text-center border-0'),
            
            Column::make('Kontrak', 'kontrak')
                ->sortable()
                ->searchable()
                ->headerAttribute('class', 'bg-gradient-to-r from-slate-800 to-slate-900 text-white font-bold text-center border-0'),
            
            Column::make('Vendor', 'vendor')
                ->sortable()
                ->searchable()
                ->headerAttribute('class', 'bg-gradient-to-r from-slate-800 to-slate-900 text-white font-bold text-center border-0'),
            
            Column::make('Tanggal Mulai', 'start_project_formatted', 'start_project')
                ->sortable()
                ->headerAttribute('class', 'bg-gradient-to-r from-slate-800 to-slate-900 text-white font-bold text-center border-0'),
            
            Column::make('Tanggal Selesai', 'end_project_formatted', 'end_project')
                ->sortable()
                ->headerAttribute('class', 'bg-gradient-to-r from-slate-800 to-slate-900 text-white font-bold text-center border-0'),
            
            Column::make('Detail', 'detail')
                ->headerAttribute('class', 'bg-gradient-to-r from-slate-800 to-slate-900 text-white font-bold text-center border-0'),
        ];
    }

    public function filters(): array
    {
        return [
            // Remove filters to show all data
        ];
    }

    // Override the default theme
    public function template(): ?string
    {
        return null;
    }
}