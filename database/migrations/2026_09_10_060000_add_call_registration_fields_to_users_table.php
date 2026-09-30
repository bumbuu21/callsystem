<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('name');
            $table->string('username', 50)->nullable()->unique()->after('email');
            $table->string('role', 30)->default('customer')->after('password');
            $table->string('status', 20)->default('active')->after('role');
            $table->string('aimag_code', 10)->nullable()->after('status');
            $table->string('aimag_name', 80)->nullable()->after('aimag_code');
            $table->string('soum_code', 10)->nullable()->after('aimag_name');
            $table->string('soum_name', 80)->nullable()->after('soum_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn([
                'phone', 'username', 'role', 'status',
                'aimag_code', 'aimag_name', 'soum_code', 'soum_name',
            ]);
        });
    }
};
