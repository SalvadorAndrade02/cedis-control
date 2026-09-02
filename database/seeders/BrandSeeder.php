<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('brands')->upsert([
            ['name' => 'CAN-AM', 'active' => true],
            ['name' => 'GEELY', 'active' => true],
            ['name' => 'INDIAN', 'active' => true],
            ['name' => 'POLARIS', 'active' => true],
            ['name' => 'TRIUMPH', 'active' => true],
        ], ['name'], [
            'active',
        ]);
    }
}
