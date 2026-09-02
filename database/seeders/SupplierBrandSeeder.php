<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = DB::table('suppliers')
            ->pluck('id', 'rfc');

        $brands = DB::table('brands')
            ->pluck('id', 'name');

        $relations = [
            [
                'supplier_id' => $suppliers['BMD080630IU5'],
                'brand_id' => $brands['CAN-AM'],
            ],
            [
                'supplier_id' => $suppliers['GAM230511HK2'],
                'brand_id' => $brands['GEELY'],
            ],
            [
                'supplier_id' => $suppliers['PSM130805BX6'],
                'brand_id' => $brands['POLARIS'],
            ],
            [
                'supplier_id' => $suppliers['PSM130805BX6'],
                'brand_id' => $brands['INDIAN'],
            ],
            [
                'supplier_id' => $suppliers['KME931015PC0'],
                'brand_id' => $brands['TRIUMPH'],
            ],
        ];

        foreach ($relations as $relation) {
            DB::table('supplier_brands')->updateOrInsert(
                $relation,
                $relation
            );
        }
    }
}
