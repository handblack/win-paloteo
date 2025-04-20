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
        Schema::create('vl_user_config_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_config_id')->constant('vl_user_configs');
            $table->integer('orden')->default(0);
            $table->text('groupname')->nullable();
            $table->string('shortname')->nullable();
            $table->enum('istype',['P','M'])->default('P');
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
        Schema::dropIfExists('vl_user_config_groups');
    }
};
