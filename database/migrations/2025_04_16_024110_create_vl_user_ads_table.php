<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vl_user_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctype_id');
            $table->string('documentno',15)->nullable();
            $table->string('nombre',100)->nullable();
            $table->string('paterno',100)->nullable();
            $table->string('materno',100)->nullable();
            $table->string('email',150)->nullable();
            $table->string('token',80)->default(DB::raw('UUID()'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vl_user_ads');
    }
};
