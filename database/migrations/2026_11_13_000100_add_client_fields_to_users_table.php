<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 40)->nullable()->after('email');
            $table->string('role', 20)->default('client')->after('phone');
            $table->boolean('is_blocked')->default(false)->after('role');
            $table->timestamp('last_login_at')->nullable()->after('is_blocked');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'role', 'is_blocked', 'last_login_at']);
        });
    }
};
