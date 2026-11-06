<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Brand => generic, only where the generic is certain. Extra brands can be set from the inventory screen. */
    private array $generics = [
        'Actos 15mg' => 'pioglitazone', 'Amlokind 5mg' => 'amlodipine', 'Amoxil' => 'amoxicillin', 'Atorva' => 'atorvastatin',
        'Augmentin' => 'amoxicillin-clavulanate', 'Azomax' => 'azithromycin', 'Betnesol' => 'betamethasone',
        'Brufen' => 'ibuprofen', 'Brufen Syrup' => 'ibuprofen', 'Calpol Syrup' => 'paracetamol', 'Ciproxin' => 'ciprofloxacin',
        'Claratyne 10mg' => 'loratadine', 'Clexane Injection' => 'enoxaparin', 'Clonotril' => 'clonazepam', 'Concor 5mg' => 'bisoprolol',
        'Defcort' => 'deflazacort', 'Disprin' => 'aspirin', 'Ecosprin' => 'aspirin', 'Eliquis' => 'apixaban',
        'Entresto' => 'sacubitril-valsartan', 'Ethics Paracetamol 500mg' => 'paracetamol', 'Flagyl' => 'metronidazole',
        'Folvite' => 'folic acid', 'Glucophage 500mg' => 'metformin', 'Inspra' => 'eplerenone', 'Januvia' => 'sitagliptin',
        'Levipil' => 'levetiracetam', 'Loprin' => 'aspirin', 'Maxigesic 500mg/150mg' => 'paracetamol-ibuprofen',
        'Meronem Injection' => 'meropenem', 'Nexito' => 'escitalopram', 'Nurofen Zavance 250mg' => 'ibuprofen',
        'Pamol All-Age 250mg' => 'paracetamol', 'Pan-D' => 'pantoprazole-domperidone', 'Panadol Extra' => 'paracetamol-caffeine',
        'Pantocid' => 'pantoprazole', 'Rigix 10mg' => 'cetirizine', 'Risek 40mg' => 'omeprazole', 'Rosuvas' => 'rosuvastatin',
        'Storvas' => 'atorvastatin', 'Telfast 180mg' => 'fexofenadine', 'Thyronorm' => 'levothyroxine', 'Trajenta' => 'linagliptin',
        'Udiliv' => 'ursodeoxycholic acid', 'Ventolin Evohaler 100mcg' => 'salbutamol', 'Voltral' => 'diclofenac', 'Xarelto' => 'rivaroxaban',
    ];

    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 40)->nullable();
            $table->string('phone_key', 20)->nullable()->unique(); // digits only, used for matching
            $table->string('email')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('allergies')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('rx_prescriber')->nullable();
            $table->string('rx_number')->nullable();
        });

        Schema::table('medicines', function (Blueprint $table) {
            $table->string('generic_name')->nullable()->after('name')->index();
            $table->boolean('is_controlled')->default(false);
        });

        foreach ($this->generics as $brand => $generic) {
            DB::table('medicines')->where('name', $brand)->update(['generic_name' => $generic]);
        }
        DB::table('medicines')->where('name', 'Clonotril')->update(['is_controlled' => true]);

        // Existing named customers with a real phone number become customer profiles.
        DB::table('orders')->where('channel', 'customer')->orderBy('id')->each(function ($o) {
            $key = preg_replace('/\D/', '', (string) $o->phone);
            if (strlen($key) < 6) {
                return;
            }
            $id = DB::table('customers')->where('phone_key', $key)->value('id')
                ?? DB::table('customers')->insertGetId(['name' => $o->patient_name, 'phone' => $o->phone, 'phone_key' => $key, 'created_at' => now(), 'updated_at' => now()]);
            DB::table('orders')->where('id', $o->id)->update(['customer_id' => $id]);
        });
    }

    public function down(): void
    {
        Schema::table('medicines', fn (Blueprint $t) => $t->dropColumn(['generic_name', 'is_controlled']));
        Schema::table('orders', function (Blueprint $t) {
            $t->dropConstrainedForeignId('customer_id');
            $t->dropColumn(['rx_prescriber', 'rx_number']);
        });
        Schema::dropIfExists('customers');
    }
};
