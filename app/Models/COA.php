<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class COA extends Model
{
    use HasFactory;
    protected $table = 'coa';
    protected $guarded = []; // all attributes are mass assignable

    const SYSTEM_ACCOUNT = 128; // 1 << 7

    /* MAIN ACCOUNTS: */
    const CURRENT_ASSET = 1;
    const LONG_TERM_ASSET = 2;
    const CURRENT_LIABILITY = 3;
    const LONG_TERM_LIABILITY = 4;
    const EQUITY = 5;
    const REVENUE = 6;
    const EXPENSE= 7;

    /* CURRENT ASSETS */
    const CASH = 0;
    const GOODS = 8; // 1 << 3;

    /* CURRENT LIABILITY */
    const PAYBLE_SALARY = 0;
    const PAYBLE_INSURANCE = 8;
    const PAYBLE_EMPLOYEES_INCOME_TAX = 16;

    /* REVENUE */
    const SELLING_REVENUE = 0;
    
    /** EXPENSES */
    const COST_OF_SOLD_GOODS = 0;
    const FEES = 8; // 1 << 3;
    const WAGES = 16;

    public static function get_cash_account(){
        return self::where('type', self::CURRENT_ASSET | self::CASH | self::SYSTEM_ACCOUNT)->first();
    }
    public static function get_fees_account(){
        return self::where('type', self::EXPENSE | self::FEES | self::SYSTEM_ACCOUNT)->first();
    }
    public static function get_selling_revenue_account(){
        return self::where('type',COA::REVENUE | COA::SELLING_REVENUE | COA::SYSTEM_ACCOUNT)->first();
    }
    public static function get_cost_of_sold_goods_account(){
        return self::where('type',COA::EXPENSE | COA::COST_OF_SOLD_GOODS | COA::SYSTEM_ACCOUNT)->first();
    }
    public static function get_payable_salary_account(){
        return self::where('type', self::CURRENT_LIABILITY | self::PAYBLE_SALARY | self::SYSTEM_ACCOUNT)->first();
    }
    public static function get_payable_insurance_account(){
        return self::where('type', self::CURRENT_LIABILITY | self::PAYBLE_INSURANCE | self::SYSTEM_ACCOUNT)->first();
    }
    public static function get_payable_employees_income_tax_account(){
        return self::where('type', self::CURRENT_LIABILITY | self::PAYBLE_EMPLOYEES_INCOME_TAX | self::SYSTEM_ACCOUNT)->first();
    }
    public static function get_wages_account(){
        return self::where('type', self::EXPENSE | self::WAGES | self::SYSTEM_ACCOUNT)->first();
    }

    /**
     * @param Integer $id the id of the currency 
     */
    public static function get_crypto_account($id){
        return self::where('type',COA::CURRENT_ASSET | COA::GOODS | COA::SYSTEM_ACCOUNT)->where('item_id', $id)->first();
    }
}
