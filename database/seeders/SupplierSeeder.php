<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->upsert([
            [
                'name' => 'BRP MEXICAN DISTRIBUTION',
                'rfc' => 'BMD080630IU5',
                'parser_key' => 'brp',
                'active' => true,
            ],
            [
                'name' => 'GEELY AUTO MEXICO CORPORATION',
                'rfc' => 'GAM230511HK2',
                'parser_key' => 'geely',
                'active' => true,
            ],
            [
                'name' => 'POLARIS SALES MEXICO',
                'rfc' => 'PSM130805BX6',
                'parser_key' => 'polaris',
                'active' => true,
            ],
            [
                'name' => 'KAWASAKI DE MEXICO',
                'rfc' => 'KME931015PC0',
                'parser_key' => 'kawasaki_mexico',
                'active' => true,
            ],
        ], ['rfc'], [
            'name',
            'parser_key',
            'active',
        ]);
    }
}
