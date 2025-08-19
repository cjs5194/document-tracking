<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = ['OED', 'AFMD', 'PIMD', 'KMD', 'RDD', 'CENTERS'];

        foreach ($divisions as $name) {
            Division::updateOrCreate(['name' => $name]);
        }
    }
}
