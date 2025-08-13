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
        $this->showCheckBox();

        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
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
            ->add('id')
            ->add('code')
            ->add('name')
            ->add('total_nominal', function ($model) {
                // Filter incomestatements sesuai tanggal dan project
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

                // Tambahkan nominal dari akun anak
                $code = $model->code;

                if (strlen($code) == 5 && substr($code, -4) === '0000') {
                    $prefix = substr($code, 0, 1);
                    $childAccounts = Account::where('code', 'like', $prefix . '%')
                        ->where('code', '!=', $code)
                        ->with(['incomestatements.purchase'])
                        ->get();

                    foreach ($childAccounts as $child) {
                        $childTotal = $child->incomestatements
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

                        $total += $childTotal;
                    }
                } elseif (strlen($code) == 5 && substr($code, -3) === '000') {
                    $prefix = substr($code, 0, 2);
                    $childAccounts = Account::where('code', 'like', $prefix . '%')
                        ->where('code', '!=', $code)
                        ->with(['incomestatements.purchase'])
                        ->get();

                    foreach ($childAccounts as $child) {
                        $childTotal = $child->incomestatements
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

                        $total += $childTotal;
                    }
                }

                return 'Rp ' . number_format($total, 0, ',', '.');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id'),
            Column::make('Kode Akun', 'code')->sortable()->searchable(),
            Column::make('Nama Akun', 'name')->sortable()->searchable(),
            Column::make('Total Nominal', 'total_nominal')->sortable()->bodyAttribute('text-right'),
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
