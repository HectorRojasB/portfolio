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
        Schema::table('interests', function (Blueprint $table) {
            $table->string('name_es')->nullable()->after('name');
            $table->string('name_en')->nullable()->after('name_es');
            $table->text('description_es')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_es');
        });

        DB::table('interests')->update([
            'name_es' => DB::raw('name'),
            'description_es' => DB::raw('description'),
        ]);

        Schema::table('interests', function (Blueprint $table) {
            $table->dropColumn(['name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interests', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->text('description')->nullable();
        });

        DB::table('interests')->update([
            'name' => DB::raw('name_es'),
            'description' => DB::raw('description_es'),
        ]);

        Schema::table('interests', function (Blueprint $table) {
            $table->dropColumn(['name_es', 'name_en', 'description_es', 'description_en']);
        });
    }
};
