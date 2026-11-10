<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20)->nullable()->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('phone', 40);
            $table->string('email')->nullable();
            $table->string('client_type', 30)->nullable();
            $table->text('message');
            $table->string('status', 20)->default('new');
            $table->string('priority', 20)->default('normal');
            $table->decimal('price', 12, 2)->nullable();
            $table->date('deadline')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('client_comment')->nullable();
            $table->string('locale', 5)->default('hy');
            $table->string('ip', 45)->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });

        Schema::create('request_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30);
            $table->text('body')->nullable();
            $table->boolean('visible_to_client')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_events');
        Schema::dropIfExists('service_requests');
    }
};
