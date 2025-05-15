<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevenueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenue', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->string('order_id')->nullable();
            $table->string('visitor_id')->nullable();
            $table->string('source')->default('stripe'); // 'stripe' or 'manual'
            $table->date('date');
            $table->timestamps();
            
            // Add the foreign key constraint or uncomment if there's an issue with schema
            // $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revenue');
    }
}
