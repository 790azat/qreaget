<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('icon', 40)->default('scale');
            foreach (['hy', 'ru', 'en'] as $l) {
                $table->string("name_$l");
                $table->text("description_$l")->nullable();
            }
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

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

        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('contact')->nullable();
            $table->string('status', 20)->default('open');
            $table->string('ip', 45)->nullable();
            $table->string('page_url')->nullable();
            $table->boolean('unread_admin')->default(false);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_session_id')->constrained()->cascadeOnDelete();
            $table->string('sender', 20); // visitor | admin | system
            $table->string('author')->nullable();
            $table->text('body');
            $table->bigInteger('telegram_message_id')->nullable()->index();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            foreach (['hy', 'ru', 'en'] as $l) {
                $table->string("title_$l");
                $table->text("excerpt_$l")->nullable();
                $table->longText("body_$l")->nullable();
            }
            $table->string('image_url')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            foreach (['hy', 'ru', 'en'] as $l) {
                $table->string("question_$l");
                $table->text("answer_$l");
            }
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->text('details')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['activity_logs', 'settings', 'faqs', 'articles', 'chat_messages', 'chat_sessions', 'request_events', 'service_requests', 'departments'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
