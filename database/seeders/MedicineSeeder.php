<?php

namespace Database\Seeders;

use App\Models\AdultDosage;
use App\Models\Brand;
use App\Models\Effect;
use App\Models\Medicine;
use App\Models\MedicineAlias;
use App\Models\MedicineCategory;
use App\Models\PediatricDosage;
use App\Models\SideEffect;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════════
        // 1. BRANDS
        // ══════════════════════════════════════════════
        $brandGSK = Brand::create([
            'name'    => 'Panadol',
            'company' => 'GlaxoSmithKline မြန်မာ',
        ]);

        $brandPfizer = Brand::create([
            'name'    => 'Amoxil',
            'company' => 'Pfizer မြန်မာ',
        ]);

        $brandNovartis = Brand::create([
            'name'    => 'Voltaren',
            'company' => 'Novartis မြန်မာ',
        ]);

        // ══════════════════════════════════════════════
        // 2. CATEGORIES
        // ══════════════════════════════════════════════
        $catAnalgesic = MedicineCategory::create([
            'name'        => 'Analgesics',
            'slug'        => 'analgesics',
            'description' => 'နာကျင်မှုများကို သက်သာစေသောဆေးများ။ ခေါင်းကိုက်ခြင်း၊ အဆစ်နာကြင်ခြင်း နှင့် ကိုယ်တွင်းနာကျင်မှုများအတွက် အသုံးပြုသည်။',
        ]);

        $catAntibiotic = MedicineCategory::create([
            'name'        => 'Antibiotics',
            'slug'        => 'antibiotics',
            'description' => 'ဘက်တီးရီးယားပိုးများကို သတ်ဖြတ် သို့မဟုတ် တားဆီးနိုင်သောဆေးများ။ ကူးစက်ရောဂါများကုသရာတွင် ဆရာဝန်ညွှန်ကြားချက်ဖြင့်သာ သောက်သုံးရသည်။',
        ]);

        $catNSAID = MedicineCategory::create([
            'name'        => 'NSAIDs',
            'slug'        => 'nsaids',
            'description' => 'ရောင်ရမ်းမှု၊ နာကျင်မှု နှင့် အဖျားကျဆေးများ (Non-Steroidal Anti-Inflammatory Drugs)။ အဆစ်ရောင်ရောဂါ (Arthritis) အပါအဝင် ရောင်ရမ်းသောနေရာများအတွက် သုံးသည်။',
        ]);

        // ══════════════════════════════════════════════
        // 3. EFFECTS (Therapeutic)
        // ══════════════════════════════════════════════
        $effectFever = Effect::create([
            'slug' => 'fever-reduction',
            'name' => 'အဖျားကျ',
            'text' => 'ကိုယ်အပူချိန်ကျစေပြီး အဖျားကို သက်သာစေသည်',
        ]);

        $effectPain = Effect::create([
            'slug' => 'pain-relief',
            'name' => 'နာကျင်မှုသက်သာ',
            'text' => 'နာကျင်မှုကို လျော့ကျစေပြီး ကောင်းမွန်သောအနားယူမှုကို ရစေသည်',
        ]);

        $effectAntibiotic = Effect::create([
            'slug' => 'antibacterial',
            'name' => 'ဘက်တီးရီးယားသတ်',
            'text' => 'ဘက်တီးရီးယားပိုးများကို သတ်ဖြတ်ကာ ကူးစက်ရောဂါများကို ကုသပေးသည်',
        ]);

        $effectAntiInflammatory = Effect::create([
            'slug' => 'anti-inflammatory',
            'name' => 'ရောင်ရမ်းမှုကျ',
            'text' => 'ရောင်ရမ်းမှုကို လျော့ကျစေပြီး ဖောရောင်ခြင်းနှင့် နာကျင်မှုကို သက်သာစေသည်',
        ]);

        // ══════════════════════════════════════════════
        // 4. SIDE EFFECTS
        // ══════════════════════════════════════════════
        $seLiverDamage = SideEffect::create([
            'slug' => 'liver-damage',
            'name' => 'အသည်းထိခိုက်မှု',
            'text' => 'ဆေးပမာဏ လွန်ကဲပါက အသည်းကို ထိခိုက်နိုင်သည်',
        ]);

        $seNausea = SideEffect::create([
            'slug' => 'nausea',
            'name' => 'ပျို့ချင်ခြင်း',
            'text' => 'ပျို့ချင်ခြင်း၊ အော်ချင်ခြင်း ဖြစ်နိုင်ပြီး ဗိုက်ထဲ မအီမသာ ဖြစ်နိုင်သည်',
        ]);

        $seDiarrhea = SideEffect::create([
            'slug' => 'diarrhea',
            'name' => 'ဝမ်းလျှော',
            'text' => 'ဝမ်းလျှောခြင်း ဖြစ်နိုင်ပြီး ရေဓာတ်ဆုံးရှုံးမှု သတိပြုရသည်',
        ]);

        $seStomachPain = SideEffect::create([
            'slug' => 'stomach-pain',
            'name' => 'အစာအိမ်နာကျင်',
            'text' => 'အစာအိမ်နာကျင်ခြင်း ဖြစ်နိုင်ပြီး အစာသောက်ပြီးမှ ဆေးသောက်သင့်သည်',
        ]);

        $seAllergic = SideEffect::create([
            'slug' => 'allergic-reaction',
            'name' => 'ဓာတ်မတည့်',
            'text' => 'အချို့သောလူများတွင် ဓာတ်မတည့်ခြင်း (အရေပြားနီမြန်းခြင်း၊ ယားယံခြင်း) ဖြစ်နိုင်သည်',
        ]);

        // ══════════════════════════════════════════════
        // 5. MEDICINES
        // ══════════════════════════════════════════════

        // --- Medicine 1: Paracetamol ---
        $paracetamol = Medicine::create([
            'brand_id'               => $brandGSK->id,
            'name'                   => 'Paracetamol 500mg',
            'chemical_name'          => 'Acetaminophen',
            'doctor_prescription'    => false,
            'dosage_form'            => 'Tablet',
            'strength'               => '500mg',
            'route_of_administration' => 'Oral',
            'pregnancy_category'     => 'B',
            'caution'                => 'တစ်နေ့လျှင် 4g ထက်မပိုသောက်ပါနှင့်။ အရက်သောက်သောသူများ ဂရုတစိုက်သုံးပါ။ ကျောက်ကပ် သို့မဟုတ် အသည်းရောဂါရှိသူများ ဆရာဝန်နှင့် တိုင်ပင်ပါ။',
            'moa'                    => 'ဦးနှောက်နှင့် အာရုံကြောစနစ်ထဲရှိ prostaglandin များကို ထုတ်လုပ်မှု လျော့ကျစေကာ နာကျင်မှုနှင့် အဖျားကို သက်သာစေသည်။ COX enzyme ကို ဗဟိုအာရုံကြောစနစ်တွင် ဟန့်တားသည်။',
            'drug_interactions'      => 'Warfarin (သွေးပျော်ဆေး) နှင့် တွဲသောက်ပါက သွေးထွက်နိုင်ချေ တိုးလာသည်။ Isoniazid၊ Rifampicin တို့နှင့် တွဲသောက်ပါက အသည်းထိခိုက်မှု ပိုများနိုင်သည်။',
            'storage_conditions'     => 'အပူချိန် ၂၅°C အောက်တွင် သိမ်းဆည်းပါ။ နေရောင်ခြည်ကင်းသော၊ ပိတ်မိသောနေရာတွင် သိမ်းဆည်းပါ။ ကလေးလက်မမှီနိုင်သောနေရာတွင် ထားပါ။',
            'overdose_emergency'     => 'ဆေးလွန်ပါက ချက်ချင်းဆေးရုံတင်ပါ။ N-acetylcysteine ကုသမှု တတ်နိုင်သမျှ မြန်မြန်ပေးရမည်။ အော်ဆေးပေးခြင်း သို့မဟုတ် အသက်ကြောင်း ဆေးသောက်ခိုင်းခြင်း မပြုပါနှင့်။',
            'contraindications'      => 'ပြင်းထန်သောအသည်းရောဂါ (Severe Hepatic Impairment) ရှိသူများ လုံးဝ မသောက်ရ။',
            'lactation_safety'       => 'နို့တိုက်မိခင်များ သောက်သုံးနိုင်သည်။ မိခင်နို့ထဲသို့ အနည်းငယ်ရောက်သော်လည်း ကလေးကို ထိခိုက်မှု မဖြစ်ပေ။ သို့သော် ပမာဏ ထိန်းချုပ်ပါ။',
        ]);

        // --- Medicine 2: Amoxicillin ---
        $amoxicillin = Medicine::create([
            'brand_id'               => $brandPfizer->id,
            'name'                   => 'Amoxicillin 500mg',
            'chemical_name'          => 'Amoxicillin Trihydrate',
            'doctor_prescription'    => true,
            'dosage_form'            => 'Capsule',
            'strength'               => '500mg',
            'route_of_administration' => 'Oral',
            'pregnancy_category'     => 'B',
            'caution'                => 'ဆရာဝန်ညွှန်ကြားသည့် ဆေးသောက်ရက်ကို ပြည့်စွာပြီးအောင် သောက်ပါ။ ကြားဆိတ်မသောက်ပါနှင့်။ Penicillin ဓာတ်မတည့်သူများ မသောက်ရ။',
            'moa'                    => 'ဘက်တီးရီးယားပိုး၏ အပြင်ပိုးကာကွယ်ရေးနံရံ (Cell Wall) ကို ဖျက်ဆီးကာ ပိုးများကို သေစေသည်။ Beta-lactam antibiotics အုပ်စုဝင်ဖြစ်ပြီး Penicillin-binding proteins (PBPs) ကို ဟန့်တားသည်။',
            'drug_interactions'      => 'Methotrexate နှင့် တွဲသောက်ပါက toxicity ပိုများနိုင်သည်။ Oral contraceptives (မီးဖွားမမဲ့ဆေး) ၏ ထိရောက်မှု လျော့ကျနိုင်သည်။',
            'storage_conditions'     => 'အပူချိန် ၂၅°C အောက်တွင် သိမ်းဆည်းပါ။ Syrup ပုံစံဆိုပါက ရောစပ်ပြီး ၁၄ ရက်အတွင်း သုံးရမည်။ ရေ့ဖရိုဂျ (2-8°C) တွင်ထည့်ပါ။',
            'overdose_emergency'     => 'ဆေးအများကြီး မမှားသောက်မိပါနှင့်။ မမှားသောက်မိပါက ဆေးရုံသွားကာ Supportive treatment ပြုလုပ်ပါ။ Hemodialysis ဖြင့် ဆေးဓာတ်ဖယ်ရှားနိုင်သည်။',
            'contraindications'      => 'Penicillin ဒါမှမဟုတ် beta-lactam antibiotics မည်သည့်အမျိုးအစားကိုမဆို ဓာတ်မတည့်ဖူးသောသူများ လုံးဝ မသောက်ရ။',
            'lactation_safety'       => 'မိခင်နို့ထဲ အနည်းငယ်ရောက်သည်။ ကလေးတွင် ဗိုက်ချုပ်ခြင်း ဖြစ်နိုင်သောကြောင့် ကလေး၏ ကျန်းမာရေးကို စောင့်ကြည့်ပါ။',
        ]);

        // --- Medicine 3: Diclofenac ---
        $diclofenac = Medicine::create([
            'brand_id'               => $brandNovartis->id,
            'name'                   => 'Diclofenac Sodium 50mg',
            'chemical_name'          => 'Diclofenac Sodium',
            'doctor_prescription'    => true,
            'dosage_form'            => 'Tablet',
            'strength'               => '50mg',
            'route_of_administration' => 'Oral',
            'pregnancy_category'     => 'C',
            'caution'                => 'အစာအိမ်ပေါ်ဖိသောကြောင့် အမြဲတမ်း အစာသောက်ပြီးမှ သောက်ပါ။ နှလုံး၊ ကျောက်ကပ်ရောဂါရှိသူများ ဆရာဝန်နှင့် တိုင်ပင်ပါ။',
            'moa'                    => 'COX-1 နှင့် COX-2 enzyme နှစ်မျိုးကို ဟန့်တားကာ prostaglandin ထုတ်လုပ်မှုကို နှိမ်ချသည်။ ဒါကြောင့် ရောင်ရမ်းမှု၊ နာကျင်မှု နှင့် အဖျားများ သက်သာသည်။',
            'drug_interactions'      => 'Aspirin နှင့် တွဲသောက်ပါက သွေးထွက်ဒဏ်ရာ ဖြစ်နိုင်ချေ မြင့်တက်သည်။ ACE inhibitors (သွေးတိုးဆေး) များ၏ ထိရောက်မှု လျော့ကျနိုင်သည်။',
            'storage_conditions'     => 'အပူချိန် ၃၀°C အောက်တွင် သိမ်းဆည်းပါ။ ကလေးလက်မမှီနိုင်သောနေရာတွင် ထားပါ။',
            'overdose_emergency'     => 'ဆေးများများသောက်မိပါက အော်ချင်ဆေး (Activated Charcoal) ကိုချက်ချင်းပေးပြီး ဆေးရုံတင်ပါ။ Gastric lavage ပြုလုပ်နိုင်သည်။',
            'contraindications'      => 'အစာအိမ်အနာ (Peptic Ulcer) ရှိသူ၊ နှလုံးသွေးကြောဆိုင်ရာ ရောဂါ (CABG) ပြီးလူများ၊ ကိုယ်ဝန်ဆောင် တတိယသုံးလပတ် (Third Trimester) တွင် မသောက်ရ။',
            'lactation_safety'       => 'နို့တိုက်မိခင်များ တတ်နိုင်သမျှ ရှောင်ကြဉ်သင့်သည်။ Ibuprofen သည် ပိုမိုဘေးကင်းသော ရွေးချယ်မှုတစ်ခုဖြစ်သည်။',
        ]);

        // ══════════════════════════════════════════════
        // 6. MEDICINE ↔ EFFECTS (Pivot)
        // ══════════════════════════════════════════════
        $paracetamol->effects()->attach([$effectFever->id, $effectPain->id]);
        $amoxicillin->effects()->attach([$effectAntibiotic->id]);
        $diclofenac->effects()->attach([$effectPain->id, $effectAntiInflammatory->id]);

        // ══════════════════════════════════════════════
        // 7. MEDICINE ↔ SIDE EFFECTS (Pivot)
        // ══════════════════════════════════════════════
        $paracetamol->sideEffects()->attach([$seLiverDamage->id, $seNausea->id]);
        $amoxicillin->sideEffects()->attach([$seDiarrhea->id, $seNausea->id, $seAllergic->id]);
        $diclofenac->sideEffects()->attach([$seStomachPain->id, $seNausea->id]);

        // ══════════════════════════════════════════════
        // 8. MEDICINE ↔ CATEGORIES (Pivot)
        // ══════════════════════════════════════════════
        $paracetamol->categories()->attach([$catAnalgesic->id]);
        $amoxicillin->categories()->attach([$catAntibiotic->id]);
        $diclofenac->categories()->attach([$catNSAID->id, $catAnalgesic->id]);

        // ══════════════════════════════════════════════
        // 9. ADULT DOSAGES
        // ══════════════════════════════════════════════
        AdultDosage::create([
            'medicine_id' => $paracetamol->id,
            'slug'        => 'paracetamol-adult-standard',
            'text'        => 'တစ်ကြိမ်လျှင် Tablet ၁-၂ ပြား (500mg-1000mg)၊ တစ်နေ့ ၃-၄ ကြိမ်သောက်သုံးနိုင်သည်။ ဆေးသောက်ကြာချိန် ၄-၆ နာရီ ထိန်းသင့်သည်။ တစ်နေ့လျှင် 4000mg (4g) ထက် မကျော်ရ။',
        ]);

        AdultDosage::create([
            'medicine_id' => $amoxicillin->id,
            'slug'        => 'amoxicillin-adult-standard',
            'text'        => 'တစ်ကြိမ်လျှင် 500mg၊ တစ်နေ့ ၃ ကြိမ် (ကြိုက် ၈ နာရီတစ်ကြိမ်)။ ဆေးသောက်ရက် ၅-၇ ရက် ပြည့်ရမည်။ ပြင်းထန်သောကူးစက်ရောဂါများတွင် 1g တစ်နေ့ ၓ ကြိမ် ဆရာဝန်ညွှန်နိုင်သည်။',
        ]);

        AdultDosage::create([
            'medicine_id' => $diclofenac->id,
            'slug'        => 'diclofenac-adult-standard',
            'text'        => 'တစ်ကြိမ်လျှင် 50mg၊ တစ်နေ့ ၂-၃ ကြိမ် (အစာသောက်ပြီးမှ)။ တစ်နေ့ အများဆုံး 150mg ထက် မကျော်ရ။ ရက်ရှည်သောက်ပါက အသားစပ်ပမာဏ (75mg slow-release) ကို ဆရာဝန် ညွှန်နိုင်သည်။',
        ]);

        // ══════════════════════════════════════════════
        // 10. PEDIATRIC DOSAGES
        // ══════════════════════════════════════════════
        PediatricDosage::create([
            'medicine_id'   => $paracetamol->id,
            'age_group'     => '1-5 years',
            'weight_kg_min' => 10.0,
            'weight_kg_max' => 20.0,
            'slug'          => 'paracetamol-pediatric-1-5yr',
            'text'          => 'ကိုယ်အလေးချိန် kg တစ်ကီလိုလျှင် 15mg။ ပုံမှန်ဆိုပါက တစ်ကြိမ်လျှင် 120mg-250mg (Syrup ပုံစံ)၊ တစ်နေ့ ၄ ကြိမ် အထိ သောက်နိုင်သည်။ ဆေးကြားချိန် ၆ နာရီ ထိန်းရမည်။',
        ]);

        PediatricDosage::create([
            'medicine_id'   => $amoxicillin->id,
            'age_group'     => '6-12 years',
            'weight_kg_min' => 20.0,
            'weight_kg_max' => 40.0,
            'slug'          => 'amoxicillin-pediatric-6-12yr',
            'text'          => 'ကိုယ်အလေးချိန် kg တစ်ကီလိုလျှင် 25mg၊ တစ်နေ့ ၃ ကြိမ် (Syrup 125mg/5ml)။ ဆေးသောက်ရက် ၅-၇ ရက် ပြည့်ရမည်။ ဆရာဝန်ညွှန်ကြားချက်မပါဘဲ ကြားဆိတ်မရပ်ရ။',
        ]);

        PediatricDosage::create([
            'medicine_id'   => $diclofenac->id,
            'age_group'     => '12-18 years',
            'weight_kg_min' => 40.0,
            'weight_kg_max' => 70.0,
            'slug'          => 'diclofenac-pediatric-12-18yr',
            'text'          => '၁၂ နှစ်အောက် ကလေးများ မသုံးရ။ ၁၂-၁၈ နှစ်ကြားဆိုပါက 25mg တစ်နေ့ ၃ ကြိမ် ဆရာဝန်ညွှန်ကြားချက်ဖြင့်သာ သောက်ခွင့်ပြုသည်။',
        ]);

        // ══════════════════════════════════════════════
        // 11. MEDICINE ALIASES
        // ══════════════════════════════════════════════
        MedicineAlias::create([
            'medicine_id' => $paracetamol->id,
            'alias'       => 'Panadol',
            'language'    => 'trade',
        ]);
        MedicineAlias::create([
            'medicine_id' => $paracetamol->id,
            'alias'       => 'ပက်နာဒေါ်',
            'language'    => 'mm',
        ]);
        MedicineAlias::create([
            'medicine_id' => $paracetamol->id,
            'alias'       => 'Calpol',
            'language'    => 'trade',
        ]);

        MedicineAlias::create([
            'medicine_id' => $amoxicillin->id,
            'alias'       => 'Amoxil',
            'language'    => 'trade',
        ]);
        MedicineAlias::create([
            'medicine_id' => $amoxicillin->id,
            'alias'       => 'အမောက်ဆဆလင်',
            'language'    => 'mm',
        ]);

        MedicineAlias::create([
            'medicine_id' => $diclofenac->id,
            'alias'       => 'Voltaren',
            'language'    => 'trade',
        ]);
        MedicineAlias::create([
            'medicine_id' => $diclofenac->id,
            'alias'       => 'ဒိုင်ကလိုဖီနက်',
            'language'    => 'mm',
        ]);
    }
}
