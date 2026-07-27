<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    /**
     */
    public function run(): void
    {
        $categories = ['Painkillers', 'Antibiotics', 'Vitamins', 'First Aid'];
        $companies = ['GSK', 'Pfizer', 'Abbott', 'Getz Pharma', 'Bayer'];

        $medicines = [
            ['name' => 'Panadol', 'category' => 'Painkillers', 'company' => 'GSK', 'price' => 250.00, 'stock' => 100, 'expiry_date' => '2026-12-31'],
            ['name' => 'Disprin', 'category' => 'Painkillers', 'company' => 'Reckitt', 'price' => 50.00, 'stock' => 200, 'expiry_date' => '2025-10-15'],
            ['name' => 'Brufen', 'category' => 'Painkillers', 'company' => 'Abbott', 'price' => 180.00, 'stock' => 150, 'expiry_date' => '2026-05-20'],
            ['name' => 'Amoxil', 'category' => 'Antibiotics', 'company' => 'GSK', 'price' => 450.00, 'stock' => 80, 'expiry_date' => '2025-08-12'],
            ['name' => 'Augmentin', 'category' => 'Antibiotics', 'company' => 'GSK', 'price' => 850.00, 'stock' => 60, 'expiry_date' => '2026-02-28'],
            ['name' => 'Flagyl', 'category' => 'Antibiotics', 'company' => 'Sanofi', 'price' => 120.00, 'stock' => 120, 'expiry_date' => '2025-11-30'],
            ['name' => 'Cac-1000 Plus', 'category' => 'Vitamins', 'company' => 'GSK', 'price' => 950.00, 'stock' => 50, 'expiry_date' => '2027-01-01'],
            ['name' => 'Surbex-Z', 'category' => 'Vitamins', 'company' => 'Abbott', 'price' => 600.00, 'stock' => 90, 'expiry_date' => '2026-09-14'],
            ['name' => 'Neurobion', 'category' => 'Vitamins', 'company' => 'Merck', 'price' => 400.00, 'stock' => 110, 'expiry_date' => '2026-03-25'],
            ['name' => 'Pyodine', 'category' => 'First Aid', 'company' => 'Brookes', 'price' => 150.00, 'stock' => 70, 'expiry_date' => '2028-05-05'],
            ['name' => 'Band-Aid', 'category' => 'First Aid', 'company' => 'J&J', 'price' => 20.00, 'stock' => 500, 'expiry_date' => '2030-01-01'],
            ['name' => 'Polymyxin B', 'category' => 'First Aid', 'company' => 'Pfizer', 'price' => 350.00, 'stock' => 40, 'expiry_date' => '2025-12-12'],
            ['name' => 'Ponstan', 'category' => 'Painkillers', 'company' => 'Pfizer', 'price' => 220.00, 'stock' => 130, 'expiry_date' => '2026-07-18'],
            ['name' => 'Azomax', 'category' => 'Antibiotics', 'company' => 'Getz Pharma', 'price' => 520.00, 'stock' => 75, 'expiry_date' => '2025-06-30'],
            ['name' => 'Loprin', 'category' => 'Painkillers', 'company' => 'Highnoon', 'price' => 80.00, 'stock' => 250, 'expiry_date' => '2026-11-11'],
            ['name' => 'Vidaylin', 'category' => 'Vitamins', 'company' => 'Abbott', 'price' => 320.00, 'stock' => 85, 'expiry_date' => '2026-01-20'],
            ['name' => 'Dettol Liquid', 'category' => 'First Aid', 'company' => 'Reckitt', 'price' => 480.00, 'stock' => 100, 'expiry_date' => '2028-10-10'],
            ['name' => 'Ciproxin', 'category' => 'Antibiotics', 'company' => 'Bayer', 'price' => 670.00, 'stock' => 55, 'expiry_date' => '2025-09-09'],
            ['name' => 'Voltral', 'category' => 'Painkillers', 'company' => 'Novartis', 'price' => 310.00, 'stock' => 95, 'expiry_date' => '2026-04-04'],
            ['name' => 'Softin', 'category' => 'First Aid', 'company' => 'Getz Pharma', 'price' => 290.00, 'stock' => 110, 'expiry_date' => '2026-08-22'],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
