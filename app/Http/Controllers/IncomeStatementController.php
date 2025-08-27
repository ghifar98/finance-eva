<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Purchase;
use App\Models\Incomestatement;
use Illuminate\Http\Request;
use App\Models\MasterProject;

class IncomeStatementController extends Controller
{ 
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'project_id' => 'nullable|exists:master_projects,id',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $projectIdSelected = $request->input('project_id');

        $query = Purchase::query();

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        // Update this line to include start_project and end_project
        $projects = MasterProject::select('id', 'project_name', 'start_project', 'end_project')->get();
        
        // Get selected project details
        $selectedProject = null;
        if ($projectIdSelected) {
            $selectedProject = MasterProject::select('id', 'project_name', 'start_project', 'end_project')
                ->find($projectIdSelected);
        }

        $amount = 0;
        if ($startDate && $endDate && $projectIdSelected) {
            $query->where('project_id', $projectIdSelected);
            $amount = $query->sum('total_amount');
        }

        $incomeQuery = Incomestatement::with(['creditAccount', 'debitAccount']);

        if ($startDate && $endDate) {
            $incomeQuery->whereHas('purchase', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            });
        }

        if ($projectIdSelected) {
            $incomeQuery->whereHas('purchase', function ($q) use ($projectIdSelected) {
                $q->where('project_id', $projectIdSelected);
            });
        }

        $incomestatements = $incomeQuery->get();

        $grouped = [];
        foreach ($incomestatements as $entry) {
            $account = $entry->type === 'credit' ? $entry->creditAccount : $entry->debitAccount;

            if (!$account) continue;

            $code = $account->code;
            $name = $account->name;

            if (!isset($grouped[$code])) {
                $grouped[$code] = [
                    'name' => $name,
                    'code' => $code,
                    'total' => 0,
                ];
            }

            $grouped[$code]['total'] += $entry->nominal;
        }

        ksort($grouped);

        $accounts = Account::query()
            ->with(['incomestatements' => function ($query) use ($startDate, $endDate, $projectIdSelected) {
                $query->when($startDate && $endDate, function ($q) use ($startDate, $endDate, $projectIdSelected) {
                    $q->whereHas('purchase', function ($subQ) use ($startDate, $endDate, $projectIdSelected) {
                        $subQ->whereBetween('date', [$startDate, $endDate]);

                        if ($projectIdSelected) {
                            $subQ->where('project_id', $projectIdSelected);
                        }
                    });
                });
            }])
            ->whereIn('code', ['40000', '50000', '60000', '70000', '80000'])
            ->get();

        $summary = $accounts->map(function ($account) use ($startDate, $endDate, $projectIdSelected) {
            $total = $account->incomestatements
                ->filter(function ($entry) use ($startDate, $endDate, $projectIdSelected) {
                    $valid = true;

                    if ($startDate && $endDate) {
                        $entryDate = optional($entry->purchase)->date;
                        if (!$entryDate || $entryDate < $startDate || $entryDate > $endDate) {
                            $valid = false;
                        }
                    }

                    if ($projectIdSelected) {
                        if (optional($entry->purchase)->project_id != $projectIdSelected) {
                            $valid = false;
                        }
                    }

                    return $valid;
                })
                ->sum('nominal');

            $code = $account->code;

            if (strlen($code) === 5 && substr($code, -4) === '0000') {
                $prefix = substr($code, 0, 1);
                $childAccounts = Account::where('code', 'like', $prefix . '%')
                    ->where('code', '!=', $code)
                    ->with(['incomestatements.purchase'])
                    ->get();

                foreach ($childAccounts as $child) {
                    $childTotal = $child->incomestatements
                        ->filter(function ($entry) use ($startDate, $endDate, $projectIdSelected) {
                            $valid = true;

                            if ($startDate && $endDate) {
                                $entryDate = optional($entry->purchase)->date;
                                if (!$entryDate || $entryDate < $startDate || $entryDate > $endDate) {
                                    $valid = false;
                                }
                            }

                            if ($projectIdSelected) {
                                if (optional($entry->purchase)->project_id != $projectIdSelected) {
                                    $valid = false;
                                }
                            }

                            return $valid;
                        })
                        ->sum('nominal');

                    $total += $childTotal;
                }
            } elseif (strlen($code) === 5 && substr($code, -3) === '000') {
                $prefix = substr($code, 0, 2);
                $childAccounts = Account::where('code', 'like', $prefix . '%')
                    ->where('code', '!=', $code)
                    ->with(['incomestatements.purchase'])
                    ->get();

                foreach ($childAccounts as $child) {
                    $childTotal = $child->incomestatements
                        ->filter(function ($entry) use ($startDate, $endDate, $projectIdSelected) {
                            $valid = true;

                            if ($startDate && $endDate) {
                                $entryDate = optional($entry->purchase)->date;
                                if (!$entryDate || $entryDate < $startDate || $entryDate > $endDate) {
                                    $valid = false;
                                }
                            }

                            if ($projectIdSelected) {
                                if (optional($entry->purchase)->project_id != $projectIdSelected) {
                                    $valid = false;
                                }
                            }

                            return $valid;
                        })
                        ->sum('nominal');

                    $total += $childTotal;
                }
            }

            return [
                'code' => $account->code,
                'name' => $account->name,
                'total_nominal' => $total,
                'formatted' => 'Rp ' . number_format($total, 0, ',', '.'),
            ];
        });

        if ($startDate && $endDate && $projectIdSelected) {
            // Gross Profit
            $grossProfit = $summary->where('code', '40000')->sum('total_nominal') 
                         - $summary->where('code', '50000')->sum('total_nominal');

            // Operating Income
            $operatingIncome = $grossProfit - $summary->where('code', '60000')->sum('total_nominal');

            // Income Before Tax
            $incomeBeforeTax = $operatingIncome - $summary->where('code', '70000')->sum('total_nominal');

            // Other Income Balance
            $otherIncomeBalance = $incomeBeforeTax - $summary->where('code', '80000')->sum('total_nominal');

            // Simpan hasil dalam array jika ingin dikirim ke view
            $calculatedTotals = [
                'gross_profit' => 'Rp ' . number_format($grossProfit, 0, ',', '.'),
                'operating_income' => 'Rp ' . number_format($operatingIncome, 0, ',', '.'),
                'income_before_tax' => 'Rp ' . number_format($incomeBeforeTax, 0, ',', '.'),
                'other_income_balance' => 'Rp ' . number_format($otherIncomeBalance, 0, ',', '.'),
            ];
        } else {
            // Default jika belum ada filter
            $calculatedTotals = [
                'gross_profit' => 'Rp 0',
                'operating_income' => 'Rp 0',
                'income_before_tax' => 'Rp 0',
                'other_income_balance' => 'Rp 0',
            ];
        }

        return view('incomestatement.index', compact(
            'startDate',
            'endDate',
            'amount',
            'projects',
            'projectIdSelected',
            'selectedProject',  // Add this new variable
            'grouped',
            'summary',
            'calculatedTotals'
        ));
    }
}