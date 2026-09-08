<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('excerpt_es')->nullable()->after('excerpt');
            $table->string('excerpt_en')->nullable()->after('excerpt_es');
            $table->text('description_es')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_es');
        });

        DB::table('projects')->update([
            'excerpt_es' => DB::raw('excerpt'),
            'description_es' => DB::raw('description'),
        ]);

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('excerpt')->nullable();
            $table->text('description')->nullable();
        });

        DB::table('projects')->update([
            'excerpt' => DB::raw('excerpt_es'),
            'description' => DB::raw('description_es'),
        ]);

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['excerpt_es', 'excerpt_en', 'description_es', 'description_en']);
        });
    }
};
