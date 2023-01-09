<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('business');
            $table->string('customer');
            $table->string('type');
            $table->string('currency');
            $table->string('accountName');
            $table->string('accountNumber')->nullable();
            $table->string('accountType');
            $table->double('currentBalance', 8, 2);
            $table->double('availableBalance', 8, 2);
            $table->string('bankCode')->nullable();
            $table->string('provider');
            $table->string('providerReference');
            $table->string('referenceCode');
            $table->string('isDefault');
            $table->string('created_on');
            $table->foreignId('user_id')->constrained();
            $table->string('third_party_id');
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
        Schema::dropIfExists('accounts');
    }
}
