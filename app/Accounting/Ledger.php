<?php
namespace App\Accounting;

use App\Models\COA;
use App\Models\GeneralJournalDetail;
use DB;

class Ledger
{
    private $balances = null;

    public function balances($from = null, $to = null)
    {
        if(! $this->balances){
            if($from || $to){
                $whereClause = 'where ';
                if($from && $to){
                    $whereClause .= "gj.date >= '$from' and gj.date <= '$to'";
                }elseif($from){
                    $whereClause .= "gj.date >= '$from'";
                }else{
                    $whereClause .= "gj.date <= '$to'";
                }
                $balances = DB::select("SELECT a.name, a.code, a.id, a.type, sum(gjd.amount) sum,if(gjd.type = 0, 'debit','credit') debit_credit FROM coa a JOIN general_journal_details gjd ON a.id = gjd.account_id JOIN general_journals gj ON gj.id = gjd.general_journal_id $whereClause GROUP BY gjd.account_id, gjd.type");
            }else{
                $balances = DB::select('SELECT a.name, a.code, a.id, a.type, sum(gjd.amount) sum,if(gjd.type = 0, "debit","credit") debit_credit FROM coa a JOIN general_journal_details gjd ON a.id = gjd.account_id GROUP BY gjd.account_id, gjd.type');
            }
            $balances = collect($balances);
            $balances = $balances->groupBy('name');
            $balances = $balances->map( function($item, $key){
                if($item->count() == 1){
                    $item[0]->sum = (float) $item[0]->sum;
                    return $item[0];
                }else{ // count == 2
                    $type = $item[0]->debit_credit;
                    $sum = (float) $item[0]->sum - (float) $item[1]->sum;
                    if($sum < 0){
                        $type = $item[1]->debit_credit;
                        $sum = abs($sum);
                    }
                    $item[0]->debit_credit = $type;
                    $item[0]->sum = $sum;
                    return $item[0];
                }
            });
            $this->balances = $balances;
        }
        return $this->balances;
    }

    public function revenues_and_expenses_accounts_balances($from = null, $to = null)
    {
        $b = $this->balances($from, $to);
        $b = $b->filter(function($account){
            return (($account->type & 7) == COA::EXPENSE) || (($account->type & 7) == COA::REVENUE);
        });
        $b = $b->groupBy(function($account){
            if(($account->type & 7) ==  COA::EXPENSE) return 'Expense';
            if(($account->type & 7) ==  COA::REVENUE) return 'Revenue';
        });
        return $b;
    }

    public function balances_grouped_by_type($from = null, $to = null)
    {
        return $this->balances($from, $to)->groupBy(function($account){
            if(($account->type & 7) == COA::CURRENT_ASSET) return 'Current Asset';
            if(($account->type & 7) == COA::LONG_TERM_ASSET) return 'Fixed Asset';
            if(($account->type & 7) == COA::CURRENT_LIABILITY) return 'Current Liability';
            if(($account->type & 7) == COA::LONG_TERM_LIABILITY) return 'Long Term Liability';
            if(($account->type & 7) == COA::EQUITY) return 'Equity';
            if(($account->type & 7) == COA::EXPENSE) return 'Expense';
            if(($account->type & 7) == COA::REVENUE) return 'Revenue';
        });
    }
}