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
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return MasterProject::query();
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
            ->add('project_description')
            ->add('tahun')
            ->add('nilai')
            ->add('kontrak')
            ->add('vendor')
            ->add('start_project_formatted', fn (MasterProject $model) => Carbon::parse($model->start_project)->format('d/m/Y'))
            ->add('end_project_formatted', fn (MasterProject $model) => Carbon::parse($model->end_project)->format('d/m/Y'))
            ->add('rab')
            ->add('data_proyek')
            ->add('created_at')
            ->add('detail', fn (MasterProject $model) => Blade::render('<x-button href="'. route('master-projects.show',['id'=>$model->id]) .'" light positive label="Detail" />'));
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('User id', 'user_id'),
            Column::make('Project name', 'project_name')
                ->sortable()
                ->searchable(),

            // Column::make('Project description', 'project_description')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('Tahun', 'tahun')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('Nilai', 'nilai')
            //     ->sortable()
            //     ->searchable(),

            Column::make('Kontrak', 'kontrak')
                ->sortable()
                ->searchable(),

            Column::make('Vendor', 'vendor')
                ->sortable()
                ->searchable(),

            Column::make('Start project', 'start_project_formatted', 'start_project')
                ->sortable(),

            Column::make('End project', 'end_project_formatted', 'end_project')
                ->sortable(),

            Column::make('Rab', 'rab')
                ->sortable()
                ->searchable(),
                Column::make('Detail', 'detail'),

            
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('start_project'),
            Filter::datepicker('end_project'),
        ];
    }

    // #[\Livewire\Attributes\On('edit')]
    // public function edit($rowId): void
    // {
    //     $this->js('alert('.$rowId.')');
    // }

    
}
