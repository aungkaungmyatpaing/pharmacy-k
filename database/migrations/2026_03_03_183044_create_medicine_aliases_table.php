<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_aliases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')
                ->constrained('medicines')
                ->onDelete('cascade');
            $table->string('alias')
                ->comment('Alternative name, e.g. Panadol, Calpol, ပက်နာဒေါ်');
            $table->string('language')->default('en')
                ->comment('Language code: en, mm, trade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_aliases');
    }
};
