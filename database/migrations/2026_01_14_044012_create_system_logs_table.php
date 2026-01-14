<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->increments('log_id'); 

            $table->string('action', 255);
            $table->string('ip_address', 45)->nullable(); 

            $table->dateTime('done_at');

            $table->unsignedInteger('user_id')->nullable(); 

            $table->index(['done_at']);
            $table->index(['user_id']);

            $table->foreign('user_id')
                ->references('user_id')
                ->on('user')
                ->onUpdate('cascade')
                ->onDelete('set null'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
