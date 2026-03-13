<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pediatric_dosages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')
                ->constrained('medicines')
                ->onDelete('cascade');
            $table->string('age_group')
                ->comment('e.g. Neonate, 1-12 months, 1-5 years, 6-12 years, 12-18 years');
            $table->decimal('weight_kg_min', 5, 1)->nullable()
                ->comment('Minimum body weight (kg) for this dosage');
            $table->decimal('weight_kg_max', 5, 1)->nullable()
                ->comment('Maximum body weight (kg) for this dosage');
            $table->string('slug')
                ->comment('Short label, e.g. pediatric-standard-dose');
            $table->text('text')->nullable()
                ->comment('Full dosage instructions for this age/weight group');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pediatric_dosages');
    }
};
