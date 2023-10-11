<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achats', function (Blueprint $table) {
            $table->id();
            $table->string("nr_de_reglement");//nr_de_reglement date mode reference date_echeance montant_regle code_fournisseur
            $table->string("date");
            $table->string("mode");
            $table->string("reference");
            $table->string("date_echeance");
            $table->decimal('montant_regle', 10, 2);
            $table->string("code_fournisseur");
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('achats');
    }
};
