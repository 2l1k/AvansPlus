<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowerSmsNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrower_sms_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('borrower_loan_id')->nullable()->comment('Идентификатор займа заёмщика');
            $table->integer('borrower_id')->nullable()->comment('Идентификатор заёмщика');
            $table->text('message')->nullable()->comment('Содержание СМС уведомления');
            $table->string('status', 255)->nullable()->comment('Статус доставки (доставлено\не доставлено)');
            $table->integer('enter')->nullable()->comment('Переход по ссылке');
            $table->string('ref', 255)->nullable()->comment('Дата отправки СМС');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borrower_sms_notifications');
    }
}
