<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Incomestatement;
use Livewire\Component;

class IncomeStatementSummaryTable extends Component
{
    public $startDate;
    public $endDate;
    public $projectId;

    public function render()
    {
        $accounts = Account::all();

        $data = $accounts->map(function ($account) {
            $query = Incomestatement::query();

            if ($this->startDate) {
                $query->whereDate('created_at', '>=', $this->startDate);
            }

            if ($this->endDate) {
                $query->whereDate('created_at', '<=', $this->endDate);
            }

            if ($this->projectId) {
                $query->where('project_id', $this->projectId);
            }

            $debit = (clone $query)->where('debit_account_id', $account->id)->sum('nominal');
            $credit = (clone $query)->where('credit_account_id', $account->id)->sum('nominal');

            return [
                'code' => $account->code,
                'name' => $account->name,
                'pos_laporan' => $account->pos_laporan,
                'debit' => $debit,
                'credit' => $credit,
            ];
        });

        return view('livewire.income-statement-summary-table', [
            'accounts' => $data,
        ]);
    }
}
