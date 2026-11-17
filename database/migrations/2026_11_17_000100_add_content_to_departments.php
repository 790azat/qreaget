<?php

use App\Models\Department;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Department::ensureContentColumns();
        Department::syncTexts();
    }

    public function down(): void
    {
        foreach (['hy', 'ru', 'en'] as $l) {
            if (Schema::hasColumn('departments', "content_$l")) {
                Schema::table('departments', fn ($t) => $t->dropColumn("content_$l"));
            }
        }
    }
};
