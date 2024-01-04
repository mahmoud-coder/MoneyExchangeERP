<?php
namespace App\Accounting;

use App\Models\Transaction;
use App\Models\Currency;
use Exception;

class FIFO{
    private $rows = [];
    private $meta = [];
    private $running_stock = [];
    private $transactions;

    /**
     * @param $invoice  int|Null    begin from this tranansaction id , but do not include it (it was in the previous request)
     */
    function __construct($currency_id, $maximum_invoices = null, $begining_inventory = null, $invoice = null){
        $query = Transaction::whereRaw('( `from_currency` = ? OR `to_currency` = ? )', [$currency_id, $currency_id])->orderBy('date')->orderBy('id');
        if($invoice){
            $query->where('date','>=', Transaction::find($invoice)->date)->where('id', '>', $invoice);
        }
        $this->transactions = $query->get();
        if(count($this->transactions) == 0){
            $currency =  Currency::find($currency_id);
            throw new Exception("There are no invoices for $currency->name ($currency->symbol) !");
        }
        if($begining_inventory){
            $this->running_stock = $begining_inventory;
        }
        foreach($this->transactions as $transaction){
            $row['id'] = $transaction->id;
            $row['date'] = $transaction->date;
            if($transaction->type == 1){
                $row['type'] = 'buy';
                $row['amount'] = (float) $transaction->to_amount;
                $row['rate'] = round( $transaction->from_amount / $transaction->to_amount, 3);
                $row['price'] = $transaction->from_amount;
                $this->running_stock[] = [
                    'amount' => $row['amount'],
                    'rate' =>  $row['rate'],
                ];
            }
            if($transaction->type == 2){
                $row['type'] = 'sell';
                $row['cost_of_sold_item'] = $this->reduce_inventory( (float) $transaction->from_amount);
                $row['total_sell_price'] = (float) $transaction->to_amount;
            }
            $row['running_stock'] = $this->running_stock;
            $this->rows[] = $row;
            unset($row);
            if($maximum_invoices && count($this->rows) == $maximum_invoices)break;
        }
        $this->meta['has_more'] = end($this->rows)['id'] !== $this->transactions->last()['id'];
        $this->meta['ending_inventory'] = $this->running_stock;
        $this->meta['last_invoice_id'] = end($this->rows)['id'];
    }

    private function total_stock(){
        return  array_reduce($this->running_stock, fn($a, $b) => $a + $b['amount'], 0);
    }

    private function reduce_inventory($quantity){
        $reducing_details = [];
        if($quantity > $this->total_stock()){
            throw new Exception('There are selling invoices for amounts bigger than what stock have !');
        }
        for(;;){
            if($this->running_stock[0]['amount'] >= $quantity){
                $reducing_details[] = [
                    'amount' => $quantity,
                    'rate' => $this->running_stock[0]['rate']
                ];
                if($this->running_stock[0]['amount'] > $quantity)
                    $this->running_stock[0]['amount'] -= $quantity;
                else
                    array_splice($this->running_stock, 0, 1);
                break;
            }else{
                $reducing_details[] = [
                    'amount' => $this->running_stock[0]['amount'],
                    'rate' => $this->running_stock[0]['rate']
                ];
                $quantity -= $this->running_stock[0]['amount'];
                array_splice($this->running_stock, 0, 1);
            }
        }
        return $reducing_details;
    }

    public function get(){
        return ['data' => $this->rows, 'meta'=>$this->meta ];
    }
}