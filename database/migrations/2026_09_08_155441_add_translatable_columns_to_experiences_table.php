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
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('position_es')->nullable()->after('position');
            $table->string('position_en')->nullable()->after('position_es');
            $table->text('description_es')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_es');
        });

        DB::table('experiences')->update([
            'position_es' => DB::raw('position'),
            'description_es' => DB::raw('description'),
        ]);

        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn(['position', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('position')->nullable();
            $table->text('description')->nullable();
        });

        DB::table('experiences')->update([
            'position' => DB::raw('position_es'),
            'description' => DB::raw('description_es'),
        ]);

        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn(['position_es', 'position_en', 'description_es', 'description_en']);
        });
    }
};
