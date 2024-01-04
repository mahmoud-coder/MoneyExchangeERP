<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsSummaryProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = 'CREATE PROCEDURE transactions_summary(IN from_date DATE,IN to_date DATE)
        BEGIN
            WITH 
	            purchasing AS (SELECT to_currency currency_id, SUM(to_amount) total_purchased FROM transactions WHERE transactions.`type` = 1 AND transactions.date >= from_date AND transactions.date <= to_date GROUP BY to_currency),
	            selling AS (SELECT from_currency currency_id, SUM(from_amount) total_selling FROM transactions WHERE transactions.`type` = 2 AND transactions.date >= from_date AND transactions.date <= to_date GROUP BY from_currency),
	            summary AS (SELECT IFNULL(purchasing.currency_id, selling.currency_id) id, total_purchased, total_selling  FROM purchasing RIGHT JOIN selling ON purchasing.currency_id = selling.currency_id UNION SELECT IFNULL(purchasing.currency_id, selling.currency_id) id, total_purchased, total_selling  FROM purchasing LEFT JOIN selling ON purchasing.currency_id = selling.currency_id)
            SELECT currencies.id, name, symbol,  total_purchased, total_selling
            FROM summary join currencies on summary.id = currencies.id;
        END';
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP PROCEDURE IF EXISTS `transactions_summary`');
    }
}
