<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesDetialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices_detials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number', 50);
            $table->string('product', 50);
            $table->string('section', 999);
            $table->string('status', 50);
            $table->integer('value_status');
            $table->date('payment_date')->nullable();
            $table->text('note')->nullable();
            $table->string('user',300);
            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
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
        Schema::dropIfExists('invoices_detials');
    }
}
