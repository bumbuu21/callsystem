<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('user_id'); 

            $table->string('name', 255);
            $table->string('phone', 50)->nullable();

            $table->string('email', 255)->unique();
            $table->string('username', 100)->unique();

            $table->string('password', 255);

            $table->string('status', 50)->default('active'); 
            $table->string('role', 50)->default('agent');     
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
