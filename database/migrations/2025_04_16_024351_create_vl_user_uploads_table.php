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
        Schema::create('vl_user_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('documentno',20)->nullable();
            $table->date('datetrx',20)->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->string('filename',250)->nullable();
            $table->enum('mode',['I','O']);
            $table->string('token',80)->default(DB::raw('UUID()'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vl_user_uploads');
    }
};
