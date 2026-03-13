<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->text('drug_interactions')->nullable()->comment('တခြား ဘယ်ဆေးတွေနဲ့ တွဲမသောက်သင့်ဘူးဆိုတဲ့ အချက်အလက်');
            $table->text('storage_conditions')->nullable()->comment('အပူချိန် ဘယ်လောက်မှာ သိမ်းဆည်းရမလဲ');
            $table->text('overdose_emergency')->nullable()->comment('ဆေးလွန်သွားပါက ကနဦးပြုစုရမည့် လမ်းညွှန်ချက်');
            $table->text('contraindications')->nullable()->comment('လုံးဝ မသုံးသင့်သည့်ရောဂါအခံများ သို့မဟုတ် အခြေအနေများ (ဥပမာ ကိုယ်ဝန်ဆောင်)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn([
                'drug_interactions',
                'storage_conditions',
                'overdose_emergency',
                'contraindications'
            ]);
        });
    }
};
