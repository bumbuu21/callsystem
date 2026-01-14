<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->increments('call_id'); 

            
            $table->date('call_date');

            $table->string('caller_name', 255);
            $table->string('caller_phone', 50);

            $table->string('call_type', 50); 
            $table->string('call_from', 100); 

            $table->text('description')->nullable();
            $table->string('status', 50)->default('open'); 

            $table->unsignedInteger('user_id'); 

            $table->timestamps();

            
            $table->index(['call_date']);
            $table->index(['call_type']);
            $table->index(['call_from']);
            $table->index(['status']);
            $table->index(['user_id']);

            $table->foreign('user_id')
                ->references('user_id')
                ->on('user')
                ->onUpdate('cascade')
                ->onDelete('restrict'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
