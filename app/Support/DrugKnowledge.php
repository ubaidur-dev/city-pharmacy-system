<?php

namespace App\Support;

use App\Models\Customer;
use App\Models\Medicine;

/**
 * A small curated reference: ingredient classes, well-established interaction pairs and allergy classes.
 * It supports, and never replaces, the pharmacist's judgement.
 */
class DrugKnowledge
{
    /** class => ingredients */
    public const CLASSES = [
        'nsaid' => ['ibuprofen', 'diclofenac', 'naproxen', 'mefenamic acid', 'ketorolac', 'aspirin', 'celecoxib', 'etoricoxib'],
        'anticoagulant' => ['warfarin', 'apixaban', 'rivaroxaban', 'enoxaparin', 'dabigatran', 'heparin'],
        'statin' => ['atorvastatin', 'rosuvastatin', 'simvastatin', 'pravastatin'],
        'ssri' => ['escitalopram', 'sertraline', 'fluoxetine', 'citalopram', 'paroxetine'],
        'opioid' => ['tramadol', 'codeine', 'morphine', 'oxycodone'],
        'benzodiazepine' => ['clonazepam', 'diazepam', 'alprazolam', 'lorazepam'],
        'raas' => ['lisinopril', 'enalapril', 'ramipril', 'valsartan', 'losartan', 'sacubitril'],
        'k_sparing' => ['spironolactone', 'eplerenone'],
        'nitrate' => ['isosorbide', 'nitroglycerin'],
        'beta_blocker' => ['bisoprolol', 'metoprolol', 'atenolol', 'propranolol', 'carvedilol'],
        'mineral' => ['calcium', 'iron', 'ferrous'],
    ];

    /** [token A, token B, severity, message]. Tokens are ingredients or class names. */
    public const INTERACTIONS = [
        ['nsaid', 'anticoagulant', 'major', 'NSAIDs with anticoagulants greatly raise the risk of serious bleeding.'],
        ['aspirin', 'ibuprofen', 'moderate', 'Ibuprofen can blunt aspirin\'s antiplatelet effect, and both irritate the stomach.'],
        ['nsaid', 'nsaid', 'moderate', 'Two NSAIDs together (duplicate therapy) raise stomach, bleeding and kidney risk.'],
        ['paracetamol', 'paracetamol', 'moderate', 'Duplicate paracetamol: risk of accidental overdose and liver damage.'],
        ['metronidazole', 'warfarin', 'major', 'Metronidazole strongly increases warfarin effect (bleeding risk).'],
        ['ciprofloxacin', 'warfarin', 'moderate', 'Ciprofloxacin can increase INR and bleeding risk with warfarin.'],
        ['clarithromycin', 'statin', 'major', 'Clarithromycin raises statin levels: risk of severe muscle damage.'],
        ['erythromycin', 'statin', 'moderate', 'Erythromycin can raise statin levels and muscle-damage risk.'],
        ['ssri', 'nsaid', 'moderate', 'SSRIs plus NSAIDs increase the risk of gastrointestinal bleeding.'],
        ['ssri', 'tramadol', 'major', 'SSRI with tramadol: risk of serotonin syndrome and seizures.'],
        ['benzodiazepine', 'opioid', 'major', 'Benzodiazepines with opioids can cause dangerous sedation and slowed breathing.'],
        ['raas', 'k_sparing', 'moderate', 'Both raise potassium: risk of hyperkalaemia. Monitor.'],
        ['sildenafil', 'nitrate', 'major', 'Sildenafil with nitrates can cause a severe drop in blood pressure.'],
        ['methotrexate', 'trimethoprim', 'major', 'Trimethoprim with methotrexate can cause severe bone-marrow toxicity.'],
        ['clopidogrel', 'omeprazole', 'moderate', 'Omeprazole can reduce the effect of clopidogrel.'],
        ['levothyroxine', 'mineral', 'moderate', 'Calcium/iron reduce levothyroxine absorption. Separate doses by 4 hours.'],
        ['amlodipine', 'simvastatin', 'moderate', 'Amlodipine raises simvastatin levels; limit the simvastatin dose.'],
        ['salbutamol', 'beta_blocker', 'moderate', 'Beta-blockers can oppose the effect of salbutamol.'],
    ];

    /** allergy keyword => ingredients it covers (plus the keyword itself) */
    public const ALLERGY_CLASSES = [
        'penicillin' => ['amoxicillin', 'ampicillin', 'penicillin', 'cloxacillin', 'flucloxacillin', 'piperacillin', 'clavulanate'],
        'sulfa' => ['sulfamethoxazole', 'cotrimoxazole', 'co-trimoxazole', 'sulfasalazine'],
        'sulpha' => ['sulfamethoxazole', 'cotrimoxazole', 'co-trimoxazole', 'sulfasalazine'],
        'nsaid' => self::CLASSES['nsaid'],
        'nsaids' => self::CLASSES['nsaid'],
        'cephalosporin' => ['cefixime', 'ceftriaxone', 'cefuroxime', 'cefalexin', 'cephalexin', 'cefpodoxime'],
    ];

    /** @return string[] lowercase active ingredients for a medicine */
    public static function ingredients(Medicine $m): array
    {
        $generic = strtolower(trim((string) $m->generic_name));

        return $generic === '' ? [] : array_values(array_filter(array_map('trim', explode('-', $generic))));
    }

    /** Ingredients plus every class they belong to. */
    public static function tokens(Medicine $m): array
    {
        $tokens = self::ingredients($m);
        foreach (self::CLASSES as $class => $members) {
            if (array_intersect($members, $tokens)) {
                $tokens[] = $class;
            }
        }

        return array_values(array_unique($tokens));
    }

    /** @param \Illuminate\Support\Collection<int, Medicine> $medicines */
    public static function interactions($medicines): array
    {
        $medicines = $medicines->values();
        $found = [];

        for ($i = 0; $i < $medicines->count(); $i++) {
            for ($j = $i + 1; $j < $medicines->count(); $j++) {
                $x = self::tokens($medicines[$i]);
                $y = self::tokens($medicines[$j]);
                foreach (self::INTERACTIONS as [$a, $b, $severity, $text]) {
                    $hit = (in_array($a, $x, true) && in_array($b, $y, true)) || (in_array($b, $x, true) && in_array($a, $y, true));
                    // "A with A" rules (duplicates) need the pair to genuinely share that token.
                    if ($hit) {
                        $found[] = ['level' => $severity, 'type' => 'interaction', 'text' => "{$medicines[$i]->name} + {$medicines[$j]->name}: {$text}"];
                        break;
                    }
                }
            }
        }

        return $found;
    }

    public static function allergies(?Customer $customer, $medicines): array
    {
        if (! $customer || trim((string) $customer->allergies) === '') {
            return [];
        }
        $terms = array_filter(array_map(fn ($t) => strtolower(trim($t)), preg_split('/[,;\n]+/', $customer->allergies)));
        $found = [];

        foreach ($medicines as $m) {
            $name = strtolower($m->name);
            $ingredients = self::ingredients($m);
            foreach ($terms as $term) {
                $covered = self::ALLERGY_CLASSES[$term] ?? [$term];
                $match = str_contains($name, $term) || array_intersect($covered, $ingredients) || in_array($term, $ingredients, true);
                if ($match) {
                    $found[] = ['level' => 'major', 'type' => 'allergy', 'text' => "ALLERGY: {$customer->name} is allergic to \"{$term}\" and {$m->name} may contain it."];
                    break;
                }
            }
        }

        return $found;
    }

    /** Same active ingredient(s), in stock, cheapest first. */
    public static function alternatives(Medicine $m, int $limit = 4)
    {
        $generic = strtolower(trim((string) $m->generic_name));
        if ($generic === '') {
            return collect();
        }

        return Medicine::whereRaw('LOWER(generic_name) = ?', [$generic])->where('id', '!=', $m->id)
            ->orderBy('price')->get()
            ->filter(fn ($alt) => $alt->sellableStock() > 0)->take($limit)->values();
    }
}
