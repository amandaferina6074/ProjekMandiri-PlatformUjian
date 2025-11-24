<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('soals', function (Blueprint $table) {
            if (!Schema::hasColumn('soals', 'image_path')) {
                $table->string('image_path')->nullable()->after('pertanyaan');
            }

            if (!Schema::hasColumn('soals', 'type')) {
                $table->enum('type', ['pg', 'esai'])->default('pg')->after('image_path');
            }
        });
    }

    public function down(): void {
        Schema::table('soals', function (Blueprint $table) {
            if (Schema::hasColumn('soals', 'image_path')) {
                $table->dropColumn('image_path');
            }
            if (Schema::hasColumn('soals', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
