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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('headline_es')->nullable()->after('headline');
            $table->string('headline_en')->nullable()->after('headline_es');
            $table->text('bio_es')->nullable()->after('bio');
            $table->text('bio_en')->nullable()->after('bio_es');
        });

        DB::table('profiles')->update([
            'headline_es' => DB::raw('headline'),
            'bio_es' => DB::raw('bio'),
        ]);

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['headline', 'bio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
        });

        DB::table('profiles')->update([
            'headline' => DB::raw('headline_es'),
            'bio' => DB::raw('bio_es'),
        ]);

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['headline_es', 'headline_en', 'bio_es', 'bio_en']);
        });
    }
};
