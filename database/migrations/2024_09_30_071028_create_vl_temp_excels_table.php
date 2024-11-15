<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vl_temp_excels', function (Blueprint $table) {
            $table->id();
            $c = 'A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z';
            foreach(explode('|',$c) as $k){
                $table->string("col_{$k}",200)->nullable();
            }
            $c = 'A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z';
            foreach(explode('|',$c) as $k){
                $table->string("col_A{$k}",200)->nullable();
            }
            //Aqui agregamos los indices requeridos
            $table->foreignId('order_id')->nullable();
            $table->foreignId('pricelist_id')->nullable();
            $table->foreignId('product_id')->nullable();
            $table->foreignId('bpartner_id')->nullable();
            $table->enum('isprocessed',['Y','N'])->default('N');
            $table->enum('isready',['Y','N'])->default('N');
            $table->string('token',60)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vl_temp_excels');
    }
};
