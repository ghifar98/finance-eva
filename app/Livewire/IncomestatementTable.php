<?php

namespace App\Livewire;

use App\Models\Incomestatement;
use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class IncomestatementTable extends PowerGridComponent
{
    public string $tableName = 'incomestatement-table-z6muea-table';

    public string $startDate = '';
    public string $endDate = '';
    public string $projectId = '';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput()
                ->withoutLoading(),
            PowerGrid::footer()
                ->showPerPage(0) // Show all records
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Account::query()
            ->with(['incomestatements' => function ($query) {
                $query->when($this->startDate && $this->endDate, function ($q) {
                    $q->whereHas('purchase', function ($subQ) {
                        $subQ->whereBetween('date', [$this->startDate, $this->endDate]);

                        if ($this->projectId) {
                            $subQ->where('project_id', $this->projectId);
                        }
                    });
                });
            }])
            ->where(function ($query) {
                $query->whereIn('code', ['40000', '50000', '60000', '70000', '80000'])
                    ->orWhere(function ($q) {
                        $q->where('code', 'like', '4%')
                          ->orWhere('code', 'like', '5%')
                          ->orWhere('code', 'like', '6%')
                          ->orWhere('code', 'like', '7%')
                          ->orWhere('code', 'like', '8%');
                    });
            });
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('code')
            ->add('name')
            ->add('total_formatted', function ($model) {
                $total = $this->calculateAccountTotal($model);
                $isImportant = in_array(substr($model->code, 0, 2), ['42', '50']);
                $class = $isImportant ? 'font-bold text-blue-800' : '';
                
                return '<div class="text-right '.$class.'">Rp '.number_format($total, 0, ',', '.').'</div>';
            });
    }

    protected function calculateAccountTotal($model): float
    {
        $total = $model->incomestatements
            ->filter(function ($entry) {
                $valid = true;

                if ($this->startDate && $this->endDate) {
                    $entryDate = optional($entry->purchase)->date;
                    if (!$entryDate || $entryDate < $this->startDate || $entryDate > $this->endDate) {
                        $valid = false;
                    }
                }

                if ($this->projectId) {
                    if (optional($entry->purchase)->project_id != $this->projectId) {
                        $valid = false;
                    }
                }

                return $valid;
            })
            ->sum('nominal');

        // Calculate child accounts if this is a parent account
        if (strlen($model->code) == 5) {
            $prefix = match(true) {
                substr($model->code, -4) === '0000' => substr($model->code, 0, 1),
                substr($model->code, -3) === '000' => substr($model->code, 0, 2),
                default => null
            };

            if ($prefix) {
                $childAccounts = Account::where('code', 'like', $prefix.'%')
                    ->where('code', '!=', $model->code)
                    ->with(['incomestatements.purchase'])
                    ->get();

                foreach ($childAccounts as $child) {
                    $total += $this->calculateAccountTotal($child);
                }
            }
        }

        return $total;
    }

    public function columns(): array
    {
        return [
            Column::make('Account Code', 'code')
                ->sortable()
                ->searchable()
                ->headerAttribute('text-left')
                ->bodyAttribute('font-medium'),

            Column::make('Account Name', 'name')
                ->sortable()
                ->searchable()
                ->headerAttribute('text-left')
                ->bodyAttribute(''), // Removed the Closure, styling is handled in the field formatter

            Column::make('Amount', 'total_formatted')
                ->sortable()
                ->headerAttribute('text-right')
                ->bodyAttribute('text-right'),
        ];
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function filters(): array
    {
        return [];
    }
}