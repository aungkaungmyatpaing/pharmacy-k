<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->string('dosage_form')->nullable()->after('contraindications')
                ->comment('Tablet, Capsule, Syrup, Injection, Cream, Drop, Patch, etc.');
            $table->string('strength')->nullable()->after('dosage_form')
                ->comment('e.g. 500mg, 250mg/5ml, 0.5%');
            $table->string('route_of_administration')->nullable()->after('strength')
                ->comment('Oral, IV, IM, Topical, Inhaled, Sublingual, etc.');
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['dosage_form', 'strength', 'route_of_administration']);
        });
    }
};
