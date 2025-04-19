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
        Schema::create('vl_user_configs', function (Blueprint $table) {
            $table->id();
            $table->string('configname');
            $table->string('shortname')->nullable();
            $table->enum('isactive',['Y','N'])->default('Y');
            $table->string('token',80)->default(DB::raw('UUID()'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vl_user_configs');
    }
};
