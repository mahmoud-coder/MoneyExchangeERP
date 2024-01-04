<?php
namespace App\Excel\Imports;

use App\Accounting\GeneralJournal;
use App\Models\{Transaction, Option, Customer, IndividualCustomer, EntityCustomer, PaymentMethod};
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use PhpOffice\PhpSpreadsheet\Shared\Date as D;
use Auth;
use DB;
use Exception;

class TransactionsImport implements WithStartRow, WithLimit, OnEachRow
{
    use Importable;

    public function __construct($start, $limit , $currency_id){
        $this->start = $start;
        $this->limit = $limit;
        $this->currency_id = $currency_id;
        $this->default_currency_id = Option::get('default_currency_id');
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();
        if (!isset($row[0])) return null;
        if(! $row[0]) return null;
        if($payment_method_id = PaymentMethod::where('method', trim($row[6]))->first()){
            $payment_method_id = $payment_method_id->id;
        }else{
            throw new Exception('There are a transaction without an exist payment method');
        }
        $customer = EntityCustomer::where('name', trim($row[1]))->first();
        if(!$customer){
            $customer = IndividualCustomer::whereRaw('name = ? OR CONCAT(name, ", ", surname) = ? OR CONCAT(name," ",surname) = ?', [ $row[1], $row[1], $row[1] ])->first();
        }
        if($customer){
            $customer_id = $customer->customer->id;
        }else{
            $customer = new Customer;
            $customer->creator_type = 'Uploaded Sheet';
            $fullname = trim($row[1]);
            $name = explode(' ', $fullname)[0];
            DB::beginTransaction();
            IndividualCustomer::create([
                'name' => $name,
                'surname' => ltrim( substr($fullname, strlen($name)) )
            ])->customer()->save($customer);
          
            $customer_id = $customer->id;
        }
        if(trim(strtolower($row[2])) == 'buy'){
            $type = 1;
            $from_currency = $this->default_currency_id;
            $from_amount = $row[3];
            $to_currency = $this->currency_id;
            $to_amount =  $row[5];
        }elseif(trim(strtolower($row[2])) == 'sell'){
            $type = 2;
            $from_currency = $this->currency_id;
            $from_amount = $row[5];
            $to_currency = $this->default_currency_id;
            $to_amount =  $row[3];
        }else{
            throw new Exception('There are a tranasaction that is not "Sell" nor "Buy"');
        }
        try{
            $date = D::excelToDateTimeObject($row[0])->format('Y-m-d');
        }catch(Exception $e){
            throw new Exception('There are a tranasaction with invalide date');
        }
        $transaction = Transaction::create([
            'type' => $type,
            'from_currency' => $from_currency,
            'from_amount' => $from_amount,
            'to_currency' => $to_currency,
            'to_amount' =>$to_amount,
            'customer_id' => $customer_id,
            'user_id' => Auth::id(),
            'fees' => $row[7] ?: 0,
            'payment_method_id' => $payment_method_id,
            'date' => $date
        ]);
        (new GeneralJournal)->save_transaction_entity($transaction);
        DB::commit();
    }

    public function startRow() : int
    {
        return $this->start;
    }

    public function limit(): int
    {
        return $this->limit;
    }

}