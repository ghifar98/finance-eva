<?php

namespace App\Livewire;

use App\Models\MasterProject;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
            ->add('created_at');
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

            // Column::make('Data proyek', 'data_proyek')
            //     ->sortable()
            //     ->searchable(),


            Column::action('Action')
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

    public function actions(MasterProject $row): array
    {
        return [
            // Button::add('edit')
            //     ->slot('Edit: '.$row->id)
            //     ->id()
            //     ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
            //     ->dispatch('edit', ['rowId' => $row->id]),
            // Button::add('detail')
		    //     ->slot('Detail')
		    //     ->class('bg-blue-500 text-white font-bold py-2 px-2 rounded')
		        // ->route('iot.device.setup.detail', ['setupId' => $row->id, 'coopSlug' => $this->coop->coop_slug, 'iotDeviceSlug' => $this->iotDevice->module_coop_slug]),

            // Button::add('edit')
            //     ->slot('Edit')
            //     ->class('bg-blue-500 text-white font-bold py-2 px-2 rounded'),
            //     ->route('master-projects.edit', ['id' => $row->id])

            Button::add('detail')
                ->slot('Detail')
                ->class('bg-blue-500 text-white font-bold py-2 px-2 rounded')
                ->route('master-projects.show', ['id' => $row->id]),

//            Button::add('detail')
//                ->render(function (MasterProject $row) {
//                    // Pastikan route 'master-projects.show' sudah terdefinisi di file routes/web.php Anda.
//                    $detailUrl = route('master-projects.show', ['id' => $row->id]);
//
//                    return '<a href="' . $detailUrl . '" class="bg-blue-500 text-white font-bold py-2 px-2 rounded">Detail</a>';
//                }),
//
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
