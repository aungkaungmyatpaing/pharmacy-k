<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->string('pregnancy_category')->nullable()->after('route_of_administration')
                ->comment('FDA Pregnancy Categories: A, B, C, D, X, N (Not classified)');
            $table->text('lactation_safety')->nullable()->after('pregnancy_category')
                ->comment('Safety information for breastfeeding mothers');
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['pregnancy_category', 'lactation_safety']);
        });
    }
};
