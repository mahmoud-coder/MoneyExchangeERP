<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('duty');
            $table->decimal('salary', 6,2);
            $table->decimal('pension', 4, 2)->default(0);
            $table->decimal('social_insurance', 4, 2);
            $table->boolean('apply_tax_free_amount')->default(true); // tax free amount (NPD) doesn't applay if the employee has 2nd job which apply the NPD on it
            $table->date('joined_at');
            $table->date('left_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
