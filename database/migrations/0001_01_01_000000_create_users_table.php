<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vl_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastname')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('documentno',15)->nullable();
            $table->string('program',100)->nullable();
            $table->string('token',60)->nullable();
            $table->string('age',10)->nullable();
            $table->enum('isactive',['Y','N'])->default('N');
            $table->enum('isadmin',['Y','N'])->default('N');
            $table->foreignId('team_id');
            $table->rememberToken();
            
            $table->foreignId('leader_id')->nullable();

            $table->foreignId('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('vl_users');
            $table->foreignId('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('vl_users');
            
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        /*
            Creando USUARIOS de prueba
        */
        $u = new User();
        $u->name     = 'elias.fuentes';
        $u->lastname = 'ELIAS FUENTES';
        $u->email    = 'soporte@miasoftware.net';
        $u->password = Hash::make('x5w93kra');
        $u->isactive = 'Y';
        $u->isadmin  = 'Y';
        $u->team_id  = 2;  //Administrador
        $u->token    = $u->get_token(); 
        $u->save();

        $u = new User();
        $u->name     = '40214719';
        $u->lastname = 'ELIAS FUENTES';
        $u->email    = '40214719@contact.com';
        $u->password = Hash::make('x5w93kra');
        $u->isactive = 'Y';
        $u->isadmin  = 'N';
        $u->leader_id= 4; // sujeto con el supervidor
        $u->team_id  = 1;  // Asesor
        $u->token    = $u->get_token(); 
        $u->program  = 'WOM';
        $u->save();
        
        $u = new User();
        $u->name     = '10065309';
        $u->lastname = 'LUIS LOMBARDI';
        $u->email    = '10065309@contact.com';
        $u->password = Hash::make('1235667');
        $u->isactive = 'Y';
        $u->isadmin  = 'N';
        //$u->leader_id= NULL;
        $u->team_id  = 3; // Coordinador
        $u->token    = $u->get_token(); 
        $u->program  = 'WOM';
        $u->save();
        
        $u = new User();
        $u->name     = '10138812';
        $u->lastname = 'PEDRO PICAPIEDRA';
        $u->email    = '10138812@contact.com';
        $u->password = Hash::make('1235667');
        $u->isactive = 'Y';
        $u->isadmin  = 'N';
        //$u->leader_id= NULL;
        $u->team_id  = 4; // Supervisor
        $u->token    = $u->get_token(); 
        $u->program  = 'WOM';
        $u->save();
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vl_users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
