<?php

use App\Models\Media;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Media::ensureTable();
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
