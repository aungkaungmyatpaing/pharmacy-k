<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('effects', function (Blueprint $table) {
            $table->string('name')->nullable()->after('slug')
                ->comment('မြန်မာဘာသာ အတိုကောက် နာမည် (ဥပမာ: အဖျားကျ, နာကျင်မှုသက်သာ)');
        });

        Schema::table('side_effects', function (Blueprint $table) {
            $table->string('name')->nullable()->after('slug')
                ->comment('မြန်မာဘာသာ အတိုကောက် နာမည် (ဥပမာ: ပျို့ချင်ခြင်း, ဝမ်းလျှော)');
        });
    }

    public function down(): void
    {
        Schema::table('effects', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('side_effects', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
