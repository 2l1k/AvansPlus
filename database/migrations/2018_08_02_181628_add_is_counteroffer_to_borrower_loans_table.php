<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsCounterofferToBorrowerLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('borrower_loans', function (Blueprint $table) {
            $table->tinyInteger("is_counteroffer")->nullable()->default(0)->comment("Является встречным предложенем");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('borrower_loans', function (Blueprint $table) {
           $table->dropColumn("is_counteroffer");
        });
    }
}
