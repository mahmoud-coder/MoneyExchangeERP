<?php
namespace App\Accounting;

use Illuminate\Support\Arr;
use App\Models\COA;
use App\Models\Transaction;
use App\Models\GeneralJournal as GJ;

class GeneralJournal{
    private function get_transaction_cost($id, $currency_id){
        if(! isset($this->fifo[ $currency_id ])){
            $this->fifo[$currency_id] =  (new FIFO($currency_id) )->get()['data'];
        }
        $fifo_row = Arr::first($this->fifo[$currency_id], fn($t)=> $t['id'] == $id);
        
        return round( array_reduce($fifo_row['cost_of_sold_item'], fn($a,$b)=>$a + $b['amount'] * $b['rate'], 0) , 3);
    }
    
    /**
     * @param boolean $verbose , if true then use clear strings instead of ids, to show in the screen,
     *                           if flase , then use ids, this to store in the database
     */
    public function create_transaction_entity(Transaction $transaction, $date = null , $verbose = false){
        $entity = [
            'date' => $date ?: $transaction->date
        ];
        if(! $verbose){
            $entity['itemable_type'] = Transaction::class;
            $entity['itemable_id'] = $transaction->id;
        }
        if($transaction->type == 1){ // buy transaction
            if($verbose){
                // todo: implement verbose case
                throw new \Exception('The verbose case, is not implemented yet');
            }else{
                $entity['details'] = [
                    [
                        'account_id' => COA::get_crypto_account($transaction->to_currency)->id,
                        'amount' => $transaction->from_amount,
                        'type' => 'debit'
                    ],
                    [
                        'account_id' => COA::get_cash_account()->id,
                        'amount' => $transaction->from_amount,
                        'type' => 'credit'
                    ]
                ];
            }
        }else{ // sell transaction
            if($verbose){
                // todo: implement verbose case
                throw new \Exception('The verbose case, is not implemented yet');
            }else{
                $cost_of_sold_goods = $this->get_transaction_cost($transaction->id, $transaction->from_currency);
                $entity['details'] = [
                    [
                        'account_id' => COA::get_cash_account()->id,
                        'amount' => $transaction->to_amount,
                        'type' => 'debit'
                    ],
                    [
                        'account_id' => COA::get_selling_revenue_account()->id,
                        'amount' => $transaction->to_amount,
                        'type' => 'credit'
                    ],
                    [
                        'account_id' => COA::get_cost_of_sold_goods_account()->id,
                        'amount' => $cost_of_sold_goods,
                        'type' => 'debit'
                    ],
                    [
                        'account_id' => COA::get_crypto_account($transaction->from_currency)->id,
                        'amount' => $cost_of_sold_goods,
                        'type' => 'credit'
                    ]
                ];
            }
        }
        if( (float) $transaction->fees ){
            $entity['details'][] = [
                'account_id' => COA::get_fees_account()->id,
                'amount' => $transaction->fees,
                'type' => 'debit'
            ];
            $entity['details'][] = [
                'account_id' => COA::get_cash_account()->id,
                'amount' => $transaction->fees,
                'type' => 'credit'
            ];
        }
        return $entity;
    }

    public function save_transaction_entity(Transaction $transaction, $date = null){
        $entity = $this->create_transaction_entity($transaction, $date);
        GJ::create( Arr::except($entity, 'details') )->details()->createMany($entity['details']);
    }
    
}